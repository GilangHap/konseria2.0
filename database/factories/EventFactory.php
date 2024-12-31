<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'time' => $this->faker->time,
            'location' => $this->faker->address,
            'price' => $this->faker->numberBetween(50000, 200000),
            'ticket_quota' => $this->faker->numberBetween(1, 100),
            'image' => 'https://artatix.co.id/img/event_banner/13028851-scriptbannerotello.png', // Added image field
            'author_id' => \App\Models\User::factory(), // Assuming you have a UserFactory
        ];
    }
}