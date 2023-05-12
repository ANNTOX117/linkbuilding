<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Buy extends Component {

    public $title;

    public function mount() {
        $this->title = trans('Buy links');
        $this->menu  = 'Buy links';
    }

    public function render() {
        return view('livewire.account.buy')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
    }

}
