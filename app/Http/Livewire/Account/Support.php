<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Support extends Component {

    public $title;
    public $menu;

    public $email;
    public $full_name;
    public $message;

    public function mount() {
        $this->title = trans('Support');
        $this->menu  = 'Support';
        $user = Auth::user();
        $this->email = $user->email;
        $this->full_name = $user->name. " " . $user->lastname;
    }

    public function sendsupport(){
        $this->validate([
            'message' => 'required|min:10'
        ]);

        $name = $this->full_name;
        $email = $this->email;
        $subject = trans('Support');

        $content = trans('Name : ') . " " . $this->full_name . "</br>" . trans('Email : ') . " " . $this->email . "</br>" . trans('Message : ') . " " . $this->message;

        Mail::send('mails.template', ['content' => $content ], function ($mail) use ($email, $name, $subject) {
                $mail->from($email, $name);
                $mail->to(env('APP_EMAIL'), env('APP_NAME'))->subject($subject);
            });

        $this->message = '';
        session()->flash('successsupport', __('Your email has been sent successfully.'));
        $this->resetErrorBag();

    }

    public function render() {
        return view('livewire.account.support')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
    }

}
