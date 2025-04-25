<?php

namespace App\Console\Commands;

use App\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckBirthdaysAndAnniversaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday and anniversary reminders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today();
        $reminderStart = $today->copy()->subDays(7);

        $members = Member::whereNotNull('dob')
            ->orWhereNotNull('wedding_anniversary')
            ->get();

        foreach ($members as $member) {
            // Handle Birthday
            if ($member->dob) {
                $dob = Carbon::parse($member->dob)->setYear($today->year);
                if ($dob->between($reminderStart, $today)) {
                    // Send reminder to admin
                    Mail::raw("Reminder: {$member->title} {$member->firstname} {$member->lastname}'s birthday is on {$dob->toFormattedDateString()}", function ($message) {
                        $message->to('odejinmiabraham@gmail.com')->subject('Birthday Reminder');
                        $message->to('odejinmiabraham@gmail.com')->subject('Birthday Reminder');
                    }
                    );
                }

                if ($dob->isToday()) {
                    if ($member->email != null) {
                        // Send birthday email to member
                        Mail::raw("Happy Birthday {$member->title} {$member->firstname}!", function ($message) use ($member) {
                            $message->to($member->email)->subject('Happy Birthday!');
                        });
                    }
                }
            }

            // Handle Wedding Anniversary
            if ($member->wedding_anniversary) {
                $anniversary = Carbon::parse($member->wedding_anniversary)->setYear($today->year);
                if ($anniversary->between($reminderStart, $today)) {
                    // Send reminder to admin
                    Mail::raw("Reminder: {$member->title} {$member->firstname} {$member->lastname}'s anniversary is on {$anniversary->toFormattedDateString()}", function ($message) {
                        $message->to('odejinmiabraham@gmail.com')->subject('Anniversary Reminder');
                    });
                }

                if ($anniversary->isToday()) {
                    if ($member->email != null) {
                        // Send anniversary email to member
                        Mail::raw("Happy Wedding Anniversary {$member->title} {$member->firstname}!", function ($message) use ($member) {
                            $message->to($member->email)->subject('Happy Anniversary!');
                        });
                    }
                }
            }
        }

        $this->info('Reminder check completed.');
    }
}
