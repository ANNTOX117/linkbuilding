<?php

namespace App\Http\Livewire\Admin;

use App\Models\Article;
use App\Models\Link;
use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component {

    public $title;
    public $links;
    public $articles;
    public $users;
    public $today;
    public $month;
    public $payments;

    public function mount() {
        if(!permission('dashboard', 'read')) {
            abort(404);
        }

        $this->title    = trans('Dashboard');
        $this->links    = Link::count_all_pendings(); // Pending links
        $this->articles = Article::count_all_pendings(); // Pending articles
        $this->users    = User::count_last_users(); // New users
        $this->today    = Order::earnings_today(); // Payments today
        $this->month    = Order::earnings_this_month(); // Payments this month
        $this->payments = Order::earnings_total(); // Total payments
    }

    public function render() {
        return view('livewire.admin.dashboard')->layout('layouts.panel');
    }

}
