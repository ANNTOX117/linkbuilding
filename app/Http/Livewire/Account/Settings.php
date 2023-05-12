<?php

namespace App\Http\Livewire\Account;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use App\Models\Setting;
use App\Models\SettingUser;


class Settings extends Component
{
	public $menu;
	public $title;
	public $checked = [];
	public $options = [];
	
	public function mount(){
		
		$this->title  = trans('Settings');
		$this->menu   = 'Settings';
		$this->options = Setting::all();
		$mysettings = SettingUser::setting_by_user();

		if (empty($mysettings->toArray())) {

			foreach ($this->options as $index => $option) {
				$value = 1;
				SettingUser::updateOrCreate(
					['user' => Auth::user()->id , 'option' => $option->id],
					['value' => $value]
				);

				$this->checked[$option->id] = $value;
			}
		}
		else{
			
			foreach ($this->options as $option) {
				if (!empty($mysettings)) {
					foreach ($mysettings as $setting) {
						if ($option->id == $setting->option) {
							$value = ($setting->value == 1) ? true : false;
							$this->checked[$option->id] = $value;
						}
					}
				}
				else{
					$this->checked[$option->id] = false;
				}
			}
		}			
	}

	public function updatedchecked($value, $index)
	{
		$this->checked[$index] = $value;
	}
	
	public function render() {
		return view('livewire.account.settings')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
	}

	public function updatesettingsuser(){
		foreach ($this->checked as $index => $option) {
			$value = ($option == true) ? 1 : 0;
			SettingUser::updateOrCreate(
				['user' => Auth::user()->id , 'option' => $index],
				['value' => $value]
			);
		}
		session()->flash('successupdate', __('Your settings have been saved successfully.'));
	}
}
