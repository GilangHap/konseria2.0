<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransactionResource;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    protected function afterSave(): void
{
    parent::afterSave();

    $transaction = $this->record;

    // Debugging
    Log::info('Transaction Created', ['transaction' => $transaction]);

    $tickets = [];
    for ($i = 0; $i < $transaction->quantity; $i++) {
        $tickets[] = [
            'transaction_id' => $transaction->id,
            'event_id' => $transaction->event_id,
            'ticket_code' => 'TICKET-' . $transaction->event_id . '-' . strtoupper(uniqid()),
            'is_redeemed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Ticket::insert($tickets);

    // Debugging
    Log::info('Tickets Created', ['tickets' => $tickets]);
}

}
