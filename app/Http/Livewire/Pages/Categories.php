<?php

namespace App\Http\Livewire\Pages;

use App\Models\Link;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Categories extends Component {

    public $title;
    public $section;
    public $site;
    public $daughters;

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

    public function mount($category) {
        // $this->site      = session('website');
        $this->site      = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        $this->title     = trans('Privacy');
        $this->section   = 'daughters';
        $this->daughters = Link::daughters_for_category($this->site->id, $category);

        if(!empty($this->daughters)) {
            foreach($this->daughters as $daughter) {
                $daughter->links = Link::daughters_list_for_category($this->site->id, $category);
            }
        }
    }

    public function render() {
        return view('livewire.pages.categories')->layout('layouts.website', ['title' => $this->title, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

}
