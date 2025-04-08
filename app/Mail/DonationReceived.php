<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $amount;
    public function __construct($amount)
    {
        //
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->subject('Thank you for your donation!')
            ->view('emails.donation');
    }
}
