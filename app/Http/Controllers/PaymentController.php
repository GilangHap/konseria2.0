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

class PaymentController extends Controller
{
    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('payment', compact('event'));
    }

    public function generateSnapToken(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nik' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1|max:5',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check if there are enough tickets available
        if ($event->ticket_quota < $request->quantity) {
            return redirect()->route('payment.show', $request->event_id)->with('error', 'Not enough tickets available.');
        }

        // Create a new transaction
        $transaction = Transaction::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $event->price,
        ]);

        // Create tickets for the transaction
        $transaction->createTickets();

        // Update the ticket quota
        $event->ticket_quota -= $request->quantity;
        $event->save();

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->uuid,
                'gross_amount' => $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $transaction->name,
                'email' => $transaction->email,
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $transaction->snap_token = $snapToken;
        $transaction->save();

        return view('payment', compact('event', 'transaction'))->with('success', 'Snap token generated successfully.');
    }

    public function transactionDetails($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with('tickets')->firstOrFail();
        return view('transaction-details', compact('transaction'));
    }
}
