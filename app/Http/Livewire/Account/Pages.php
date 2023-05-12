<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Pages extends Component {

    public $title;

    public function mount() {
        $this->title = trans('Pages');
    }

    public function render() {
        return view('livewire.account.pages')->layout('layouts.panel');
    }

}
