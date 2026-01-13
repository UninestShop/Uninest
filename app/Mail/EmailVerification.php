<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generate the URL properly with token as a parameter
        $verificationUrl = url('/email/verify/' . $this->data['token']);
        
        return $this->subject('Verify Your Email Address')
            ->view('emails.verification')
            ->with([
                'name' => $this->data['name'],
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
