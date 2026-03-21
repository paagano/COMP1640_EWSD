<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   //Defining the application's command schedule. 
   // This method is used to schedule periodic tasks, such as sending reminders to coordinators.
    protected function schedule(Schedule $schedule): void
    {

        // Schedule the coordinator reminders command to run daily. I can adjust the frequency as needed (e.g., hourly, weekly).
        // PRODUCTION - run reminders DAILY: php artisan schedule:run => to run the scheduler manually (for testing) OR set up a cron job to run it automatically every minute for production.
        $schedule->command('reminders:coordinators')->daily(); 
        
        // TESTING - run every minute: php artisan schedule:work   -> to start the scheduler and keep it running.
        // $schedule->command('reminders:coordinators')->everyMinute(); 
    }

  
    // Register the commands for the application. 
    // This method loads the command classes from the specified directory and allows for additional command registration if needed.
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
