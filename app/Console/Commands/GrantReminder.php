<?php

namespace App\Console\Commands;

use App\Models\ClientEnv;
use App\Models\Email;
use App\Models\GrantItem;
use Illuminate\Console\Command;
use \Illuminate\Support\Facades\Log;

class GrantReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:pending_grant';

    /**
     * The console command description.
     * Send an email reminder for the grants which are in cart for longer than X weeks and less than x+n weeks
     * @var string
     */
    protected $description = 'Email reminder for grants in the cart';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Log::channel('cron')->info('Execute Grant Reminder process');

        if (!ClientEnv::feature('GRANT_REMINDER_MAIL')) {
            Log::channel('cron')->info('Grant Reminder - Not configured');
            return;
        }

        $pairs = $this->getDatePairs();
        if (!count($pairs)) return;

        // get all matching records
        $records = GrantItem::where(['status' => GrantItem::STATUS_CREATED])
            ->where(function($query) use ($pairs){
                foreach($pairs as $i => $pair) {
                    if ($i==0) {
                        $query->whereBetween('last_updated', $pair);
                    } else {
                        $query->orWhereBetween('last_updated', $pair);
                    }
                }
            }) // ->toSql();
            ->get()
            ->groupBy('contact_id');

        Log::channel('cron')->info('Grant Reminder - count of donors=' . count($records));

        Email::cronGrantReminder($records);
    }

    private function getDatePairs()
    {
        $pairs = [];

        $minDays = ClientEnv::value('GRANT_REMINDER_AFTER_DAYS');
        $gapDays = ClientEnv::value('GRANT_REMINDER_GAP_DAYS');
        $maxReminders = ClientEnv::value('GRANT_REMINDER_MAX');

        // validate configured values
        if ($minDays < 1 || $gapDays < 1 || $maxReminders < 1) {
            Log::channel('cron')->info('GrantReminder: BadData, afterDays=' . $minDays . ', gapDays=' . $gapDays . ', maxReminders=' . $maxReminders);
            return $pairs;
        }

        for($i=0; $i<$maxReminders; $i++) {
            $maxDays = $minDays + 1;
            // get from-date with maxDays and to-date with min-days
            $fromDate = date('Y-m-d', strtotime("-" . $maxDays . " days"));
            $toDate = date('Y-m-d', strtotime("-" . $minDays . " days"));
            $pairs[] = [$fromDate, $toDate];
            $minDays += $gapDays;
        }
        return $pairs;

    }
}