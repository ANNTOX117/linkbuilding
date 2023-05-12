<?php

namespace App\Http\Livewire\Account;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component {

    use WithPagination;

    public $title;
    public $menu;
    public $column = 'created_at';
    public $sort   = 'desc';
    public $selected;
    public $table  = [];

    protected $paginationTheme = 'bootstrap';

    public function mount() {
        $this->title = trans('Orders');
        $this->menu  = 'Orders';
    }

    public function render() {
        $orders = Order::with_pagination($this->column, $this->sort);

        return view('livewire.account.orders', compact('orders'))->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function details($order, $option) {
        $this->selected = ($option == 'hide') ? '' : $order;

        if($option == 'show') {
            $this->table = [];

            $details = Order::get_details($order);
            if(!empty($details)) {
                foreach($details as $detail) {
                    $json = json_decode($detail->details, true);
                    if(!empty($json)) {
                        if($detail->item == 'packages') {
                            foreach($json as $item) {
                                $item['order'] = $detail->id;
                                $item['item']  = 'packages';
                                $this->table[] = $item;
                            }
                        } else {
                            if(count($json) == 1) {
                                $json = $json[0];
                            }
                            $json['order'] = $detail->id;
                            $json['item']  = $detail->item;
                            $this->table[] = $json;
                        }
                    }
                }
            }

            if(!empty($this->table)) {
                foreach($this->table as $i => $table) {
                    $order = Order::find($table['order']);
                    $site  = AuthoritySite::find($table['authority']);

                    if(!empty($table['category'])) {
                        $category = Category::find($table['category']);
                        $this->table[$i]['category'] = (!empty($category)) ? $category->name : null;
                    }

                    $this->table[$i]['site'] = $site->url;
                    $this->table[$i]['type'] = $site->type;
                }
            }

            $this->dispatchBrowserEvent('onShow');
        } else {
            $this->table = [];
            $this->dispatchBrowserEvent('onHide');
        }
    }

}
