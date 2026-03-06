<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleLog extends Command
{
    protected $signature = 'schedule:log';

    protected $description = 'Write log every minute';

    public function handle(): int
    {
        Log::info('Scheduler running at: ' . now());

        $this->info('Log written successfully');

        return Command::SUCCESS;
    }
}