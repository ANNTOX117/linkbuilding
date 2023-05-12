<?php

namespace App\Http\Livewire\Admin;

use App\Models\Language;
use App\Models\Page;
use App\Models\PageSite;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pages extends Component {

    public $title;
    public $section = 'pages';
    public $column  = 'title';
    public $sort    = 'asc';
    public $confirm;
    public $custom_error;
    public $languages;
    public $sites;
    public $page_id;
    public $url;
    public $language;
    public $name;
    public $content_top;
    public $content_buttom;
    public $meta_title;
    public $meta_description;
    public $meta_keyword;
    public $site;
    public $pagination;
    public $search = '';
    public $all_cities;
    public $noindex_follow;

    protected $rules = [
        'url'              => 'required',
        'language'         => 'required',
        'name'             => 'required',
        'content_top'      => 'required',
        'content_buttom'   => 'required',
        'meta_title'       => 'nullable',
        'meta_description' => 'nullable',
        'meta_keyword'     => 'nullable',
        'site'             => 'nullable',
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('pages', 'read')) {
            abort(404);
        }

        $this->title      = trans('Pages');
        $this->languages  = Language::all();
        $this->sites      = Site::all_websites();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $pages = Page::with_pagination($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.pages', compact('pages'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
        $this->column = $column;
    }

    public function modalAddPage() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddPage');
    }

    public function modalEditPage($id) {
        $page = Page::find($id);

        if(!empty($page)) {
            $this->page_id              = $page->id;
            $this->url                  = $page->url;
            $this->language             = $page->language;
            $this->name                 = $page->title;
            $this->content_top          = $page->content_top;
            $this->content_buttom       = $page->content_buttom;
            $this->meta_title           = $page->meta_title;
            $this->meta_description     = $page->meta_description;
            $this->meta_keyword         = $page->meta_keyword;
            $this->all_cities           = $page->all_cities;
            $this->noindex_follow       = $page->noindex_follow;
            $this->site                 = PageSite::list($this->page_id);

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('resetOptions', ['options' => PageSite::list_for_select($this->page_id)]);
            $this->dispatchBrowserEvent('showEditPage', ['content_top' => $this->content_top,'content_buttom' => $this->content_buttom]);

        }
    }

    public function addPage() {
        if(count($this->site) > 0) {
            if(in_array('0', $this->site)) {
                $this->site = null;
            }
        }

        $data = $this->validate();

        if(Page::already_exists($data['url'])) {
            $this->custom_error = trans('This URL already exists on the database');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }
        $page = Page::create([
            'url'                   => mysql_null(prep_slash($data['url'])),
            'language'              => mysql_null($data['language']),
            'title'                 => mysql_null($data['name']),
            'content_top'           => $data['content_top'],
            'content_buttom'        => $data['content_buttom'],
            'meta_title'            => mysql_null($data['meta_title']),
            'meta_description'      => mysql_null($data['meta_description']),
            'meta_keyword'          => mysql_null($data['meta_keyword']),
            'all_cities'            => $this->all_cities,
            'noindex_follow'        => $this->noindex_follow,
        ]);

        if(empty($this->site)) {
           PageSite::cleanup($page->id);
        } else {
            $sites = array();

            foreach($this->site as $site) {
                $sites[] = array('page' => $page->id, 'site' => $site);
            }

            if(count($sites) > 0) {
                DB::table('pages_sites')->insert($sites);
            }
        }

        self::resetInputFields();

        session()->flash('successPage', trans('Page succesfully created'));
        $this->dispatchBrowserEvent('hideAddPage');
    }

    public function editPage() {
        // dd($this->site);
        // if(count($this->site) > 0) {
        //     if(in_array('0', $this->site)) {
        //         $this->site = null;
        //     }
        // }

        $data = $this->validate();
        $page = Page::find($this->page_id);

        if(!empty($page)) {
            $page->url                  = mysql_null(prep_slash($data['url']));
            $page->language             = mysql_null($data['language']);
            $page->title                = mysql_null($data['name']);
            $page->content_top          = $data['content_top'];
            $page->content_buttom       = $data['content_buttom'];
            $page->meta_title           = mysql_null($data['meta_title']);
            $page->meta_description     = mysql_null($data['meta_description']);
            $page->meta_keyword         = mysql_null($data['meta_keyword']);
            $page->all_cities           = $this->all_cities;
            $page->noindex_follow       = $this->noindex_follow;
            $page->save();

            if(empty($this->site)) {
                PageSite::cleanup($this->page_id);
            } else {
                $sites = array();

                foreach($this->site as $site) {
                    $sites[] = array('page' => $this->page_id, 'site' => $site);
                }

                if(count($sites) > 0) {
                    PageSite::cleanup($this->page_id);
                    DB::table('pages_sites')->insert($sites);
                }
            }
        }

        self::resetInputFields();

        session()->flash('successPage', trans('Page succesfully edited'));
        $this->dispatchBrowserEvent('hideEditPage');
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Page::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->url                  = '';
        $this->language             = '';
        $this->name                 = '';
        $this->content_top          = '';
        $this->content_buttom       = '';
        $this->meta_title           = '';
        $this->meta_description     = '';
        $this->meta_keyword         = '';
        $this->noindex_follow       = 0;
        $this->all_cities           = 0;
        $this->site                 = '';
    }

}
