<?php

namespace App\Http\Livewire\Admin;

use App\Models\PageEdition;
use App\Models\PagebuilderPage;
use App\Models\PagebuilderTranslation;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Content extends Component {

    use WithFileUploads;
    use WithPagination;

    public $title;
    public $menu;
    public $breadcrumb;
    public $tab;
    public $menus;
    public $menu_type = 'header';
    public $pages;
    public $remove;
    public $item;
    public $edit;
    public $name;
    public $description;
    public $content;
    public $header;
    public $status;
    public $inmenu;
    public $lock;
    public $link;
    public $url;
    public $active_pages;
    public $seo_title;
    public $seo_description;
    public $seo_tags;
    public $paginate;
    public $filter;
    public $search;
    public $column;
    public $order;
    public $photo;
    public $site = 1;

    protected $paginationTheme = 'bootstrap';

    public function mount() {
        Paginator::useBootstrap();

        $this->title      = trans('Page builder');
        $this->menu       = 'content';
        $this->breadcrumb = array(trans('Content') => null);
        $this->tab        = 'pages';
        $this->paginate   = env('APP_PAGES');

        self::setDefault();
    }

    public function render() {
        $this->pages = PagebuilderTranslation::list_by_site($this->site);

        return view('livewire.admin.content')->layout('layouts.panel', ['title' => $this->title, 'menu' => $this->menu, 'breadcrumb' => $this->breadcrumb]);
    }

    public function updated($propertyName) {
        $this->resetValidation($propertyName);
    }

    public function updatedSearch() {
        if($this->tab == 'news') {
            $this->filter['blogs'] = !empty($this->search) ? $this->search : null;
        }
    }

    public function updatedPhoto($property) {
        $this->validate([
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3072'
        ]);

        $this->dispatchBrowserEvent('onFile', ['element' => '.photo_label', 'label' => $this->photo->getClientOriginalName()]);
    }

    public function tab($tab) {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function create() {
        $this->resetErrorBag();
        self::cleanPage();
        $this->dispatchBrowserEvent('onModal', ['action' => 'show', 'modal' => '#create_page']);
    }

    public function edit($id) {
        $this->resetErrorBag();

        $this->item = PagebuilderTranslation::find($id);

        if(!empty($this->item)) {
            $this->edit            = $this->item->id;
            $this->url             = $this->item->route;
            $this->name            = $this->item->title;
            $this->seo_title       = $this->item->pages->seo_title;
            $this->seo_description = $this->item->pages->seo_description;

            $this->dispatchBrowserEvent('onModal', ['action' => 'show', 'modal' => '#edit_page']);
        }
    }

    public function doPage() {
        $this->resetErrorBag();

        $this->validate([
            'name'            => 'required',
            'url'             => 'required',
            'seo_title'       => 'nullable',
            'seo_description' => 'nullable',
        ]);

        if(PagebuilderTranslation::slug_already_exists_by_site($this->site, get_slug($this->url))) {
            $this->addError('url', trans('You already have a page with that URL'));
            $this->dispatchBrowserEvent('onError', ['modal' => '#create_page']);
            return false;
        } else {
            // $site = Site::find($this->site);
            $now  = Carbon::now(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');

            $insert = array('site'            => $this->site,
                            'name'            => clean_html($this->name),
                            'layout'          => 'master',
                            'seo_title'       => clean_db($this->seo_title),
                            'seo_description' => clean_db($this->seo_description),
                            'data'            => null,
                            'created_at'      => $now,
                            'updated_at'      => $now
            );

            $page = PagebuilderPage::create($insert);

            $insert = array('site'            => $this->site,
                            'page_id'         => $page->id,
                            'locale'          => 'en',
                            'title'           => clean_html($this->name),
                            'route'           => prep_slash(get_slug($this->url, 'allow_slash_braces')),
                            'created_at'      => $now,
                            'updated_at'      => $now
            );

            PagebuilderTranslation::create($insert);

            session()->flash('pages_message', trans('Page ":name" has been saved successfully', ['name' => $this->name]));

            self::cleanPage();

            $this->dispatchBrowserEvent('onModal', ['action' => 'hide', 'modal' => '#create_page']);
            $this->dispatchBrowserEvent('onFlash');
        }
    }

    public function doEditPage() {
        $this->resetErrorBag();

        $this->validate([
            'name'            => 'required',
            'url'             => 'required',
            'seo_title'       => 'nullable',
            'seo_description' => 'nullable'
        ]);

        if(PagebuilderTranslation::slug_already_exists_by_site($this->site, get_slug($this->url), $this->edit)) {
            $this->addError('url', trans('You already have a page with that URL'));
            $this->dispatchBrowserEvent('onError', ['modal' => '#edit_page']);
            return false;
        } else {
            $page = PagebuilderTranslation::find($this->edit);
            $now  = Carbon::now(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');

            $update = array('title'           => clean_html($this->name),
                            'route'           => prep_slash(get_slug($this->url, 'allow_slash_braces')),
                            'updated_at'      => $now
            );

            PagebuilderTranslation::update_row($this->edit, $update);

            $update = array('name'            => clean_html($this->name),
                            'seo_title'       => clean_db($this->seo_title),
                            'seo_description' => clean_db($this->seo_description),
                            'updated_at'      => $now
            );

            PagebuilderPage::update_row($page->page_id, $update);

            session()->flash('pages_message', trans('Page ":name" has been updated successfully', ['name' => $this->name]));

            self::cleanPage();

            $this->dispatchBrowserEvent('onModal', ['action' => 'hide', 'modal' => '#edit_page']);
            $this->dispatchBrowserEvent('onFlash');
        }
    }

    public function selectedLink($param) {
        if($param == 'external') {
            $this->dispatchBrowserEvent('onToggle', ['element' => '.container-url', 'show' => true]);
        } else {
            $this->url = '';
            $this->dispatchBrowserEvent('onToggle', ['element' => '.container-url', 'show' => false]);
        }
    }

    public function menu_section($param) {
        $this->menu_type = $param;
        $this->resetPage();

        if($this->tab == 'menu') {
            $this->dispatchBrowserEvent('onOrder', ['reload' => true]);
        }
    }

    public function overviewSort($param, $items) {
        $items    = explode(',', $items);
        $position = 1;

        foreach($items as $i => $item) {
            Menu::position($item, $position);
            $position++;
        }

        if($param == 'menu') {
            session()->flash('menu_message', trans('The new order of the menu has been saved'));
        }

        $this->dispatchBrowserEvent('onFlash');
    }

    public function doMenuOption($option, $value, $name) {
        Menu::update_row($option, array('status' => set_bool($value)));

        if($this->tab == 'menu') {
            session()->flash('menu_message', trans('The status for ":name" has been saved successfully', ['name' => $name]));
        }

        $this->dispatchBrowserEvent('onFlash');
    }

    public function doPageOption($option, $value, $name) {
        PageEdition::update_row($option, array('status' => set_bool($value)));

        if($this->tab == 'pages') {
            session()->flash('pages_message', trans('The status for ":name" has been saved successfully', ['name' => $name]));
        }

        $this->dispatchBrowserEvent('onFlash');
    }

    public function doBlogOption($option, $value, $name) {
        Blog::update_row($option, array('status' => set_bool($value)));

        if($this->tab == 'news') {
            session()->flash('blogs_message', trans('The status for ":name" has been saved successfully', ['name' => $name]));
        }

        $this->dispatchBrowserEvent('onFlash');
    }

    public function doContent($param) {
        $this->content = $param;
        $this->resetValidation('content');
    }

    public function doTags($name, $param) {
        if($this->tab == 'news') {
            $this->$name = $param;
            $this->resetValidation($name);
        }
    }

    public function doOrder($items) {
        if($this->tab == 'menu') {
            $items = json_decode($items, true);

            if(!empty($items)) {
                $position = 1;

                foreach($items as $item) {
                    Menu::position_with_subitems($item['id'], $position);

                    if(array_key_exists('children', $item)) {
                        $subposition = 1;
                        foreach($item['children'] as $subitem) {
                            Menu::position_with_subitems($subitem['id'], $position, $subposition);
                            $subposition++;
                        }
                    }

                    $position++;
                }
            }

            session()->flash('menu_message', trans('The new order of the menu has been saved'));
            $this->dispatchBrowserEvent('onFlash');
        }
    }

    public function doSite($site) {
        session(['site' => $site]);
        $this->site = $site;
    }

    public function pagination($option) {
        $this->paginate = $option;
    }

    public function sort($column, $order) {
        if($this->tab == 'news') {
            $this->column['blogs'] = $column;
            $this->order['blogs']  = $order;
        }
    }

    public function delete($id) {
        $this->remove = $id;

        if($this->tab == 'menu') {
            $this->dispatchBrowserEvent('doConfirm', ['message' => trans('Do you want to delete this item?'), 'confirm' => trans('Yes, delete'), 'cancel' => trans('No')]);
        }

        if($this->tab == 'pages') {
            $this->dispatchBrowserEvent('doConfirm', ['message' => trans('Do you want to delete this page?'), 'confirm' => trans('Yes, delete'), 'cancel' => trans('No')]);
        }

        if($this->tab == 'news') {
            $this->dispatchBrowserEvent('doConfirm', ['message' => trans('Do you want to delete this blog?'), 'confirm' => trans('Yes, delete'), 'cancel' => trans('No')]);
        }
    }

    public function doDelete() {
        if($this->tab == 'menu') {
            $this->item = Menu::find($this->remove);

            Menu::destroy($this->remove);

            if($this->tab == 'menu') {
                $this->dispatchBrowserEvent('onOrder', ['reload' => true]);
            }

            session()->flash('menu_message', trans('":name" has been removed successfully', ['name' => $this->item->title]));
        }

        if($this->tab == 'pages') {
            $this->item = PagebuilderTranslation::find($this->remove);

            PagebuilderPage::destroy($this->item->page_id);
            PagebuilderTranslation::destroy($this->remove);

            session()->flash('pages_message', trans('":name" has been removed successfully', ['name' => $this->item->title]));
        }

        if($this->tab == 'news') {
            $this->item = Blog::find($this->remove);

            Blog::destroy($this->remove);

            session()->flash('blogs_message', trans('":name" has been removed successfully', ['name' => $this->item->title]));
        }

        self::cleanDestroyed();

        $this->dispatchBrowserEvent('onFlash');
    }

    private function setDefault() {
        $this->column['blogs'] = 'created_at';
        $this->order['blogs']  = 'desc';
        $this->filter['blogs'] = null;

        if(empty(session('site'))) {
            session(['site' => $this->site]);
        }

        if(!empty(session('site'))) {
            $this->site = session('site');
        }
    }

    private function cleanBlog() {
        $this->name            = '';
        $this->description     = '';
        $this->content         = '';
        $this->seo_title       = '';
        $this->seo_description = '';
        $this->seo_tags        = '';
        $this->status          = '';
    }

    private function cleanMenu() {
        $this->name = '';
        $this->link = '';
        $this->url  = '';
        $this->lock = '';
    }

    private function cleanPage() {
        $this->item            = '';
        $this->url             = '';
        $this->name            = '';
        $this->description     = '';
        $this->content         = '';
        $this->header          = '';
        $this->status          = '';
        $this->inmenu          = '';
        $this->lock            = '';
        $this->seo_title       = '';
        $this->seo_description = '';
    }

    private function cleanDestroyed() {
        $this->item = '';
    }

}
