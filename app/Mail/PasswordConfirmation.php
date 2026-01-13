<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $email;
    public $name;
    
    /**
     * Create a new message instance.
     */
    public function __construct(string $resetUrl, array $data = [])
    {
        $this->resetUrl = $resetUrl;
        $this->email = $data['email'] ?? null;
        $this->name = $data['name'] ?? null;  // Added name initialization
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Reset Password Confirmation')
                    ->markdown('emails.password-confirmation');
    }
}
