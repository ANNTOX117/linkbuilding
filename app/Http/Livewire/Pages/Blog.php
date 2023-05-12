<?php

namespace App\Http\Livewire\Pages;

use App\Models\Article;
use App\Models\MetaCategories;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component {

    use WithPagination;

    public $title;
    public $section;
    public $site;
    public $daughter;
    public $id;

    protected $domain;
    protected $category;
    protected $website;

    protected $paginationTheme = 'bootstrap';
    // protected $queryString = [

    //     'id' => ['except' => ''],

    //     'page' => ['except' => 1],

    // ];

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
        // $this->site    = session('website');
        $this->site    = $this->website;

        App::setLocale($this->site->languages->name ?? 'nl');

        $this->title   = trans('Blog');
        $this->section = 'blog';
        $this->id = str_replace('C-', '', $this->id);
    }

    public function render() {
        $meta_info = null;
        if($this->id != ''){
            $meta_info = MetaCategories::where('category_id', $this->id)->where('site_id', $this->site->id)->first();
        }
        $blogCategories = Article::getCategories($this->site->id, $this->id);
        $posts = (empty($this->category)) ? Article::get_posts($this->site->id, $this->id) : Article::get_posts_by_subdomain($this->site->id, $this->category);
        $is_daughter = (empty($this->category)) ? false : true;
        $this->daughter = $is_daughter;
        $id_filter = $this->id;

        return view('livewire.pages.blog', compact('posts', 'is_daughter', 'blogCategories', 'id_filter', 'meta_info'))->layout('layouts.website', ['meta_info' => $meta_info, 'title' => $this->title, 'id_category' => $this->id, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'daughter' => $this->daughter, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
    }

}
