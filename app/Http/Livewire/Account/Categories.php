<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Models\Site;

class Categories extends Component {

	public $site_id = 11; //TODO: Remove hardcoded site id

	public function render() {
		$site = Site::findOrFail($this->site_id);

		return view('livewire.account.categories', ['site' => $site])->layout('layouts.customer');
	}

}
