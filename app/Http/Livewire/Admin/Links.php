<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Language;
use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Links extends Component {

    use WithPagination;

    public $title;
    public $section = 'links';
    public $column_links = 'authority_sites.url';
    public $column_requests = 'authority_sites.url';
    public $sort    = 'asc';
    public $confirm;
    public $confirmApprove;
    public $tab     = 'links';
    public $table;
    public $clients;
    public $sites;
    public $languages;
    public $categories;
    public $link;
    public $client;
    public $site;
    public $authority_site;
    public $url;
    public $anchor;
    public $description;
    public $language;
    public $category;
    public $visible_at;
    public $ends_at;
    public $follow = 'follow';
    public $blank;
    public $active;
    public $edit = false;

    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'client'      => 'required|numeric',
        'site'        => 'required|numeric',
        'url'         => 'required|url|max:255',
        'anchor'      => 'required|max:255',
        'description' => 'required|max:250',
        'language'    => 'required|numeric',
        'category'    => 'required|numeric',
        'visible_at'  => 'required|date',
        'ends_at'     => 'required|date',
        'follow'      => 'nullable',
        'blank'       => 'nullable',
        'active'      => 'nullable'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedLanguage($language) {
        if(!is_null($language)) {
            $this->language   = $language;
            $this->categories = Category::by_language($language);
        }
    }

    public function updatedSite($site) {
        if(!is_null($site)) {
            $this->site   = $site;
            $this->authority_site = AuthoritySite::find($site);
        }
    }

    public function updatedVisibleAt($date) {
        $start_at = str_replace('/', '-', $date);
        $ends_at  = str_replace('/', '-', $this->ends_at);

        if(!empty($start_at) and !empty($ends_at) and ($start_at > $ends_at)) {
            $this->addError('visible_at', trans('The start date must be less than the end date'));
            return false;
        }
    }

    public function updatedEndsAt($date) {
        $start_at = str_replace('/', '-', $this->visible_at);
        $ends_at  = str_replace('/', '-', $date);

        if(!empty($start_at) and !empty($ends_at) and ($start_at > $ends_at)) {
            $this->addError('visible_at', trans('The start date must be less than the end date'));
            return false;
        }
    }

    public function mount() {
        if(!permission('links', 'read')) {
            abort(404);
        }

        $this->title      = trans('Links');
        $this->clients    = User::get_customers();
        $this->sites      = AuthoritySite::all_items();
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $links    = Link::with_filter($this->column_links, $this->sort, $this->pagination, $this->search);
        $requests = Link::pendings_with_filter($this->column_requests, $this->sort, $this->pagination, $this->search);

        if(!empty($this->language)) {
            $this->categories = Category::by_language($this->language);
        }

        return view('livewire.admin.links', compact('links', 'requests'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        $this->search     = '';
        $this->pagination = env('APP_PAGINATE');

        if($this->tab == 'links') {
            $this->column_links = 'authority_sites.url';
        }

        if($this->tab == 'requests') {
            $this->column_requests = 'authority_sites.url';
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'links') {
            $this->column_links = $column;
        }

        if($table == 'requests') {
            $this->column_requests = $column;
        }
    }

    public function modalAddLink() {
        $this->edit = false;
        self::resetLinksInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddLink');
    }

    public function modalEditLink($id) {
        $link = Link::find($id);
        $this->authority_site = AuthoritySite::find($link->authority_site);

        if($this->tab == 'requests') {
            $this->edit = true;
        }

        if(!empty($link)) {
            $this->link        = $link->id;
            $this->url         = $link->url;
            $this->anchor      = $link->anchor;
            $this->follow      = get_follow($link->follow);
            $this->blank       = (get_bool($link->blank)) ? '_blank' : '_self';
            $this->description = $link->alt;
            $this->active      = $link->active;
            $this->site        = $link->authority_site;
            $this->language    = $link->language;
            $this->category    = $link->category;
            $this->ends_at     = $link->ends_at;
            $this->visible_at  = $link->visible_at;
            $this->client      = $link->client;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('editDates', ['start' => datepicker_date($link->visible_at), 'end' => datepicker_date($link->ends_at)]);
            $this->dispatchBrowserEvent('showEditLink');
        }
    }

    public function addLink() {
        $data = $this->validate([
            'client'      => 'required|numeric',
            'site'        => 'required|numeric',
            'url'         => 'required|url|max:255',
            'anchor'      => 'required|max:255',
            'description' => 'required|max:250',
            'language'    => 'required|numeric',
            'category'    => 'required|numeric',
            'visible_at'  => 'required|date',
            'ends_at'     => 'required|date',
            'follow'      => 'nullable',
            'blank'       => 'nullable',
            'active'      => 'nullable'
        ]);

        $active       = 2;
        $approved_at  = null;
        $published_at = null;

        if(get_bool($data['active'])) {
            $active       = 1;
            $approved_at  = Carbon::now()->toDateTimeString();
            $published_at = Carbon::now()->toDateTimeString();
        }
        // dd($approved_at, $published_at);
        Link::create([
            'url'            => mysql_null($data['url']),
            'anchor'         => mysql_null($data['anchor']),
            'follow'         => get_bool_follow($data['follow']),
            'blank'          => get_blank($data['blank']),
            'alt'            => mysql_null($data['description']),
            'description'    => mysql_null($data['description']),
            'active'         => $active,
            'authority_site' => mysql_null($data['site']),
            'language'       => mysql_null($data['language']),
            'category'       => mysql_null($data['category']),
            'ends_at'        => mysql_null($data['ends_at']),
            'visible_at'     => mysql_null($data['visible_at']),
            'client'         => mysql_null($data['client']),
            'approved_at'    => $approved_at,
            'published_at'   => $published_at
        ]);

        self::resetLinksInputFields();

        session()->flash('successLink', trans('Link succesfully created'));
        $this->dispatchBrowserEvent('hideAddLink');
    }

    public function editLink() {
        $data = $this->validate([
            'client'      => 'required|numeric',
            'site'        => 'required|numeric',
            'url'         => 'required|url|max:255',
            'anchor'      => 'required|max:255',
            'description' => 'required|max:250',
            'language'    => 'required|numeric',
            'category'    => 'required|numeric',
            'visible_at'  => 'required|date',
            'ends_at'     => 'required|date',
            'follow'      => 'nullable',
            'blank'       => 'nullable',
            'active'      => 'nullable'
        ]);

        $active       = 2;
        $approved_at  = null;
        $published_at = null;

        if(get_bool($data['active'])) {
            $active       = 1;
            $approved_at  = Carbon::now()->toDateTimeString();
            $published_at = Carbon::now()->toDateTimeString();
        }

        $link = Link::find($this->link);

        if(!empty($link)) {
            $link->url            = mysql_null($data['url']);
            $link->anchor         = mysql_null($data['anchor']);
            $link->follow         = get_bool_follow($data['follow']);
            $link->blank          = get_blank($data['blank']);
            $link->alt            = mysql_null($data['description']);
            $link->description    = mysql_null($data['description']);
            $link->active         = $active;
            $link->authority_site = mysql_null($data['site']);
            $link->language       = mysql_null($data['language']);
            $link->category       = mysql_null($data['category']);
            $link->ends_at        = mysql_null($data['ends_at']);
            $link->visible_at     = mysql_null($data['visible_at']);
            $link->client         = mysql_null($data['client']);
            if(empty($link->approved_at)) {
                $link->approved_at = $approved_at;
            }
            if(empty($link->published_at)) {
                $link->published_at = $published_at;
            }
            $link->save();
        }

        self::resetLinksInputFields();

        session()->flash('successLink', trans('Link succesfully edited'));
        $this->dispatchBrowserEvent('hideEditLink');
    }

    public function confirmApprove($id) {
        $this->confirmApprove = $id;
        $this->dispatchBrowserEvent('confirmApprove');
    }

    public function approve() {
        Link::approve($this->confirmApprove);
        $this->confirmApprove = '';
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Link::destroy($this->confirm);
        $this->confirm   = '';
    }

    private function resetLinksInputFields() {
        $this->link        = '';
        $this->client      = '';
        $this->site        = '';
        $this->url         = '';
        $this->anchor      = '';
        $this->description = '';
        $this->language    = '';
        $this->category    = '';
        $this->visible_at  = '';
        $this->ends_at     = '';
        $this->follow      = 'follow';
        $this->blank       = '';
        $this->active      = '';
    }

}
