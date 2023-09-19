<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('clinician:not-fixed-notes')->mondays()->at('08:00');
        // $schedule->command('location:not-fixed-notes')->mondays()->at('08:00');


        // what's the timezone we need to setup
        // $schedule->command('location:not-fixed-notes')->thursdays()->at('12:07');

        $schedule->command('clinician:not-fixed-notes')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
