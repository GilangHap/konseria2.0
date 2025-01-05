<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\ReceipMail;
use Illuminate\Support\Facades\Mail;
use Midtrans;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('payment', compact('event'));
    }

    public function generateSnapToken(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'nik' => 'required|string|max:20',
                'quantity' => 'required|integer|min:1|max:5',
                'total_price' => 'required|numeric|min:0',
            ]);

            $event = Event::findOrFail($request->event_id);

            // Check if there are enough tickets available
            if ($event->ticket_quota < $request->quantity) {
                return response()->json(['error' => 'Not enough tickets available.'], 400);
            }

            // Create a new transaction
            $transaction = Transaction::create([
                'event_id' => $request->event_id,
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'quantity' => $request->quantity,
                'total_price' => $request->total_price,
            ]);

            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->uuid,
                    'gross_amount' => $request->total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;
            $transaction->save();
            
            $transaction->createTickets();
        
            // Update the ticket quota
            $event = $transaction->event;
            $event->ticket_quota -= $transaction->quantity;
            $event->save();
        
            // Send transaction receipt email
            Mail::to($transaction->email)->send(new ReceipMail($transaction));
        
            Log::info('Receipt email sent.');
            
            return response()->json(['snapToken' => $snapToken, 'transaction_uuid' => $transaction->uuid]);

        } catch (\Exception $e) {
            Log::error('Error generating SnapToken: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function transactionDetails($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with('tickets', 'event')->firstOrFail();

        if ($transaction->status == 'success' && $transaction->tickets->isEmpty()) {
            // Create tickets for the transaction
        }

        return view('transaction-details', compact('transaction'));
    }

    public function handleNotification(Request $request)
    {
        $notification = new \Midtrans\Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        $transaction = Transaction::where('uuid', $orderId)->firstOrFail();

        if ($transactionStatus == 'settlement') {
            // Update transaction status
            $transaction->status = 'success';
            $transaction->save();
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
            $transaction->save();
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $transaction->status = 'failed';
            $transaction->save();
        }

        return response()->json(['status' => 'ok']);
    }

    public function downloadTicket($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with('tickets')->firstOrFail();
        $pdf = PDF::loadView('emails.receipt', compact('transaction'));
        return $pdf->download('transaction_receipt.pdf');
    }
}

