<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contribution;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class SendCoordinatorReminders extends Command
{
    protected $signature = 'reminders:coordinators';

    protected $description = 'Send SLA reminder emails to faculty coordinators';

    // ---------------------------------------------------------------------------
    // CONFIGURABLE PARAMETERS: Adjust these values for testing or production use.
    // ---------------------------------------------------------------------------
    
    // php artisan reminders:coordinators (custom command) OR php artisan schedule:run (Laravel scheduler) => the command to run the reminders manually. it will check contributions and send emails based on the defined time thresholds.
    // php artisan schedule:work   -> to start the scheduler and keep it running continuously.

    // Time unit for testing: Set to 'minutes' for quick testing, I will switch to 'days' for production. 
    
    private $timeUnit = 'days';     // options: 'days' or 'minutes'

    private $friendlyReminder = 7;  // Day 7 reminder Testing: 1 minute reminder
    private $slaWarning = 12;       // Day 12 warning Testing: 2 minutes warning
    private $slaBreach = 14;        // Day 14 breach Testing: 3 minutes breach

    private $managerRole = 'Marketing Manager'; // Role used for escalation



    public function handle()
    {
        $contributions = Contribution::whereDoesntHave('comments')
            ->with(['faculty.coordinator', 'student'])
            ->get();

        foreach ($contributions as $contribution) {

           
            // Calculate pending time
            if ($this->timeUnit === 'minutes') {
                $pendingTime = Carbon::parse($contribution->created_at)->diffInMinutes(now());
            } else {
                $pendingTime = Carbon::parse($contribution->created_at)->diffInDays(now());
            }

            $coordinator = $contribution->faculty->coordinator;

            if (!$coordinator || !$coordinator->email) {
                continue;
            }

            $type = null;

          
            // SLA Logic
            if ($pendingTime == $this->friendlyReminder) {
                $type = 'friendly';
            }

            elseif ($pendingTime == $this->slaWarning) {
                $type = 'warning';
            }

            elseif ($pendingTime >= $this->slaBreach) {
                $type = 'breach';
            }

            if ($type) {

               
                // Send reminder to Faculty Coordinator
                Mail::to($coordinator->email)
                    ->send(new \App\Mail\CoordinatorReminderMail(
                        $contribution,
                        $pendingTime,
                        $type
                    ));

                // Escalation to University Marketing Manager
                if ($type === 'breach') {

                    $manager = User::role($this->managerRole)->first();

                    if ($manager && $manager->email) {

                        Mail::to($manager->email)
                            ->send(new \App\Mail\SlaEscalationMail(
                                $contribution,
                                $pendingTime,
                                $coordinator
                            ));
                    }
                }

                $this->info("Reminder sent for contribution {$contribution->id} (Pending: {$pendingTime} {$this->timeUnit})");
            }
        }

        return Command::SUCCESS;
    }
}