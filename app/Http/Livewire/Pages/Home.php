<?php

namespace App\Http\Livewire\Pages;

use App\Models\Link;
use App\Models\Article;
use App\Models\AuthoritySite;
use App\Models\Site;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use App\Http\Livewire\Traits\CustomWithPagination;

class Home extends Component {

    use CustomWithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['paginatorPageChanged' => 'paginatorPageChanged'];

    public $pageName = 'page';

    public $title;
    public $description;
    public $section;
    public $site;
    public $categories;
    public $slides;
    protected $daughters;

    protected $domain;
    protected $category;
    protected $website;

    public $daughter;

    public function __construct() {
        // if(empty(session('website'))) {
        //     abort(404);
        // }
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        $this->website  = \App\Models\Site::get_info($this->domain);
        $this->website  = \App\Models\Site::get_info('hotpaginas.nl');

        if(!empty($this->category)) {
            $this->domain = $this->category . '.' . $this->domain;
        }
        if(empty($this->website)) {
            abort(404);
        }
    }

    public function paginatorPageChanged(){
        $this->slides     = json_decode($this->site->slider_text);
        $this->dispatchBrowserEvent('paginatorPageChanged');
    }

    public function mount() {
        // if(!empty(session('category'))) {
        if(!empty($this->category)) {
            // $this->site      = session('website');
            $this->site      = $this->website;

            App::setLocale($this->site->languages->name ?? 'nl');

            // $this->title     = ucfirst(session('category'));
            $this->title     = ucfirst($this->category);
            $this->section   = 'daughters';
            // $this->daughters = AuthoritySite::all_daughters_for_category($this->site->id, session('category'));
            $this->daughters = array();
            $alphachar = array_merge(range('a', 'z'));
            foreach($alphachar as $alp){
                $this->daughters[] = (object) ['name' => $alp, 'links' => null];
            }

            if(!empty($this->daughters)) {
                foreach($this->daughters as $dau) {
                    $dau->links = AuthoritySite::all_daughters_list_for_letter($this->site->id, $dau->name, session('category'));
                }
            }
        } else {
            // $this->site       = session('website');
            $this->site       = $this->website;

            App::setLocale($this->site->languages->name ?? 'nl');

            // $this->title      = trans('Home page overview');
            $this->title      = $this->site->meta_title;
            $this->description= $this->site->meta_description;
            $this->section    = 'home';
            $this->categories = Link::categories_for_website($this->site->id);
            $this->slides     = json_decode($this->site->slider_text);
        
           
            if(!empty($this->categories)) {
                foreach($this->categories as $category) {
                    $category->links = Link::categories_list_for_website($this->site->id, $category->id);
                }
            }
        }
    }

    public function render() {
        if($this->site->type == 'Blog page'){
            $blogCategories = Article::getCategories($this->site->id);
            // $posts = (empty(session('category'))) ? Article::get_posts($this->site->id) : Article::get_posts_by_subdomain($this->site->id, session('category'));
            // $is_daughter = (empty(session('category'))) ? false : true;
            $posts = (empty($this->category)) ? Article::get_posts($this->site->id) : Article::get_posts_by_subdomain($this->site->id, $this->category);
            $is_daughter = (empty($this->category)) ? false : true;
            $this->daughter = $is_daughter;
            try {
                $site = Site::findOrFail($this->site->id);
            } catch (\Throwable $th) {
                $site->headerText = "Lorem Ipsum";
            }
            

            return view('livewire.pages.blog', compact('posts', 'is_daughter', 'blogCategories','site'))->layout('layouts.website', ['title' => $this->title, 'meta_ title' => $this->title, 'meta_description' => $this->description, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'daughter' => $this->daughter, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
        } else {
            // if(!empty(session('category'))) {
            if(!empty($this->category)) {
                $paginator_id = [
                    'name' => 0,
                    'total' => 0,
                ];
                $daughters_paginator = array();
                $alphachar = array_merge(range('a', 'z'));
                foreach($alphachar as $alp){
                    $daughters_paginator[] = (object) ['name' => $alp, 'links' => null];
                }
        
                if(!empty($daughters_paginator)) {
                    foreach($daughters_paginator as $dau) {
                        // $dau->links = AuthoritySite::all_daughters_list_for_letter($this->site->id, $dau->name, session('category'))->paginate(3, ['*'], $dau->name);
                        $dau->links = AuthoritySite::all_daughters_list_for_letter($this->site->id, $dau->name, $this->category)->paginate(3, ['*'], $dau->name);
                        if($dau->links->total() > $paginator_id['total']){
                            $paginator_id['total'] = $dau->links->total();
                            $paginator_id['name'] = $dau->name;
                        }
                    }
                }
                return view('livewire.pages.categories', ['daughters_paginator' => $daughters_paginator, 'paginator_id' => $paginator_id])->layout('layouts.website', ['title' => $this->title, 'meta_ title' => $this->title, 'meta_description' => $this->description, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
            } else {
                $categories_paginator = [];
                $paginator_id = [
                    'id' => 0,
                    'total' => 0,
                ];
    
                $categories_paginator = Link::categories_for_website($this->site->id);
                $max_links_per_page = 80;
                $count_paginator = count($categories_paginator);
                $element_per_page = round($max_links_per_page / ($count_paginator == 0 ? 1 : $count_paginator), 0, PHP_ROUND_HALF_DOWN);
    
                if(!empty($categories_paginator)) {
                    foreach($categories_paginator as $category) {
                        $category->links = Link::categories_list_for_website($this->site->id, $category->id)->paginate($element_per_page, ['*'], 'page');
                        if($category->links->total() > $paginator_id['total']){
                            $paginator_id['total'] = $category->links->total();
                            $paginator_id['id'] = $category->id;
                        }
                    }
                }
                return view('livewire.pages.home', ['categories_paginator' => $categories_paginator, 'paginator_id' => $paginator_id])->layout('layouts.website', ['title' => $this->title, 'section' => $this->section, 'site' => $this->site, 'page' => $this->site->name, 'header' => $this->site->header, 'footer' => $this->site->footer, 'footer2' => $this->site->footer2, 'footer3' => $this->site->footer3, 'footer4' => $this->site->footer4, 'headerText' => $this->site->headerText, 'footerText' => $this->site->footerText]);
            }
        }
    }
}
