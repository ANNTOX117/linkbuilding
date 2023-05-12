<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Blogs extends Component {

    public $title;

    public function mount() {
        $this->title = trans('Blogs');
    }

    public function render() {
        return view('livewire.account.blogs')->layout('layouts.panel');
    }

}
