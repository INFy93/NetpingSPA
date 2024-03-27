<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueSenderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $mail_address;
    public $user;
    public $netping;
    public $state;
    public $time;
    public $date;
    public function __construct($mail_address, $user, $netping, $state, $time, $date)
    {
        $this->mail_address = $mail_address;
        $this->user = $user;
        $this->netping = $netping;
        $this->state = $state;
        $this->time = $time;
        $this->date = $date;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
