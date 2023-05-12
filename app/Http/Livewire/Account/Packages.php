<?php

namespace App\Http\Livewire\Account;

use App\Models\AuthoritySite;
use App\Models\Cart;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\PackageSite;
use App\Models\SiteCategoryChild;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Packages extends Component {

    public $menu;
    public $title;
    public $categories;
    public $packages;
    public $info;
    public $package;
    public $name;
    public $sort;
    public $view = 'categories';
    public $package_id;
    public $package_homepages = [];
    public $package_categories = [];
    public $package_anchors = [];
    public $package_titles = [];
    public $package_urls = [];
    public $package_follows = [];
    public $package_blanks = [];
    public $package_dates = [];
    public $max_fields = 0;
    public $max_categories = 0;
    public $completed = false;

    public function mount() {
        $this->title = trans('Packages');
        $this->menu  = 'Packages';
    }

    public function render() {
        self::loadPackages();

        if($this->view == 'configuration' and is_numeric($this->package_id)) {
            self::loadPackage();
        }

        return view('livewire.account.packages')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
    }

    public function loadPackages() {
        $this->categories = PackageCategory::availables();

        if(!empty($this->categories)) {
            foreach($this->categories as $i => $category) {
                $this->categories[$i]['packages'] = PackageCategory::packages($category->id);
            }
        }
    }

    public function loadPackage() {
        $this->package = PackageSite::get_info($this->package_id);

        foreach($this->package as $i => $package) {
            $this->package[$i]['categories'] = (is_numeric($package->site)) ? SiteCategoryChild::get_categories($package->site) : null;
            $this->package_homepages[$package->id] = $package->site;
        }
    }

    public function sort($i, $category, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($this->view == 'categories') {
            $this->categories[$i]['packages'] = PackageCategory::packages($category, $column, $this->sort);
        }

        if($this->view == 'details') {
            $this->package = PackageSite::get_info($category, $column, $this->sort);
        }
    }

    public function showPackage($id) {
        $this->package_id = $id;
        $this->info       = Package::find($id);
        $this->package    = PackageSite::get_info($id);
        $this->view       = 'details';

        $this->dispatchBrowserEvent('onDetails');
    }

    public function showConfig($id) {
        $this->package_id = $id;
        $this->view       = 'configuration';

        $this->dispatchBrowserEvent('onConfig');
        $this->dispatchBrowserEvent('loadDatepicker');
        $this->dispatchBrowserEvent('countCategories');
    }

    public function goBack() {
        $this->name    = '';
        $this->info    = '';
        $this->package = '';
        $this->view    = 'categories';

        $this->dispatchBrowserEvent('onBack');
    }

    public function doOrder() {
        if(count($this->package_categories) != $this->max_categories) {
            $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the categories before continuing'), 'cancel' => trans('OK')]);
            return false;
        }

        if(count($this->package_anchors) != $this->max_fields) {
            $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the anchors before continuing'), 'cancel' => trans('OK')]);
            return false;
        }

        if(count($this->package_titles) != $this->max_fields) {
            $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the titles before continuing'), 'cancel' => trans('OK')]);
            return false;
        }

        if(count($this->package_urls) != $this->max_fields) {
            $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the URLs before continuing'), 'cancel' => trans('OK')]);
            return false;
        }

        if(count($this->package_dates) != $this->max_fields) {
            $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the publication dates before continuing'), 'cancel' => trans('OK')]);
            return false;
        }

        foreach($this->package_urls as $i => $url) {
            $link = prefix_http($url);
            if(!is_valid_url($link)) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Apparently ":url" is not a valid URL', ['url' => $url]), 'cancel' => trans('OK')]);
                return false;
            }
            $this->package_urls[$i] = $link;
        }

        $package = Package::find($this->package_id);
        $index   = 0;
        $details = [];

        foreach($this->package_homepages as $i => $row) {
            $details[$index]['authority'] = $i;
            $details[$index]['site']      = $row;
            $details[$index]['category']  = @$this->package_categories[$i];
            $details[$index]['anchor']    = @$this->package_anchors[$i];
            $details[$index]['title']     = @$this->package_titles[$i];
            $details[$index]['url']       = @$this->package_urls[$i];
            $details[$index]['follow']    = get_follow(@$this->package_follows[$i]);
            $details[$index]['blank']     = get_bool(@$this->package_blanks[$i]);
            $details[$index]['date']      = fix_date(@$this->package_dates[$i]);
            $details[$index]['years']     = 1;

            $index++;
        }

        Cart::create(['item' => 'packages', 'identifier' => $package->id, 'details' => json_encode($details), 'price' => $package->price, 'user' => Auth::user()->id]);

        $this->completed = true;
        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => route('customer_cart')]);
        $this->emitTo('cart.link', '$refresh');
    }

    public function categoryUpdated($position, $value) {
        $this->package_categories[$position] = $value;
    }

    public function dateUpdated($position, $value) {
        $this->package_dates[$position] = $value;
    }

    public function buyPackage($id) {
        $this->view = 'purchase';

        $this->dispatchBrowserEvent('onPurchase');
    }

}
