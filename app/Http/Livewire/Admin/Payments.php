<?php

namespace App\Http\Livewire\Admin;

use App\Models\ArticleRequested;
use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class Payments extends Component {

    use WithPagination;

    public $title;
    public $section = 'payments';
    public $column  = 'created_at';
    public $sort    = 'desc';
    public $confirm;
    public $confirmApprove;
    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'     => 'required',
        'language' => 'required|numeric'
    ];

    public function updated($propertyName){
        //$this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('payments', 'read')) {
            abort(404);
        }

        $this->title      = trans('Payments');
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $payments = Order::list($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.payments', compact('payments'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
        $this->column = $column;
    }

    public function confirmApprove($id) {
        $this->confirmApprove = $id;
        $this->dispatchBrowserEvent('confirmApprove');
    }

    public function approve() {
        Order::approve($this->confirmApprove);
        $this->confirmApprove = '';
    }

}
