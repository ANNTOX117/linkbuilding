<?php

namespace App\Http\Livewire\Account;

use App\Models\AuthoritySite;
use App\Models\Cart;
use App\Models\Link;
use App\Models\Article;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Renewal extends Component {

    public $title;
    public $p;
    public $article;
    public $products;

    protected $queryString = ['p', 'article'];

    public function mount() {
        $this->title = trans('Renewal');
    }

    public function render() {
        $products = base64_decode($this->p);
        $article = $this->article;

        if(empty($products)) {
            abort(404);
        }

        $products = explode(',', $products);
        $order  = get_invoice();
        if($article){
            $this->processArticles($products);
        } else {
            $this->processLinks($products);
        }
        
        return view('livewire.account.renewal', ['order' => $order])->layout('layouts.account', ['title' => $this->title]);
    }

    public function processLinks($products){
        $this->products = Link::get_links($products);
        $orders = array();

        foreach($this->products as $i => $product) {
            $authority = AuthoritySite::find($product->authority_site);
            $type      = get_wordpress_or_site($authority);
            $page      = type_page($authority->type);
            $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

            if(!empty($product->orders)) {

                $json    = json_decode($product->orders->details, true);
                $details   = array();
                $details['authority'] = $json['authority'];
                $details['site']      = $json['site'];
                $details['category']  = $json['category'];
                $details['anchor']    = $json['anchor'];
                $details['title']     = $json['title'];
                $details['url']       = $json['url'];
                $details['follow']    = $json['follow'];
                $details['blank']     = $json['blank'];
                $details['date']      = $json['date'];
                $details['years']     = $json['years'];
                $details['renewal']   = $product->id;

                Cart::create(['item' => 'blog sidebar link', 'identifier' => $authority, 'details' => json_encode($details), 'price' => $price, 'user' => Auth::user()->id]);

                // $json    = json_decode($product->orders->details, true);
                // $details = null;
                // // $details = '['. json_encode($product->url) . ']';

                // foreach($json as $item => $value) {
                //     if($item == 'url' and $value == $product->url) {
                //         // $details = '['. json_encode($json) . ']';
                //         $details = json_encode($json);
                //     }
                // }

                // foreach($json as $item) {
                //     if($item['url'] == $product->url) {
                //         $details = '['. json_encode($item) . ']';
                //     }
                // }

                // Order::create([
                //     'order'      => $order,
                //     'item'       => 'renewal',
                //     'identifier' => $product->id,
                //     'details'    => $details,
                //     'price'      => $price,
                //     'total'      => $price,
                //     'payment'    => 0,
                //     'status'     => 'open',
                //     'user'       => auth()->id()
                // ]);
            } else {
                $create = array('order' => $order, 'item' => 'renewal', 'identifier' => $product->id, 'details' => null, 'price' => $price, 'total' => $price, 'payment' => '', 'status' => 'open', 'user' => auth()->id());
                Order::create($create);
            }
        }
    }

    public function processArticles($products){
        $this->products = Article::get_articles($products);

        $orders = array();
        foreach($this->products as $i => $product) {

            $authority = AuthoritySite::find($product->authority_site);
            $type      = get_wordpress_or_site($authority);
            $page      = type_page($authority->type);
            $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

            if(!empty($product->orders)) {
                $json    = json_decode($product->orders->details, true);
                $details   = array();
                $details['authority'] = $json['authority'];
                $details['wordpress'] = $json['wordpress'];
                $details['content']   = $json['content'];
                $details['image']    = $json['image'];
                $details['category']  = $json['category'];
                $details['title']     = $json['title'];
                $details['url']       = $json['url'];
                $details['date']      = $json['date'];
                $details['years']     = $json['years'];
                $details['renewal']   = $product->id;

                Cart::create(['item' => 'blog article', 'identifier' => $authority, 'details' => json_encode($details), 'price' => $price, 'user' => Auth::user()->id]);
            }
        }
    }

}
