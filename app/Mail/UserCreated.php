<?php

namespace App\Mail;

use App\Setting;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //default logo url
        $logoUrl = asset('/images/logo.png');

        $fromName = env('MAIL_FROM_NAME');
        $fromEmail = env('MAIL_FROM_ADDRESS');

        return $this->subject('Your account has been created')
            ->from($fromEmail, $fromName)
            ->view('emails.users.created', [
                'loginUrl' => env('APP_URL').'/login',
                'logoUrl' => $logoUrl
            ]);
    }
}
