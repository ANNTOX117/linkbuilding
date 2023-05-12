<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends VerifyEmail {

    public function toMail($notifiable) {
        $name = $notifiable->name;
        $link = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->view('mails.verification', ['name' => $name, 'link' => $link, 'link_text' => 'Verify email address'])
            ->subject(trans('view.Verify email address'));
    }

}
