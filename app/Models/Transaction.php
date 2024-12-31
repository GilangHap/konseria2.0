<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'nik',
        'quantity',
        'total_price',
        'uuid',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function createTickets()
    {
        $tickets = [];
        for ($i = 0; $i < $this->quantity; $i++) {
            $tickets[] = [
                'transaction_id' => $this->id,
                'event_id' => $this->event_id,
                'ticket_code' => 'TICKET-' . $this->event_id . '-' . strtoupper(uniqid()),
                'is_redeemed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        \App\Models\Ticket::insert($tickets);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}