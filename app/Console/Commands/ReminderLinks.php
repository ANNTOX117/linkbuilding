<?php

namespace App\Console\Commands;

use App\Models\MailingText;
use App\Models\Setting;
use App\Models\SettingUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Models\Link;
use App\Models\User;

class ReminderLinks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We send a reminder of the links about to expire';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle(){
        // Get emails according to the language
        $months_2_nl  = MailingText::reminder_info('2 months', 'nl'); // 2 months in dutch
        $months_2_en  = MailingText::reminder_info('2 months', 'en'); // 2 months in english
        $days_15_nl   = MailingText::reminder_info('15 days', 'nl');  // 15 days in dutch
        $days_15_en   = MailingText::reminder_info('15 days', 'en');  // 15 days in english
        $days_2_nl    = MailingText::reminder_info('2 days', 'nl');   // 2 days in dutch
        $days_2_en    = MailingText::reminder_info('2 days', 'en');   // 2 days in english
        $email_id     = Setting::mail_id('I want to receive promotions');

        $send_mail_users = Link::my_liks_expired_mail()->groupBy('email');

        if (!empty($send_mail_users)) {
            foreach($send_mail_users as $item => $value){

                $array_60_days = [];
                $array_15_days = [];
                $array_2_days  = [];
                $text = '';
                $lang = 'nl';

                foreach ($value as $key) {
                    $lang = (!empty($key['lang'])) ? $key['lang'] : 'nl';

                    switch ($key['days']) {
                        case 60:
                                array_push($array_60_days, $key);
                            break;
                        case 15:
                                array_push($array_15_days, $key);
                            break;
                        case 2:
                                array_push($array_2_days, $key);
                            break;
                    }
                }

                App::setLocale($lang);

                if (!empty($array_60_days)) {
                    $what  = ($lang == 'en') ? $months_2_en->id : $months_2_nl->id;
                    $title = ($lang == 'en') ? $months_2_en->name : $months_2_nl->name;
                    $text  = replace_expire($array_60_days, $what);
                    self::send($item, $title, $text, $array_60_days[0]['name']." ".$array_60_days[0]['lastname'], $array_60_days[0]['id'], $email_id);
                }
                if (!empty($array_15_days)) {
                    $what  = ($lang == 'en') ? $days_15_en->id : $days_15_nl->id;
                    $title = ($lang == 'en') ? $days_15_en->name : $days_15_nl->name;
                    $text  = replace_expire($array_15_days, $what);
                    self::send($item, $title, $text, $array_15_days[0]['name']." ".$array_15_days[0]['lastname'], $array_15_days[0]['id'], $email_id);
                }
                if (!empty($array_2_days)) {
                    $what  = ($lang == 'en') ? $days_2_en->id : $days_2_nl->id;
                    $title = ($lang == 'en') ? $days_2_en->name : $days_2_nl->name;
                    $text  = replace_expire($array_2_days, $what);
                    self::send($item, $title, $text, $array_2_days[0]['name']." ".$array_2_days[0]['lastname'], $array_2_days[0]['id'], $email_id);
                }
            }
        }
    }

    private function send($user, $subject, $content, $full_name, $user_id, $setting_id) {
        if(SettingUser::allow_reminders($user_id, $setting_id)) {
            Mail::send('mails.template', ['content' => $content, 'size' => '80%'], function ($mail) use ($user, $subject, $full_name) {
                $mail->from(env('APP_EMAIL'), config('app.name'));
                $mail->to($user, $full_name)->subject($subject);
            });
        }
    }
}
