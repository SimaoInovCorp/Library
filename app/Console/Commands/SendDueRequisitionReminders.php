<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requisition;
use App\Notifications\RequisitionDueReminderNotification;

class SendDueRequisitionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-due-requisition-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users with requisitions due tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = $tomorrow->copy()->endOfDay();

        $requisitions = Requisition::where('status', 'approved')
            ->whereDate('expected_end_at', $tomorrow->toDateString())
            ->with('user', 'book')
            ->get();

        $count = 0;
        foreach ($requisitions as $req) {
            if ($req->user) {
                $req->user->notify(new RequisitionDueReminderNotification($req));
                $count++;
            }
        }

        $this->info("Sent $count due reminder(s).");
    }
}
