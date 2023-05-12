<?php

namespace App\Http\Livewire\Admin;

use App\Models\Language;
use Livewire\Component;
use Livewire\WithPagination;

class Languages extends Component {

    use WithPagination;

    public $title;
    public $section = 'languages';
    public $column  = 'description';
    public $sort    = 'asc';
    public $confirm;
    public $language_id;
    public $name;
    public $description;
    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'        => 'required|min:2',
        'description' => 'required'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('languages', 'read')) {
            abort(404);
        }

        $this->title      = trans('Languages');
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $languages = Language::with_filter($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.languages', compact('languages'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function modalAddLanguage() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddLanguage');
    }

    public function modalEditLanguage($id) {
        $language = Language::find($id);

        if(!empty($language)) {
            $this->language_id = $language->id;
            $this->name        = $language->name;
            $this->description = $language->description;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditLanguage');
        }
    }

    public function addLanguage() {
        $data = $this->validate([
            'name'        => 'required|min:2',
            'description' => 'required'
        ]);

        if(Language::already_exists($data['name'])) {
            $this->addError('name', trans('This language already exists on the database'));
            return false;
        }

        Language::create([
            'name'        => mysql_null($data['name']),
            'description' => mysql_null($data['description'])
        ]);

        self::resetInputFields();

        session()->flash('successLanguage', trans('Language succesfully created'));
        $this->dispatchBrowserEvent('hideAddLanguage');
    }

    public function editLanguage() {
        $data = $this->validate([
            'name'        => 'required|size:2',
            'description' => 'required'
        ]);

        if(Language::already_exists($data['name'], $this->language_id)) {
            $this->addError('name', trans('This language already exists on the database'));
            return false;
        }

        $language = Language::find($this->language_id);

        if(!empty($language)) {
            $language->name        = mysql_null($data['name']);
            $language->description = mysql_null($data['description']);
            $language->save();
        }

        self::resetInputFields();

        session()->flash('successLanguage', trans('Language succesfully edited'));
        $this->dispatchBrowserEvent('hideEditLanguage');
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Language::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->name        = '';
        $this->description = '';
    }

}
