<?php

namespace App\Http\Livewire\Admin;

use App\Models\MetaCategories;
use App\Models\SiteCategoryMain;
use App\Models\Category;
use App\Models\Site;
use Livewire\Component;

class Metas extends Component {

    public $header;
    public $section = 'metas';
    public $column  = 'category_id';
    public $sort    = 'asc';
    public $confirm;
    public $custom_error;
    public $languages;
    public $sites;
    public $categories;
    public $meta_id;
    public $url;
    public $language;
    public $name;
    public $footer;
    public $meta_title;
    public $meta_description;
    public $meta_keyword;
    public $site;
    public $site_id;
    public $category_id;
    public $pagination;
    public $search = '';
    public $title;

    protected $rules = [
        'category_id'      => 'required',
        'site_id'          => 'required',
        'header'           => 'required',
        'url'              => 'required',
        'footer'           => 'required',
        'meta_title'       => 'nullable',
        'meta_description' => 'nullable',
        'meta_keyword'     => 'nullable',
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedSiteId($site) {
        if(!is_null($site)) {
            $this->categories = Category::by_site($site);
            $this->dispatchBrowserEvent('resetCategories', ['options' => $this->categories, 'selected_option' => $this->category_id]);
        }
    }

    public function mount() {

        $this->title      = trans('Categories options');
        $this->sites      = Site::all_websites();
        $this->categories = [];
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $pages = MetaCategories::with_pagination($this->column, $this->sort, $this->pagination, $this->search);
        return view('livewire.admin.metas', compact('pages'))->layout('layouts.panel');
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
        $meta = MetaCategories::find($id);

        if(!empty($meta)) {
            $this->meta_id          = $meta->id;
            $this->url              = $meta->url;
            $this->header           = $meta->header;
            $this->footer           = $meta->footer;
            $this->meta_title       = $meta->meta_title;
            $this->meta_description = $meta->meta_description;
            $this->meta_keyword     = $meta->meta_keyword;
            $this->category_id      = $meta->category_id;
            $this->site_id          = $meta->site_id;
            $this->sites            = Site::all_websites();
            $this->categories       = $this->categories = Category::by_site($this->site_id);
            $this->resetErrorBag();
            $this->dispatchBrowserEvent('resetOptions', ['options' => $this->sites, 'selected_option' => $this->site_id]);
            // $this->dispatchBrowserEvent('resetCategories', ['options' => $this->categories, 'selected_option' => $this->category_id]);
            $this->dispatchBrowserEvent('showEditPage', ['editor_1' => $this->header, 'editor' => $this->footer]);
        }
    }

    public function addPage() {
        MetaCategories::create([
            'url'              => mysql_null(prep_slash($this->url)),
            'header'           => mysql_null($this->header),
            'footer'           => mysql_null($this->footer),
            'category_id'      => mysql_null($this->category_id),
            'site_id'          => mysql_null($this->site_id),
            'meta_title'       => mysql_null($this->meta_title),
            'meta_description' => mysql_null($this->meta_description),
            'meta_keyword'     => mysql_null($this->meta_keyword)
        ]);


        self::resetInputFields();

        session()->flash('successPage', trans('Options succesfully created'));
        $this->dispatchBrowserEvent('hideAddPage');
    }

    public function editPage() {

        $data = $this->validate();
        $page = MetaCategories::find($this->meta_id);

        if(!empty($page)) {
            $page->header           = mysql_null($data['header']);
            $page->footer           = mysql_null($data['footer']);
            $page->meta_title       = mysql_null($data['meta_title']);
            $page->meta_description = mysql_null($data['meta_description']);
            $page->meta_keyword     = mysql_null($data['meta_keyword']);
            $page->category_id      = mysql_null($data['category_id']);
            $page->site_id          = mysql_null($data['site_id']);
            $page->save();
        }

        self::resetInputFields();

        session()->flash('successPage', trans('Options succesfully edited'));
        $this->dispatchBrowserEvent('hideEditPage');
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        MetaCategories::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->category_id      = '';
        $this->site_id      = '';
        $this->footer           = '';
        $this->header           = '';
        $this->meta_title       = '';
        $this->meta_description = '';
        $this->meta_keyword     = '';
    }

}
