<?php

namespace App\Http\Livewire\Pages;

use App\Models\Link;
use App\Models\Site;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Contact extends Component {

    public $title;
    public $section;
    public $site;
    public $name;
    public $email;
    public $page;
    public $subject;
    public $message;
    public $daughters;
    public $contact;

    protected $domain;
    protected $category;
    protected $website;

    protected $rules = [
        'name'    => 'required',
        'email'   => 'required|email',
        'page'    => 'nullable',
        'subject' => 'required',
        'message' => 'required'
    ];

    public function __construct() {
        // if(empty(session('website'))) {
        //     abort(404);
        // }

        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        $this->website  = \App\Models\Site::get_info($this->domain);
        // $this->website  = \App\Models\Site::get_info('bullsandhornsmedia.com/');

        if(!empty($this->category)) {
            $this->domain = $this->category . '.' . $this->domain;
        }
        if(empty($this->website)) {
            abort(404);
        }
    }

    public function mount() {
        // $this->site      = session('website');
        $this->site      = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        $this->title     = trans('Contact');
        $this->section   = 'contact';
        $this->daughters = Link::daughters_for_website($this->site->id);
    }

    public function render() {
        return view('livewire.pages.contact')->layout('layouts.website', ['title' => $this->title, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

    public function submit() {
        $this->validate();
        self::send();

        $this->dispatchBrowserEvent('onSent');
    }

    private function send() {
        $owner_name  = $this->site->name;
        $owner_email = $this->site->contact;
        $name        = $this->name;
        $email       = $this->email;
        $page        = $this->page;
        $subject     = $this->subject;
        $content     = trans('Hello :owner, you have received the following message from :name (:email) for your site :page', ['owner' => $owner_name, 'name' => $name, 'email' => $email, 'page' => $page]) .": <br><br><em>". $this->message ."</em>";

        if(!empty($this->site->contact)) {
            Mail::send('mails.template', ['content' => $content], function ($mail) use ($owner_email, $owner_name, $subject) {
                $mail->to($owner_email, $owner_name)->subject($subject);
            });
        }

        self::resetContact();
    }

    private function resetContact() {
        $this->name    = '';
        $this->email   = '';
        $this->page    = '';
        $this->subject = '';
        $this->message = '';
    }

}
