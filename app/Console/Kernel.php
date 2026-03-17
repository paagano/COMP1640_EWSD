<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   //Defining the application's command schedule.
    protected function schedule(Schedule $schedule): void
    {

        // Schedule the coordinator reminders command to run daily. I can adjust the frequency as needed (e.g., hourly, weekly).
        $schedule->command('reminders:coordinators')->daily(); 
        
        // TESTING - run every minute: php artisan schedule:work   -> to start the scheduler.
        // $schedule->command('reminders:coordinators')->everyMinute(); 
    }

  
    // Register the commands for the application.  
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
