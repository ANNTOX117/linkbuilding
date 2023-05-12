<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\Mailing;
use App\Models\MailingText;
use App\Models\User;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Batches extends Command {

    protected $signature = 'send:batches';
    protected $description = 'We send email batches according to size and interval';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $batches = Batch::actives();
        $counter  = 0;

        if(!empty($batches)) {
            foreach($batches as $batch) {
                
                $template = MailingText::find($batch->email);
                $customer = User::find($batch->customer);
                $params   = [];

                if (User::emailsettings($batch->customer, 1) == 0 && $template->type == 'Promotions') {
                    break;
                }

                if (User::emailsettings($batch->customer, 2) == 0 && $template->type == 'Newsletter') {
                    break;
                }

                if ($template->type == 'Payments') {
                    $order = Order::order_by_user($customer->id);
                    array_push($params, ['order' => $order]);
                }

                $subject = replace_variables($template->name, $customer->id);
                $content = replace_variables($template->description, $customer->id);
                array_push($params, ['content' => $content]);
                
                self::send($customer, $subject, $params);
                self::sent($batch->id);

                $counter++;
            }
        }

        $this->line($counter . ' emails sent');
    }

    private function send($user, $subject, $params) {
        Mail::send('mails.template', $params, function ($mail) use ($user, $subject) {
            $mail->from(env('APP_EMAIL'), config('app.name'));
            $mail->to($user->email, $user->name .' '. $user->lastname)->subject($subject);
        });
    }

    private function sent($batch) {
        Batch::sent($batch);
    }

}
