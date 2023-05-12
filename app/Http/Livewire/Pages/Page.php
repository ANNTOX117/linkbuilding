<?php

namespace App\Http\Livewire\Pages;

use App\Models\Language;
use App\Models\PageSite;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Page extends Component {

    public $title;
    public $section;
    public $site;
    public $locale;
    public $lang;
    public $slug;
    public $page;
    public $access = false;
    public $text;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

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

    public function mount($slug) {
        $this->slug   = clean_html($slug);
        // $this->site   = session('website');
        $this->site   = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        $this->locale = App::getLocale();
        $this->lang   = Language::by_name($this->locale);

        if(\App\Models\Page::slug_exists($this->slug, $this->lang->id)) {
            $this->page = \App\Models\Page::info($this->slug, $this->lang->id);
            if(PageSite::is_restricted_for_some_users($this->page->id)) {
                if(\App\Models\PageSite::the_site_is_allowed($this->page->id, $this->site->id)) {
                    $this->access = true;
                }
            } else {
                $this->access = true;
            }
        } else {
            abort(404);
        }

        if($this->access) {
            $this->title            = $this->page->title;
            $this->section          = 'pages';
            $this->text             = $this->page->content;
            $this->meta_title       = $this->page->meta_title;
            $this->meta_description = $this->page->meta_description;
            $this->meta_keywords    = $this->page->meta_keyword;
        } else {
            abort(404);
        }
    }

    public function render() {
        return view('livewire.pages.page')->layout('layouts.website', ['title' => $this->title, 'meta_title' => $this->meta_title, 'meta_description' => $this->meta_description, 'meta_keywords' => $this->meta_keywords, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

}
