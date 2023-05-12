<?php

namespace App\Http\Livewire\Cart;

use App\Models\Cart;
use Livewire\Component;

class Link extends Component {

    public $total = '0.00';

    protected $listeners = [
        '$refresh'
    ];

    public function render() {
        $this->total = Cart::myTotal() ?? '0.00';

        return view('livewire.cart.link');
    }

}
