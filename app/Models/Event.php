<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'date', 'time', 'location', 'price', 'ticket_quota', 'status','image', 'author_id'];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
