<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class Telegram extends Notification
{
    public $user_name;
    public $telegram_user_id;
    public $netping_name;
    public $state;
    public $date;
    public $time;

    /**
     * Create a new notification instance.
     */
    public function __construct($user_name, $telegram_user_id, $netping_name, $state, $date, $time)
    {
        $this->user_name = $user_name;
        $this->telegram_user_id = $telegram_user_id;
        $this->netping_name = $netping_name;
        $this->state = $state;
        $this->date = $date;
        $this->time = $time;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * @throws \JsonException
     */
    public function toTelegram()
    {
        $url = url('/netping');

        return TelegramMessage::create()
            // Markdown supported.
            ->content("*Точка " . $this->netping_name . ": " . mb_strtolower($this->state) . "*\n")
            ->line("\n")
            ->line("*Дата:*" . " " . $this->date)
            ->line("*Время:*" . " " . $this->time)
            ->line("*Пользователь:*" . " " . $this->user_name)
            ->line("*Точка:*" . " " . $this->netping_name)
            ->line("*Новый статус:*" . " " . $this->state)

            // (Optional) Inline Buttons
            ->button('Посмотреть статус точек', $url);
    }
}
