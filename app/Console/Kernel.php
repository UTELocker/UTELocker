<?php

namespace App\Console;

use App\Classes\Scheduler\ApprovedBookingTask;
use App\Classes\Scheduler\ExpireTokenTask;
use App\Classes\Scheduler\OverdueWarningBookingTask;
use App\Classes\Scheduler\OverdueBookingTask;
use App\Classes\Scheduler\WarningExpireTask;
use App\Classes\Scheduler\LockedBookingTask;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(new OverdueWarningBookingTask())->everyMinute();
        $schedule->call(new ApprovedBookingTask())->everyMinute();
        $schedule->call(new OverdueBookingTask())->everyMinute();
        $schedule->call(new WarningExpireTask())->everyMinute();
        $schedule->call(new ExpireTokenTask())->everyMinute();
        $schedule->call(new LockedBookingTask())->everyMinute();
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
