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
        // Run recurring transactions daily at 2:00 AM
        $schedule->command('recurring:run')->dailyAt('02:00');

        // Email overdue invoice reminders every morning at 8:00 AM
        $schedule->command('invoices:overdue-reminders')->dailyAt('08:00');

        // Auto-mark invoices overdue
        $schedule->command('invoices:mark-overdue')->dailyAt('00:10');

        // Automated database backup every night at 1:00 AM
        $schedule->command('backup:run --only-db')->dailyAt('01:00');
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
