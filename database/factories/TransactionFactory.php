<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $ticket_price = $this->faker->numberBetween(50000, 200000);
        $jumlah_ticket = $this->faker->numberBetween(1, 5);

        return [
            'event_id' => \App\Models\Event::factory(),
            'quantity' => $jumlah_ticket,
            'total_price' => $ticket_price * $jumlah_ticket,
            'buyer_name' => $this->faker->name(),
            'buyer_email' => $this->faker->safeEmail(),
            'buyer_nik' => $this->faker->unique()->numerify('##########'),
        ];
    }
}
