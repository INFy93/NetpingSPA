<?php

namespace App\Services;

use App\Jobs\QueueSenderEmail;
use App\Jobs\QueueSenderTelegram;
use App\Models\Log;
use App\Models\User;
use App\Notifications\Telegram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LogService
{
    public function logging($netping_id, $netping_name, $action): void
    {
        $users = User::all();
        $log = new Log();

        $state = $action == 1 ? 'Снята с охраны' : 'Поставлена на охрану';

        $log->user_id = Auth::id();
        $log->netping_id = $netping_id;
        $log->action_id = $action;
        $log->save();

        foreach ($users as $user) {
            if ($user->telegram_user_id)
           dispatch(new QueueSenderTelegram($user, Auth::user()->name, $user->telegram_user_id, $netping_name, $state, date('Y-m-d'), date('H:i:s')));
            if ($user->order_email == 1) {
                dispatch(new QueueSenderEmail($user->email, Auth::user()->name, $netping_name, $state, date('H:i:s'), date('Y-m-d')));
            }
        }
    }
}
