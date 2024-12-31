<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Verifikasi tiket berdasarkan kode tiket.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        // Validasi input
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        // Cari tiket berdasarkan kode tiket
        $ticket = Ticket::where('ticket_code', $request->ticket_code)->first();

        // Jika tiket tidak ditemukan
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        // Ambil data event terkait tiket
        $event = $ticket->event;

        // Validasi apakah event_id dan author_id sesuai dengan ID pengguna yang sedang login
        if (Auth::user()->role !== 'admin' && $event->author_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this ticket.',
            ], 403);
        }

        // Periksa status tiket
        if ($ticket->is_redeemed) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket has already been redeemed.',
            ], 400);
        }

        // Tandai tiket sebagai redeemed
        $ticket->is_redeemed = true;
        $ticket->save();

        // Kembalikan data tiket
        return response()->json([
            'success' => true,
            'message' => 'Ticket redeemed successfully.',
            'ticket' => [
                'code' => $ticket->ticket_code,
                'event' => $event->title ?? 'Unknown Event',
                'owner' => $ticket->transaction->name ?? 'Unknown Owner',
                'status' => 'Redeemed',
            ],
        ]);
    }
}
