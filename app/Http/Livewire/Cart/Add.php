<?php

namespace App\Http\Livewire\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Add extends Component {

    public $identifier;
    public $item;
    public $price;
    public $styles;

    public function render() {
        return view('livewire.cart.add');
    }

    public function purchase() {
        Cart::create(['item' => $this->item, 'identifier' => $this->identifier, 'price' => $this->price, 'user' => Auth::user()->id]);

        $this->dispatchBrowserEvent('doConfirm', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => route('customer_cart')]);
        $this->emitTo('cart.link', '$refresh');
    }

}
