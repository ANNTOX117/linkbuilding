<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Language;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\PackageSite;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Packages extends Component {

    use WithPagination;

    public $title;
    public $section = 'packages';
    public $column_packages   = 'name';
    public $column_categories = 'name';
    public $sort    = 'asc';
    public $confirm;
    public $sites;
    public $languages;
    public $categories;
    public $package;
    public $name;
    public $description;
    public $price;
    public $language;
    public $packages_category;
    public $category;
    public $category_id;
    public $category_name;
    public $site = [];
    public $tab = 'packages';
    public $table;
    public $what;
    public $suggested_price = '0.00';
    public $pagination;
    public $search = '';
    public $packages_categories;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'              => 'required|max:75',
        'description'       => 'required|max:250',
        'price'             => 'required|numeric',
        'language'          => 'required|numeric',
        'category'          => 'required|numeric',
        'site'              => 'required',
        'packages_category' => 'required|numeric'
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

    public function updatedSite($sites) {
        $this->suggested_price = floatval(AuthoritySite::calculate_price($sites));
    }

    public function mount() {
        if(!permission('packages', 'read')) {
            abort(404);
        }

        $this->title      = trans('Packages');
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');

        $this->packages_categories = PackageCategory::all_items();
    }

    public function render() {
        $packages        = Package::with_filter($this->column_packages, $this->sort, $this->pagination, $this->search);
        $categories_list = PackageCategory::with_filter($this->column_categories, $this->sort, $this->pagination, $this->search);

        if(!empty($this->language)) {
            $this->categories = Category::by_language($this->language);
        }

        return view('livewire.admin.packages', compact('packages', 'categories_list'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        $this->pagination = env('APP_PAGINATE');
        $this->search = '';

        if($this->tab == 'packages') {
            $this->column_packages = 'name';
        }

        if($this->tab == 'categories') {
            $this->column_categories = 'name';
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'packages') {
            $this->column_packages = $column;
        }

        if($table == 'categories') {
            $this->column_categories = $column;
        }
    }

    public function modalAddPackage() {
        $this->suggested_price = '0.00';
        self::resetInputFields();

        $this->resetErrorBag();
        $this->dispatchBrowserEvent('loadSites', ['options' => AuthoritySite::select_for_packages()]);
        $this->dispatchBrowserEvent('showAddPackage');
        $this->dispatchBrowserEvent('resetCategory');
    }

    public function modalAddCategory() {
        self::resetInputCategoryFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddCategory');
    }

    public function modalEditPackage($id) {
        $this->suggested_price = '0.00';
        $package = Package::find($id);

        if(!empty($package)) {
            $this->package           = $package->id;
            $this->name              = $package->name;
            $this->description       = $package->description;
            $this->price             = $package->price;
            $this->language          = $package->language;
            $this->categories        = Category::by_language($package->language);
            $this->sites             = AuthoritySite::list_for_packages($package->id);
            $this->category          = PackageSite::package_category($package->id);
            $this->site              = PackageSite::package_ids($package->id);
            $this->packages_category = $package->category;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('editSites', ['options' =>  $this->sites]);
            $this->dispatchBrowserEvent('showEditPackage');
        }
    }

    public function modalEditCategory($id) {
        $category = PackageCategory::find($id);

        if(!empty($category)) {
            $this->category_id   = $category->id;
            $this->category_name = $category->name;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditCategory');
        }
    }

    public function addPackage() {
        $data = $this->validate([
            'name'              => 'required|max:75',
            'description'       => 'required|max:250',
            'price'             => 'required|numeric',
            'language'          => 'required|numeric',
            'category'          => 'required|numeric',
            'site'              => 'required',
            'packages_category' => 'required|numeric'
        ]);

        if(Package::already_exists($data['name'], $data['language'])) {
            $this->addError('name', trans('This package already exists on the database'));
            return false;
        }

        $package = Package::create([
            'name'        => mysql_null($data['name']),
            'description' => mysql_null($data['description']),
            'price'       => mysql_null($data['price']),
            'category'    => mysql_null($data['packages_category']),
            'language'    => mysql_null($data['language'])
        ]);

        if(!empty($data['site'])) {
            $sites = array();
            foreach($data['site'] as $i => $item) {
                $sites[$i]['package']        = mysql_null($package->id);
                $sites[$i]['authority_site'] = mysql_null($item);
                $sites[$i]['category']       = mysql_null($data['category']);
            }
            DB::table('packages_sites')->insert($sites);
        }

        self::resetInputFields();

        session()->flash('successPackage', trans('Package succesfully created'));
        $this->dispatchBrowserEvent('hideAddPackage');
    }

    public function editPackage() {
        $data = $this->validate([
            'name'              => 'required|max:75',
            'description'       => 'required|max:250',
            'price'             => 'required|numeric',
            'language'          => 'required|numeric',
            'category'          => 'required|numeric',
            'site'              => 'required',
            'packages_category' => 'required|numeric'
        ]);

        if(Package::already_exists($data['name'], $data['language'], $this->package)) {
            $this->addError('name', trans('This package already exists on the database'));
            return false;
        }

        $package = Package::find($this->package);

        if(!empty($package)) {
            $package->name        = mysql_null($data['name']);
            $package->description = mysql_null($data['description']);
            $package->price       = mysql_null($data['price']);
            $package->category    = mysql_null($data['packages_category']);
            $package->language    = mysql_null($data['language']);
            $package->save();
        }

        if(!empty($data['site'])) {
            PackageSite::cleanup($package->id);

            $sites = array();
            foreach($data['site'] as $i => $item) {
                $sites[$i]['package']        = mysql_null($package->id);
                $sites[$i]['authority_site'] = mysql_null($item);
                $sites[$i]['category']       = mysql_null($data['category']);
            }
            DB::table('packages_sites')->insert($sites);
        }

        self::resetInputFields();

        session()->flash('successPackage', trans('Package succesfully edited'));
        $this->dispatchBrowserEvent('hideEditPackage');
    }

    public function addCategory() {
        if(PackageCategory::already_exists($this->category_name)) {
            $this->addError('category_name', trans('The category for that package already exists'));
            return false;
        }

        $data = $this->validate([
            'category_name' => 'required|max:50'
        ]);

        PackageCategory::create([
            'name' => mysql_null($data['category_name'])
        ]);

        self::resetInputCategoryFields();

        session()->flash('successCategory', trans('Category succesfully created'));
        $this->dispatchBrowserEvent('hideAddCategory');
    }

    public function editCategory() {
        $data = $this->validate([
            'category_name' => 'required|max:50'
        ]);

        $category = PackageCategory::find($this->category_id);

        if(!empty($category)) {
            $category->name = mysql_null($data['category_name']);
            $category->save();
        }

        self::resetInputCategoryFields();

        session()->flash('successCategory', trans('Category succesfully edited'));
        $this->dispatchBrowserEvent('hideEditCategory');
    }

    public function confirm($id) {
        if($this->tab == 'packages') {
            $this->what = trans('package');
        }

        if($this->tab == 'categories') {
            $this->what = trans('category');
        }

        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        if($this->tab == 'packages') {
            Package::destroy($this->confirm);
        }

        if($this->tab == 'categories') {
            PackageCategory::destroy($this->confirm);
        }

        $this->confirm = '';
    }

    private function loadCategories() {
        //
    }

    private function resetInputFields() {
        $this->package           = '';
        $this->name              = '';
        $this->description       = '';
        $this->price             = '';
        $this->packages_category = '';
        $this->language          = '';
        $this->category          = '';
        $this->site              = '';
        $this->sites             = '';
    }

    private function resetInputCategoryFields() {
        $this->category_id   = '';
        $this->category_name = '';
    }

}
