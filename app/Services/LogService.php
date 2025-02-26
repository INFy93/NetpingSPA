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
        $users = User::select('id', 'telegram_user_id', 'order_email')->get();
        $current_user = Auth::user();
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');

        $log = new Log();

        $state = '';

        match ($action) {
            1 => $state = 'Cнята с охраны',
            2 => $state = 'Поставлена на охрану',
            3 => $state = 'Включен вентилятор',
            4 => $state = 'Отключен вентилятор',
            default => 'Ошибка: не получено действие!'
        };

        $log->user_id = $current_user->id;
        $log->netping_id = $netping_id;
        $log->action_id = $action;
        $log->save();

        foreach ($users as $user) {
            if ($user->telegram_user_id)
                dispatch(new QueueSenderTelegram($user, $current_user->name, $user->telegram_user_id, $netping_name, $state, $current_date, $current_time));
        }
    }
}

