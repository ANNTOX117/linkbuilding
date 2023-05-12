<?php

namespace App\Http\Livewire\Admin;

use App\Models\Language;
use App\Models\Text;
use Livewire\Component;

class Texts extends Component {

    public $title;
    public $section = 'texts';
    public $column  = 'name';
    public $sort    = 'asc';
    public $confirm;
    public $custom_error;
    public $text_id;
    public $text_name;
    public $text_title;
    public $text_description;
    public $language;
    public $languages;
    public $pagination;
    public $search = '';

    protected $rules = [
        'text_name'        => 'required|max:140',
        'text_title'       => 'required|max:140',
        'text_description' => 'required',
        'language'         => 'required|numeric'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function mount() {
        if(!permission('texts', 'read')) {
            abort(404);
        }

        $this->title      = trans('Static texts');
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $texts = Text::with_pagination($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.texts', compact('texts'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
        $this->column = $column;
    }

    public function modalAddText() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddText');
    }

    public function modalEditText($id) {
        $text = Text::find($id);

        if(!empty($text)) {
            $this->text_id          = $text->id;
            $this->text_name        = $text->name;
            $this->text_title       = $text->title;
            $this->text_description = $text->description;
            $this->language         = $text->language;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditText', ['editor' => $this->text_description]);
        }
    }

    public function addText() {
        $data = $this->validate([
            'text_name'        => 'required|max:140',
            'text_title'       => 'required|max:140',
            'text_description' => 'required',
            'language'         => 'required|numeric',
        ]);

        if(Text::already_exists($data['text_name'], $data['language'])) {
            $this->custom_error = trans('This name already exists on the database');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }

        Text::create([
            'name'        => mysql_null($data['text_name']),
            'title'       => mysql_null($data['text_title']),
            'description' => mysql_null($data['text_description']),
            'language'    => mysql_null($data['language'])
        ]);

        self::resetInputFields();

        session()->flash('successText', trans('Text succesfully created'));
        $this->dispatchBrowserEvent('hideAddText');
    }

    public function editText() {
        $data = $this->validate([
            'text_name'        => 'required|max:140',
            'text_title'       => 'required|max:140',
            'text_description' => 'required',
            'language'         => 'required|numeric',
        ]);

        $text = Text::find($this->text_id);

        if(!empty($text)) {
            $text->name        = mysql_null($data['text_name']);
            $text->title       = mysql_null($data['text_title']);
            $text->description = mysql_null($data['text_description']);
            $text->language    = mysql_null($data['language']);
            $text->save();
        }

        self::resetInputFields();

        session()->flash('successText', trans('Text succesfully edited'));
        $this->dispatchBrowserEvent('hideEditText');
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Text::destroy($this->confirm);
        $this->confirm = '';
    }

    private function resetInputFields() {
        $this->text_name        = '';
        $this->text_title       = '';
        $this->text_description = '';
        $this->language         = '';
    }

}
