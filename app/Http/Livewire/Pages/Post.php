<?php

namespace App\Http\Livewire\Pages;

use App\Models\Article;
use App\Models\Site;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Post extends Component {

    public $title;
    public $section;
    public $site;
    public $post;
    public $id;

    protected $domain;
    protected $category;
    protected $website;

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

    public function mount($url) {
        $url  = clean_html($url);

        // $this->site = session('website');
        $this->site = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        if(Article::doesnt_exist($this->site->id, $url)) {
            abort(404);
        }

        $this->section = 'blog';
        $this->post    = Article::get_post($this->site->id, $url);
        $this->id = $this->post->category;

        if(empty($this->post)) {
            abort(404);
        }

        $this->title = $this->post->title;
    }

    public function render() {
        return view('livewire.pages.post')->layout('layouts.website', ['title' => $this->title, 'id_category' => $this->id, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

}
