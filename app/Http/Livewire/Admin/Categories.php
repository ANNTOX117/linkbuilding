<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Language;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class Categories extends Component {

    use WithPagination;
    use WithFileUploads;

    public $title;
    public $section = 'categories';
    public $column  = 'categories.name';
    public $sort    = 'asc';
    public $confirm;
    public $category_id;
    public $name;
    public $language;
    public $languages;
    public $pagination;
    public $search = '';
    public $new_categories = [];
    public $csv;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'     => 'required',
        'language' => 'required|numeric'
    ];

    public function mount() {
        if(!permission('categories', 'read')) {
            abort(404);
        }

        $this->title      = trans('Categories');
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $categories = Category::with_filter($this->column, $this->sort, $this->pagination, $this->search);

        return view('livewire.admin.categories', compact('categories'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function modalAddCategory() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddCategory');
    }

    public function modalEditCategory($id) {
        $category = Category::find($id);

        if(!empty($category)) {
            $this->category_id = $category->id;
            $this->name        = $category->name;
            $this->language    = $category->language;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditCategory');
        }
    }

    public function addCategory() {
        $data = $this->validate([
            'name'     => 'required',
            'language' => 'required|numeric',
        ]);

        if(Category::already_exists($data['name'], $data['language'])) {
            $this->addError('name', trans('This category already exists on the database'));
            return false;
        }

        Category::create([
            'url'      => get_slug($data['name']),
            'name'     => mysql_null($data['name']),
            'language' => mysql_null($data['language'])
        ]);

        self::resetInputFields();

        session()->flash('successCategory', trans('Category succesfully created'));
        $this->dispatchBrowserEvent('hideAddCategory');
    }

    public function editCategory() {
        $data = $this->validate([
            'name'     => 'required',
            'language' => 'required|numeric',
        ]);

        if(Category::already_exists($data['name'], $data['language'], $this->category_id)) {
            $this->addError('name', trans('This category already exists on the database'));
            return false;
        }

        $category = Category::find($this->category_id);

        if(!empty($category)) {
            $category->url      = get_slug($data['name']);
            $category->name     = mysql_null($data['name']);
            $category->language = mysql_null($data['language']);
            $category->save();
        }

        self::resetInputFields();

        session()->flash('successCategory', trans('Category succesfully edited'));
        $this->dispatchBrowserEvent('hideEditCategory');
    }

    public function modalImport() {
        self::resetUpload();
        $this->dispatchBrowserEvent('showImport');
    }

    public function addImportCategory() {
        if(!empty($this->csv)) {
            $name      = 'categories'.date('YmdHis');
            $extension = pathinfo($this->csv->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/csv';
            $link      = 'storage/csv/'. $filename;
            $csv       = public_path($link);

            $this->csv->storeAs($path, $filename);

            $this->new_categories = $this->save_csv($csv);
            self::resetUpload();

            $this->dispatchBrowserEvent('hideImport');
        } else {
            $this->addError('csv', trans('You need to select a file'));
            return false;
        }
    }

    private function save_csv($csv) {
        $file  = fopen($csv, 'r');
        $lines = array();
        $index = 0;

        while(!feof($file)) {
            $line = fgetcsv($file, 0, ',');
            if(is_array($line)) {
                if(csv_header_category($line)) {
                    //is header
                } else {

                    $row = Category::check_category($line);

                    if ($row == null) {

                        $language = Language::by_name($line[1]);

                        if ($line[1] == '' || $language == null) {
                            $language_id = 1;
                        }
                        else{
                            $language_id = $language->id;
                        }

                        $lines[$index]['url']       = get_slug($line[0]);
                        $lines[$index]['name']      = $line[0];
                        $lines[$index]['language']  = $language_id;
                    }
                    $index++;
                }
            }
        }

        Category::upsert($lines, ['url'], ['name'], ['language']);

        fclose($file);

        if(File::exists($csv)) {
            File::delete($csv);
        }

        return $lines;
    }

    private function resetUpload() {
        $this->csv = '';
    }

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Category::destroy($this->confirm);
        $this->confirm   = '';
    }

    private function resetInputFields() {
        $this->name     = '';
        $this->language = '';
    }

}
