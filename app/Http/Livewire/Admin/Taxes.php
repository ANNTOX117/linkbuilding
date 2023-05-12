<?php

namespace App\Http\Livewire\Admin;

use App\Models\Country;
use App\Models\Tax;
use Livewire\Component;
use Livewire\WithPagination;

class Taxes extends Component {

    use WithPagination;

    public $confirm;
    public $countries;
    public $country;
    public $column = 'country';
    public $sort = 'asc';
    public $tax;
    public $tax_id;
    public $title;
    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function mount() {
        if(!permission('taxes', 'read')) {
            abort(404);
        }

        $this->title      = trans('Taxes');
        $this->countries  = Country::all_items();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $taxes = Tax::with_pagination($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.taxes', compact('taxes'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
        $this->column = $column;
    }

    public function modalAddTax() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddTax');
    }

    public function modalEditTax($id) {
        $tax = Tax::find($id);

        if(!empty($tax)) {
            $this->tax_id  = $tax->id;
            $this->country = $tax->country;
            $this->tax     = $tax->tax;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('selectCountry', ['country' => $this->country]);
            $this->dispatchBrowserEvent('showEditTax');
        }
    }

    public function addTax() {
        if(Tax::already_exists($this->country)) {
            $this->addError('country', trans('The tax for that country already exists'));
            return false;
        }

        $data = $this->validate([
            'country' => 'required|numeric',
            'tax'     => 'required|numeric'
        ]);

        Tax::create([
            'country' => mysql_null($data['country']),
            'tax'     => mysql_null($data['tax'])
        ]);

        self::resetInputFields();

        session()->flash('successTax', trans('Tax succesfully created'));
        $this->dispatchBrowserEvent('hideAddTax');
    }

    public function editTax() {
        $data = $this->validate([
            'country' => 'required|numeric',
            'tax'     => 'required|numeric'
        ]);

        $tax = Tax::find($this->tax_id);

        if(!empty($tax)) {
            $tax->country = mysql_null($data['country']);
            $tax->tax     = mysql_null($data['tax']);
            $tax->save();
        }

        self::resetInputFields();

        session()->flash('successTax', trans('Tax succesfully edited'));
        $this->dispatchBrowserEvent('hideEditTax');
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Tax::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->country = '';
        $this->tax     = '';
    }
}
