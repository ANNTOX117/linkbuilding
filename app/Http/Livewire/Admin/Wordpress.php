<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Language;
use App\Models\Site;
use App\Models\User;
use App\Models\SiteCategoryMain;
use App\Models\AuthorityUser;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Wordpress extends Component {

    use WithPagination;

    public $title;
    public $section = 'wordpress';
    public $column  = 'name';
    public $sort    = 'asc';
    public $confirm;
    public $site_id;
    public $name;
    public $url;
    public $type;
    public $ip;
    public $automatic;
    public $username;
    public $password;
    public $languages;
    public $language;
    public $categories;
    public $category = [];
    public $categories_id;
    public $edit_categories = false;
    public $site;
    public $list;
    public $selections = [];

    public $pagination = 10;
    public $search = '';

    public $users = [];
    public $users_selected = [];

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'      => 'required',
        'url'       => 'required|url',
        'type'      => 'required',
        'ip'        => 'required|ip',
        'automatic' => 'nullable',
        'language'  => 'required|numeric',
        'username'  => 'required',
        'password'  => 'required'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedIp($ip) {
        if(!empty($ip) and is_not_valid_domain($ip)) {
            $this->addError('ip', trans('The IP must be a valid IP address'));
            return false;
        }
    }

    public function updatedLanguage($language) {
        if(!is_null($language)) {
            $this->categories = Category::by_language($language);
            $this->dispatchBrowserEvent('resetCategories', ['options' => $this->categories]);
        }
    }

    public function change_selections() {
        $this->edit_categories = true;
    }

    public function mount() {
        if(!permission('wordpress', 'read')) {
            abort(404);
        }

        $this->title     = trans('Wordpress sites');
        $this->languages = Language::all();
        $this->users     = User::all_items('name','asc');
    }

    public function render() {
        $sites = \App\Models\Wordpress::with_filter($this->column, $this->sort, $this->pagination, $this->search);

        // Fix for categories
        if($this->edit_categories) {
            $this->list = SiteCategoryMain::list_for_wordpress($this->site->id);

            if(!empty($this->list)) {
                foreach($this->list as $item) {
                    $item->visibility = $this->selections[$item->id];
                }
            }

            $this->edit_categories = false;
        }

        return view('livewire.admin.wordpress', compact('sites'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function modalAddSite() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddSite');
    }

    public function modalEditSite($id) {
        $site = \App\Models\Wordpress::find($id);

        if(!empty($site)) {
            $this->site_id       = $site->id;
            $this->name          = $site->name;
            $this->url           = $site->url;
            $this->type          = $site->type;
            $this->ip            = $site->ip;
            $this->automatic     = $site->automatic;
            $this->language      = $site->language;
            $this->username      = $site->username;
            $this->password      = do_decrypt($site->password);
            $this->categories    = Category::wp_category_by_language($site->id, $site->language);
            $this->category      = SiteCategoryMain::by_wp($site->id);
            $this->categories_id = SiteCategoryMain::by_wp_ids($site->id);

            $authorities = AuthoritySite::getWordpress($site->id);
            if (!empty($authorities)) {
                $this->users_selected = AuthorityUser::getAuthority($authorities);
            }

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('resetEditCategories', ['options' => $this->categories]);
            $this->dispatchBrowserEvent('showEditSite');
            $this->dispatchBrowserEvent('resetEditUsers');
        }
    }

    public function modalEditCategories($id) {
        self::resetCategoriesFields();

        $this->site = \App\Models\Wordpress::find($id);

        if(!empty($this->site)) {
            $this->list = SiteCategoryMain::list_for_wordpress($id);

            if(!empty($this->list)) {
                foreach($this->list as $item) {
                    $this->selections[$item->id] = $item->visibility;
                }
            }
        }

        $this->dispatchBrowserEvent('showEditCategories');
    }

    public function addSite() {
        $data = $this->validate([
            'name'      => 'required',
            'url'       => 'required|url',
            'type'      => 'required',
            'ip'        => 'required',
            'automatic' => 'nullable',
            'language'  => 'required|numeric',
            'username'  => 'required',
            'password'  => 'required',
            'category'  => 'nullable',
            'users_selected'    => 'nullable'
        ]);

        if(\App\Models\Wordpress::already_exists($data['url'])) {
            $this->addError('url', trans('This URL already exists on the database'));
            return false;
        }

        $wordpress = \App\Models\Wordpress::create([
            'name'      => mysql_null($data['name']),
            'url'       => mysql_null($data['url']),
            'type'      => mysql_null($data['type']),
            'ip'        => mysql_null($data['ip']),
            'automatic' => get_bool($data['automatic']),
            'language'  => mysql_null($data['language']),
            'username'  => mysql_null($data['username']),
            'password'  => do_encrypt($data['password'])
        ]);

        $site = AuthoritySite::create([
            'wordpress' => $wordpress->id,
            'url'       => mysql_null($data['url']),
            'type'      => 'wordpress'
        ]);

        if(!empty($data['category'])) {
            $categories = array();
            foreach($data['category'] as $i => $item) {
                $categories[$i]['category']  = mysql_null($item);
                $categories[$i]['wordpress'] = mysql_null($wordpress->id);
            }
            DB::table('sites_categories_main')->insert($categories);
        }

        if (!empty($data['users_selected'])) {

            $authorities = AuthoritySite::getWordpress($wordpress->id);
            
            foreach ($data['users_selected'] as $user) {
                if (!empty($authorities)) {
                    foreach ($authorities as $authority) {
                        $inser_authority_user[] = array(
                            'authority' => $authority, 
                            'user' => $user,
                        );
                    }
                }
            }
            
            DB::table('authority_user')->insert($inser_authority_user);
        }

        self::resetInputFields();

        session()->flash('successSite', trans('Wordpress site succesfully created'));
        $this->dispatchBrowserEvent('hideAddSite');
    }

    public function editSite() {
        $data = $this->validate([
            'name'      => 'required',
            'url'       => 'required|url',
            'type'      => 'required',
            'ip'        => 'required',
            'automatic' => 'nullable',
            'language'  => 'required|numeric',
            'username'  => 'required',
            'password'  => 'required',
            'category'  => 'nullable',
            'users_selected' => 'nullable',
        ]);

        if(\App\Models\Wordpress::already_exists($data['url'], $this->site_id)) {
            $this->addError('url', trans('This URL already exists on the database'));
            return false;
        }

        $wordpress = \App\Models\Wordpress::find($this->site_id);
        $link      = null;

        if(!empty($wordpress)) {
            $link                 = $wordpress->url;
            $wordpress->name      = mysql_null($data['name']);
            $wordpress->url       = mysql_null($data['url']);
            $wordpress->type      = mysql_null($data['type']);
            $wordpress->ip        = mysql_null($data['ip']);
            $wordpress->automatic = get_bool($data['automatic']);
            $wordpress->language  = mysql_null($data['language']);
            $wordpress->username  = mysql_null($data['username']);
            $wordpress->password  = do_encrypt($data['password']);
            $wordpress->save();
        }

        $site = AuthoritySite::get_site_by_url($link);

        if(!empty($site)) {
            $site->url  = mysql_null($data['url']);
            $site->type = 'wordpress';
            $site->save();
        }

        if(!empty($data['category'])) {
            $categories = array();
            foreach($data['category'] as $i => $item) {
                $categories[$i]['category']  = mysql_null($item);
                $categories[$i]['wordpress'] = mysql_null($wordpress->id);
            }
            SiteCategoryMain::cleanup_wp($wordpress->id);
            DB::table('sites_categories_main')->insert($categories);
        }

        if (!empty($data['users_selected'])) {

            $authorities = AuthoritySite::getWordpress($wordpress->id);
            $authority_user = AuthorityUser::getAuthority($authorities, true);
            
            foreach ($data['users_selected'] as $user) {
                if (!empty($authorities)) {
                    foreach ($authorities as $authority) {
                        $inser_authority_user[] = array(
                            'authority' => $authority, 
                            'user' => $user,
                        );
                    }
                }
            }
            
            DB::table('authority_user')->insert($inser_authority_user);
        }

        self::resetInputFields();

        session()->flash('successSite', trans('Wordpress site succesfully edited'));
        $this->dispatchBrowserEvent('hideEditSite');
    }

    public function editCategories() {
        if(!empty($this->selections)) {
            foreach($this->selections as $i => $item) {
                SiteCategoryMain::set_wordpress_visibility($this->site->id, $i, $item);
            }
        }

        self::resetCategoriesFields();

        session()->flash('successCategories', trans('Categories succesfully edited'));
        $this->dispatchBrowserEvent('hideEditCategories');
    }

    public function confirm($id, $links) {
        if($links > 0) {
            $this->dispatchBrowserEvent('warningDelete');
        } else {
            $this->confirm = $id;
            $this->dispatchBrowserEvent('confirmDelete');
        }
    }

    public function delete() {
        $wordpress = \App\Models\Wordpress::find($this->confirm);
        AuthoritySite::clean_by_url($wordpress->url);
        \App\Models\Wordpress::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->name          = '';
        $this->url           = '';
        $this->type          = '';
        $this->ip            = '';
        $this->automatic     = '';
        $this->language      = '';
        $this->username      = '';
        $this->password      = '';
        $this->categories    = '';
        $this->category      = '';
        $this->categories_id = '';
        $this->users_selected   = [];
    }

    private function resetCategoriesFields() {
        $this->site       = '';
        $this->list       = '';
        $this->selections = [];
    }

}
