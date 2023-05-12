<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use \App\Models\MailingText;

class ResetPasswordNotification extends ResetPassword {

    public function toMail($notifiable) {
        $name = $notifiable->name;
        $subject = trans('Reset password');
        $reset = 'reset';
        $content = '';
        
        $link = route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]);
        $template = \App\Models\MailingText::template('Forget password', App::getLocale());

        if(!empty($template)) {
            $subject = replace_variables($template->name, $notifiable->id);
            $content = replace_variables($template->description, $notifiable->id);
            $name    = $notifiable->name . ' ' . $notifiable->lastname;
            $email   = $notifiable->email;
        }
        
        return (new MailMessage)
            ->view('mails.template', ['name' => $name, 'link' => $link, 'link_text' => 'Reset password', 'reset' => $reset])
            //->subject(trans('Reset password'));
            ->subject($subject);
    }

}
