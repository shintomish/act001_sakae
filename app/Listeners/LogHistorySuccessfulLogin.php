<?php

namespace App\Listeners;

use App\Models\AdminLoginLog;
use App\Models\AdminStaffLoginLog;
use App\Models\ClientAdminLoginLog;
use App\Models\ClientStoreAdminLoginLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mobile_Detect;

class LogHistorySuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        $detect = new Mobile_Detect();

        if ($detect->isMobile()) {
            if($detect->isTablet()){
                $device = 'タブレット';
            } else {
                $device = 'スマートフォン';
            }
        } else {
            $device = 'パソコン';
        }

        $logHistory = new LogHistory();
        $logHistory->action = 'ログイン';
        $logHistory->device = $device;
        $logHistory->user_agent = request()->header('User-Agent');
        $logHistory->ip_addr = request()->ip();
        $logHistory->email = $user->email;
        $logHistory->date = date('Y-m-d H:i:s');
        $logHistory->save();
    }
}
