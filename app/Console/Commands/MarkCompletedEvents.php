<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class MarkCompletedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:mark-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark events as completed if their date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $completedEvents = Event::where('status', 'active')
            ->whereDate('date', '<', now())
            ->update(['status' => 'completed']);

        $this->info("$completedEvents events marked as completed.");
    }
}
