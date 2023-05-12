<?php

namespace App\Http\Livewire\Account;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use App\Http\Livewire\Field;

use App\Models\SiteCategoryChild;
use App\Models\AuthoritySite;
use App\Models\Wordpress;
use App\Models\ArticleRule;
use App\Models\ArticleImage;
use App\Models\Article;
use App\Models\User;
use App\Models\Externallink;
use App\Models\Category;
use App\Models\Link;
use App\Models\Cart;
use App\Models\General;

use Carbon\Carbon;
use File;

class Addbulk extends Component{

	use WithFileUploads;
	use WithPagination;

	public $sortBy = 'url';
	public $sortDirection = 'asc';
	public $perPage = 10;
	public $search = '';

	public $status_form_startingpage = '';
	public $message_status_startingpage = '';

	public $accord_selected;
	public $inputs = [];
	public $validator = [];
	public $csv;

	protected $listeners = [
		'dateStartingpage','changestartingpage', 'save_csv'
	];

	public function mount() {

		$this->inputs[] = array(
			'site_startingpage'		=> '',
			'section_startingpage'	=> '',
			'starting_url'			=> '',
			'starting_follow'		=> 1,
			'starting_blank'		=> '',
			'starting_anchor'		=> '',
			'starting_title'		=> '',
			'expired_startingpage'	=> '',
			'yearstartingpage'		=> '',
			'starting_url_preview'	=> '',
			'status'				=> 'empty'
		);

		$this->title = trans('Add bulk');
		$this->tab = 'startpage';
		$this->menu = trans('addbulk');
		$this->invoce = User::invoice();
		$this->startpage_link_list		= AuthoritySite::selectType('all')->get();
	}

	public function render(){
		return view('livewire.account.addbulk')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
	}

	public function addStartingpage(){

		foreach ($this->inputs as $key => $item) {
			$validator = Validator::make($this->inputs[$key], [
				'site_startingpage'     => 'required|numeric',
				'section_startingpage'  => 'required|numeric',
				'starting_url'          => 'required',
				'starting_follow'       => 'required',
				'starting_blank'        => 'nullable',
				'starting_anchor'       => 'required|max:255',
				'starting_title'        => 'nullable',
				'expired_startingpage'  => 'nullable|date_format:d/m/Y',
				'yearstartingpage'      => 'required|numeric',
			]);

			if ($validator->fails()) {
				$this->validator[$key] = $validator->messages()->toArray();
				$this->inputs[$key]['status'] = 'fail';
				$this->status_form_startingpage = 'fail';
				$this->message_status_startingpage = trans('There are unfilled fields');
			}
			else{
				$this->inputs[$key]['status'] = 'correct';
				unset($this->validator[$key]);
				$this->resetErrorBag();
			}
		}

		if (empty($this->validator)) {

			$this->resetErrorBag();

			foreach ($this->inputs as $key => $value) {

				$details   = array();
				$authority = AuthoritySite::find($this->inputs[$key]['site_startingpage']);
				$price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

				$details['authority'] = mysql_null($this->inputs[$key]['site_startingpage']);
				$details['site']      = (!empty($authority)) ? $authority->site : null;
				$details['category']  = mysql_null($this->inputs[$key]['section_startingpage']);
				$details['anchor']    = mysql_null($this->inputs[$key]['starting_anchor']);
				$details['title']     = mysql_null($this->inputs[$key]['starting_title']);
				$details['url']       = mysql_null(prefix_http($this->inputs[$key]['starting_url']));
				$details['follow']    = ($this->inputs[$key]['starting_follow'] == 'rel="follow"' or $this->inputs[$key]['starting_follow'] == 1) ? 'follow' : 'nofollow';
				$details['blank']     = ($this->inputs[$key]['starting_blank'] == '_blank') ? 1 : null;
				$details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
				$details['years']     = mysql_null($this->inputs[$key]['yearstartingpage']);

				$data[] = array(
					'item' => 'startpage link',
					'identifier' => $this->inputs[$key]['site_startingpage'],
					'details' => json_encode($details),
					'price' => $price,
					'user'	 => Auth::user()->id
				);
			}

			\DB::table('cart')->upsert($data, 'id');
			$this->status_form_startingpage = 'success';
			$this->message_status_startingpage = trans('Your links have been saved correctly ');
			self::resetStartingpageInput();
			$this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
			$this->emitTo('cart.link', '$refresh');
		}
	}

	private function resetStartingpageInput(){
		$this->inputs = [];
		$this->inputs[] = array(
			'site_startingpage'		=> '',
			'section_startingpage'	=> '',
			'starting_url'			=> '',
			'starting_follow'		=> '1',
			'starting_blank'		=> '',
			'starting_anchor'		=> '',
			'starting_title'		=> '',
			'expired_startingpage'	=> '',
			'yearstartingpage'		=> '',
			'starting_url_preview'	=> '',
			'status'				=> 'empty'
		);
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function changestartingpage($data){
		if(!empty($data['value']) AND !empty($data['id'])) {
			$this->inputs[$data['key']]['site_startingpage'] = $data['value'];
			$categories = AuthoritySite::category_by_authority_site($data['value']);
			$this->dispatchBrowserEvent('updateList', [
				'categories' => $categories,
				'item' => $data['id']
			]);
		}
	}

	public function openImport(){

		if(!empty($this->csv)) {
            $name      = 'links'.date('YmdHis');
            $extension = pathinfo($this->csv->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/csv';
            $link      = 'storage/csv/'. $filename;
            $csv       = public_path($link);
            $this->csv->storeAs($path, $filename);
            $this->save_csv($link);
        } else {
            $this->dispatchBrowserEvent('showImport', ['message' => trans('You need to select a file')]);
        }
	}

	private function save_csv($csv) {
		$file  = fopen(public_path($csv), 'r');
		$lines = array();
		$index = 0;
		$aux = '';
		$this->inputs =[];

		while(!feof($file)) {
			$line = fgetcsv($file, 0, ',');
			if(is_array($line)) {
				if(csv_header_bulk($line)) {
				//is header
				} else {
					$aux = $aux .' -> '. $index. ' ** '.!empty($this->inputs[$index]). ' '.count($this->inputs);
						$this->addRowStartingpage();
						$aux= $aux .' llamo add ';
						$this->inputs[$index]['starting_anchor']    = mysql_null($line[1]);
						$this->inputs[$index]['starting_title']     = mysql_null($line[3]);
						$this->inputs[$index]['starting_url']       = mysql_null(prefix_http($line[0]));
						$this->inputs[$index]['starting_follow']    = ($line[2] == 'follow' or $line[2] == 1) ? 1 : 2;
						$this->inputs[$index]['starting_blank']     = ($line[4] == '_blank') ? '_blank' : null;
						$index++;
					// }
						// if (!empty($this->inputs[$index])) {
						// 	$this->inputs[$index]['starting_anchor']    = mysql_null($line[1]);
						// 	$this->inputs[$index]['starting_title']     = mysql_null($line[3]);
						// 	$this->inputs[$index]['starting_url']       = mysql_null(prefix_http($line[0]));
						// 	$this->inputs[$index]['starting_follow']    = ($line[2] == 'follow' or $line[2] == 1) ? 1 : 2;
						// 	$this->inputs[$index]['starting_blank']     = ($line[4] == '_blank') ? '_blank' : null;
						// }

				}
			}
		}
		
		fclose($file);
		$this->resetErrorBag();
		if(File::exists($csv)) {
			File::delete($csv);
		}
	}

	public function dateStartingpage($key, $date){
		$this->inputs[$key]['expired_startingpage'] = $date;
	}

	public function updatingSearch(){
		$this->resetPage();
	}

	public function addRowStartingpage(){

		$this->inputs[] = array(
			'site_startingpage'		=> '',
			'section_startingpage'	=> '',
			'starting_url'			=> '',
			'starting_follow'		=> '1',
			'starting_blank'		=> '',
			'starting_anchor'		=> '',
			'starting_title'		=> '',
			'expired_startingpage'	=> '',
			'yearstartingpage'		=> '',
			'starting_url_preview'	=> '',
			'status'				=> 'empty'
		);

		$this->dispatchBrowserEvent('updateDatepicker');
	}

	public function selectAccordion($id){
		$this->accord_selected = $id;
	}
}
