<?php

namespace App\Providers;

use App\Models\Email;
use App\Models\EmailArchive;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use \Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\View;
use App\Services\DafFlowService;
use App\Models\DAFAccount;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            self::updateEmailStatus($event->job, Email::STATUS_SENDING, "Queue::before");
        });

        Queue::after(function (JobProcessed $event) {
            self::updateEmailStatus($event->job, Email::STATUS_SENT, "Queue::after");
        });

        Queue::failing(function (JobFailed $event) {
            self::updateEmailStatus($event->job, Email::STATUS_FAILED, "Queue::failing");
        });
        
        
        // Share sponsor-specific DAF left menu with all views
        // 1. Get DAF account ID from route/query
        // 2. Fetch sponsor_id from DAFAccount
        // 3. Load config, filter enabled steps, and build menu
        // 4. Provide $dafLeftMenu to Blade

        View::composer('*', function ($view) {

            // Step 1: Get DAF account ID from route or query
            $dafId = request()->route('id') ?: request()->query('id');

            if (!$dafId || !is_numeric($dafId)) {
                $view->with('dafLeftMenu', []);
                return;
            }

            // Step 2: Load DAF account
            $dafAccount = DAFAccount::find($dafId);

            if (!$dafAccount) {
                $view->with('dafLeftMenu', []);
                return;
            }

            // Step 3: Build menu using DafFlowService
            $flow = new DafFlowService();
            $flow->loadConfig($dafAccount->sponsor_id, $dafId);

            // 🔥 ONLY this is needed
            $menu = $flow->buildLeftNavigation();

            // Step 4: Share with all views
            $view->with([
                'dafLeftMenu' => $menu,
                'dafId'       => $dafId,
            ]);
        });
    }

    static public function updateEmailStatus(Job $job, $status, $where)
    {
        if ($job->getQueue() === 'emails') {
            try {
                $payload = $job->payload();
                $command = unserialize($payload['data']['command']);
                $emailArchiveId = $command->mailable->emailArchiveId;
                Log::channel('jobs')->info($where . ' emailArchiveId=' . $emailArchiveId);

                $email = EmailArchive::find($emailArchiveId);
                if ($email) {
                    $email->status = $status;
                    $email->save();
                } else {
                    Log::channel('jobs')->info($where . ' EmailArchiveId not found!');
                }
            } catch (\Exception $e) {
                $message = 'Exception in ' . $where;
                $message .= ', Code:' . $e->getCode() . ', Message:' . $e->getMessage();
                Log::channel('jobs')->info($message);
            }
        }
    }
}
