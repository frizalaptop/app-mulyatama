<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendReminderEmailJob implements ShouldQueue
{
    use Queueable;
    protected $data; // data yang akan digunakan dalam email
    public $tries = 5; // jumlah percobaan maksimal

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->onQueue('email');
    }

    /**
     * Eksekusi queue pengiriman email dalam dengan waktu delay yang ditentukan.
     * 
     */
    public function handle(): void
    {
        Mail::to($this->data->user_email)
            ->send(new \App\Mail\ReminderBillboardMail($this->data));
    }
}
 