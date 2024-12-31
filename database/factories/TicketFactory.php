<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => \App\Models\Transaction::factory(),
            'event_id' => \App\Models\Event::factory(),
            'ticket_code' => 'TICKET-' . $this->faker->unique()->randomNumber(5), 
        ];
    }
}