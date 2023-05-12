<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use App\Models\Column;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Storage;

class Authority extends Component {

    use WithFileUploads;
    use WithPagination;

    public $title;
    public $section = 'authority';
    public $column  = 'url';
    public $sort    = 'asc';
    public $confirm;
    public $site_id;
    public $url;
    public $subnet;
    public $pa;
    public $da;
    public $tf;
    public $cf;
    public $dre;
    public $backlinks;
    public $refering_domains;
    public $price;
    public $price_special;
    public $edit_id;
    public $edit = false;
    public $csv;
    public $columns = [];
    public $status = [];

    public $pagination;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'site_id'           => 'required|numeric',
        'url'               => 'required|url',
        'subnet'            => 'nullable',
        'pa'                => 'nullable|numeric',
        'da'                => 'nullable|numeric',
        'tf'                => 'nullable|numeric',
        'cf'                => 'nullable|numeric',
        'dre'               => 'nullable|numeric',
        'backlinks'         => 'nullable',
        'refering_domains'  => 'nullable|numeric',
        'price'             => 'nullable|numeric',
        'price_special'     => 'nullable|numeric',
        'csv'               => 'mimes:csv,txt|nullable|max:10000'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedColumns($columns) {
        Column::update_values('authority_sites', $this->columns);
    }

    public function toggleColumns() {
        //
    }

    public function changeStatus($site) {
        AuthoritySite::featured($site, $this->status[$site]);
    }

    public function mount() {
        if(!permission('authorities', 'read')) {
            abort(404);
        }

        $this->title      = trans('Authority sites');
        $this->pagination = env('APP_PAGINATE');

        self::loadColumns();
    }

    public function render() {
        $sites = AuthoritySite::with_filter($this->column, $this->sort, $this->pagination, $this->search);

        if(!empty($sites)) {
            foreach($sites as $item) {
                $this->status[$item->id] = $item->featured === 1;
            }
        }

        return view('livewire.admin.authority', compact('sites'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function editRow($id) {
        $this->edit_id = $id;
        $this->edit    = true;

        $site = AuthoritySite::find($id);

        if(!empty($site)) {
            $this->site_id          = $site->id;
            $this->url              = $site->url;
            $this->subnet           = $site->subnet;
            $this->pa               = $site->pa;
            $this->da               = $site->da;
            $this->tf               = $site->tf;
            $this->cf               = $site->cf;
            $this->dre              = $site->dre;
            $this->backlinks        = $site->backlinks;
            $this->refering_domains = $site->refering_domains;
            $this->price            = $site->price;
            $this->price_special    = $site->price_special;

            $this->dispatchBrowserEvent('resetTags', ['tags' => $this->backlinks]);
        }
    }

    public function saveRow($id) {
        $site = AuthoritySite::find($id);

        if(!empty($site)) {
            $site->subnet           = mysql_null($this->subnet);
            $site->pa               = mysql_null($this->pa);
            $site->da               = mysql_null($this->da);
            $site->tf               = mysql_null($this->tf);
            $site->cf               = mysql_null($this->cf);
            $site->dre              = mysql_null($this->dre);
            $site->backlinks        = mysql_null($this->backlinks);
            $site->refering_domains = mysql_null($this->refering_domains);
            $site->price            = mysql_null($this->price);
            $site->price_special    = mysql_null($this->price_special);
            $site->save();
        }

        self::resetInputFields();
    }

    public function cancelRow() {
        self::resetInputFields();
    }

    public function modalImport() {
        self::resetUpload();
        $this->dispatchBrowserEvent('showImport');
    }

    public function importCSV() {
        if(!empty($this->csv)) {
            $name      = date('YmdHis');
            $extension = pathinfo($this->csv->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/csv';
            $link      = 'storage/csv/'.$filename;
            $csv       = public_path($link);

            $this->csv->storeAs($path, $filename);
            self::save_csv($csv);
            self::resetUpload();

            $this->dispatchBrowserEvent('hideImport');
        } else {
            $this->addError('csv', trans('You need to select a file'));
            return false;
        }
    }

    public function export() {
        $this->dispatchBrowserEvent('doDownload');
    }

    private function loadColumns() {
        $this->columns = Column::get_values('authority_sites');
    }

    private function save_csv($csv) {

        $file  = fopen($csv, 'r');
        $lines = array();
        $index = 0;

        while(!feof($file)) {
            $line = fgetcsv($file, 0, ',');

            if(is_array($line)) {
                if(csv_header_site($line)) {
                    //is header
                } else {

                    $url = remove_http($line[0]);
                    $url = str_ireplace('www.', '', $url);

                    $lines[$index]['id']               = AuthoritySite::get_id_by_url($url);
                    $lines[$index]['url']              = $line[0];
                    $lines[$index]['subnet']           = $line[1];
                    $lines[$index]['pa']               = ($line[2] < 0) ? '0' : $line[2];
                    $lines[$index]['da']               = ($line[3] < 0) ? '0' : $line[3];
                    $lines[$index]['tf']               = ($line[4] < 0) ? '0' : $line[4];
                    $lines[$index]['cf']               = ($line[5] < 0) ? '0' : $line[5];
                    $lines[$index]['dre']              = ($line[6] < 0) ? '0' : $line[6];
                    $lines[$index]['backlinks']        = mysql_null($line[7]);
                    $lines[$index]['refering_domains'] = ($line[8] < 0) ? '0' : $line[8];
                    $lines[$index]['type']             = is_subdomain($line[0]);
                    $lines[$index]['price']            = mysql_null($line[9]);
                    $lines[$index]['price_special']    = mysql_null($line[10]);

                    $index++;
                }
            }
        }

        fclose($file);

        // Remove duplicated urls
        $duplicated = array();

        foreach($lines as $i => $line) {
            $url = remove_http($line['url']);
            $url = str_ireplace('www.', '', $url);

            if(!in_array($url, $duplicated)) {
                array_push($duplicated, $url);
            } else {
                unset($lines[$i]);
            }
        }

        /*foreach($lines as $i => $line) {
            if(!AuthoritySite::doesnt_exist($line['url'])) {
                unset($lines[$i]);
            }
        }*/

        DB::table('authority_sites')->upsert($lines, ['id'], ['url', 'subnet', 'pa', 'da', 'tf', 'cf', 'dre', 'backlinks', 'refering_domains', 'type', 'price', 'price_special']);

        if(File::exists($csv)) {
            File::delete($csv);
        }
    }

    private function resetInputFields() {
        $this->edit_id          = '';
        $this->edit             = false;
        $this->site_id          = '';
        $this->url              = '';
        $this->subnet           = '';
        $this->pa               = '';
        $this->da               = '';
        $this->tf               = '';
        $this->cf               = '';
        $this->dre              = '';
        $this->backlinks        = '';
        $this->refering_domains = '';
        $this->price            = '';
        $this->price_special    = '';
    }

    private function resetUpload() {
        $this->csv = '';
    }

}
