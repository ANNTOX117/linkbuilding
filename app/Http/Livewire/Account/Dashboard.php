<?php

namespace App\Http\Livewire\Account;

use App\Models\Discount;
use App\Models\GroupDiscount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\AuthoritySite;
use App\Models\Link;

class Dashboard extends Component {

    public $title;
    public $menu;
    public $user;
    public $site;
    public $staffel;
    public $from;
    public $to;
    public $percentage;
    public $orders;
    public $is_empty;

    public $active = 0;
    public $about  = 0;
    public $expire = 0;

    public function mount() {
        $this->user     = Auth::user()->id;
        $this->site     = AuthoritySite::inRandomOrder()->where('featured', 1)->first();
        $this->title    = trans('Dashboard');
        $this->menu     = 'Dashboard';
        $this->active   = Link::mylinks()->get()->count();
        $this->about    = Link::mylinksabout()->get()->count();
        $this->expire   = Link::myliksexpired()->get()->count();
        $this->orders   = Order::latest_invoices();
        $this->is_empty = Order::is_empty();

        $staffel = GroupDiscount::with_name('Default');

        if(!empty($staffel)) {
            $this->from       = Discount::get_values($staffel->id, 'from');
            $this->to         = Discount::get_values($staffel->id, 'to');
            $this->percentage = Discount::get_values($staffel->id, 'percentage');
        }
    }

    public function render() {
        return view('livewire.account.dashboard')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
    }

}
