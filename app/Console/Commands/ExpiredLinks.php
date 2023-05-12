<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Models\MailingText;
use App\Models\Setting;
use App\Models\SettingUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class ExpiredLinks extends Command {

    protected $signature = 'send:expired';

    protected $description = 'Reminder for expired links';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $reminder_nl = MailingText::template('Expired links', 'nl');   // Email text in dutch
        $reminder_en = MailingText::template('Expired links', 'en');   // Email text in english
        $email_id    = Setting::mail_id('I want to receive promotions');
        $links       = Link::expired_links();
        $lang        = 'nl';
        $total       = 0;

        foreach($links as $link) {
            $lang = !empty($link->lang) ? $link->lang : 'nl';
            App::setLocale($lang);

            $email   = ($lang == 'en') ? $reminder_en->id : $reminder_nl->id;
            $subject = ($lang == 'en') ? $reminder_en->name : $reminder_nl->name;
            $data[0] = $link->toArray();
            $content = replace_expire($data, $email);
            self::send($data[0]['email'], $subject, $content, $data[0]['name']." ".$data[0]['lastname'], $data[0]['id'], $email_id);
            $total++;
        }

        $this->line($total . ' emails were sent to remind expired links');
    }

    private function send($email, $subject, $content, $name, $user_id, $setting_id) {
        if(SettingUser::allow_reminders($user_id, $setting_id)) {
            Mail::send('mails.template', ['content' => $content, 'size' => '80%'], function ($mail) use ($email, $subject, $name) {
                $mail->from(env('APP_EMAIL'), config('app.name'));
                $mail->to($email, $name)->subject($subject);
            });
        }
    }

}
