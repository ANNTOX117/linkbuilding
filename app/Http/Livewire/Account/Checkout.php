<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Checkout extends Component {

    public $title;

    public function mount() {
        $this->title = trans('Checkout');
    }

    public function render() {
        return view('livewire.account.checkout')->layout('layouts.account', ['title' => $this->title]);
    }
}
