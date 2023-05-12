<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use File;

class Validate extends Component{

	use WithFileUploads;
	
	public $menu;
	public $title;
	public $csv;
	public $table = [];

	public function mount(){
		$this->title  = trans('Validate');
		$this->menu   = 'Validate';
	}
	
	public function render(){
		return view('livewire.admin.validate')->layout('layouts.panel');
	}

	public function openImport(){
		
		if(!empty($this->csv)) {
			$name		= 'links'.date('YmdHis');
			$extension	= pathinfo($this->csv->getClientOriginalName(), PATHINFO_EXTENSION);
			$filename	= $name .'.'. $extension;
			$path		= 'public/csv';
			$link		= 'storage/csv/'. $filename;
			$csv		= public_path($link);
			$this->csv->storeAs($path, $filename);
			$this->save_csv($csv);
		} else {
			$this->dispatchBrowserEvent('showImport', ['message' => trans('You need to select a file')]);
		}
	}

	private function save_csv($csv) {

		set_time_limit(0);
		ini_set('max_execution_time', 0);
		
		$file  = fopen($csv, 'r');
		$lines = array();
		$index = 0;

		while(!feof($file)) {
			$line = fgetcsv($file, 0, ',');
			if(is_array($line)) {
				if(csv_header_validate($line)) {
				//is header
				} else {
					if ($line[1] != '') {

						if (is_valid_url($line[1])) {

							$jsonData = file_get_contents($line[1]);

							if ($jsonData != '') {

								$pos = strpos($jsonData, $line[2]);

								if ($pos === false) {
									$result = 0;
								} else {
									$result = 1;
								}	
							}
							else{
								$result = 2;

							}
						}
						else{
							$result = 3;
						}	
						
						$this->table[] = array(
												'id' => $line[0],
												'linkto' => $line[1], 
												'linkfrom' => $line[2],
												'result' => $result
											);
					}
					$index++;
				}	
			}
		}
		
		fclose($file);
		$this->resetErrorBag();
		if(File::exists($csv)) {
			File::delete($csv);
		}
	}
}