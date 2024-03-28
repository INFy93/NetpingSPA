<?php

namespace App\Jobs;

use App\Notifications\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class QueueSenderTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $user_name;
    public $telegram_user_id;
    public $netping_name;
    public $state;
    public $date;
    public $time;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $user_name, $telegram_user_id, $netping_name, $state, $date, $time)
    {
        $this->user = $user;
        $this->user_name = $user_name;
        $this->telegram_user_id = $telegram_user_id;
        $this->netping_name = $netping_name;
        $this->state = $state;
        $this->date = $date;
        $this->time = $time;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notification::send($this->user, new Telegram($this->user_name, $this->telegram_user_id, $this->netping_name, $this->state, $this->date, $this->time));
    }
}
