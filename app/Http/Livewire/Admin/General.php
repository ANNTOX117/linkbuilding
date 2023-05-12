<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Config;

class General extends Component {

    public $title;
    public $settings;
    public $edit;
    public $option;
    public $value;

    public function mount() {
        if(!permission('general', 'read')) {
            abort(404);
        }

        $this->title = trans('General');
    }

    public function render() {
        $this->settings = \App\Models\General::all();

        return view('livewire.admin.general')->layout('layouts.panel');
    }

    public function edit($id) {
        $this->edit = $id;

        $item = \App\Models\General::find($id);

        if(!empty($item)) {
            $this->option = $item->key;
            $this->value  = $item->value;
        }
    }

    public function save($id) {
        $data = $this->validate([
            'value' => 'nullable',
        ]);

        $item = \App\Models\General::find($this->edit);

        if(!empty($item)) {
            if($item->key == 'PAYPAL_SANDBOX_CLIENT_ID'){
                config(['paypal.sandbox.client_id' => $item->value]);
            }
            if($item->key == 'PAYPAL_SANDBOX_CLIENT_SECRET'){
                config(['paypal.sandbox.client_secret' => $item->value]);
            }
            $item->value = mysql_null($data['value']);
            $item->save();
        }
        self::resetInputFields();
    }

    public function cancel() {
        self::resetInputFields();
    }

    private function resetInputFields() {
        $this->edit   = '';
        $this->option = '';
        $this->value  = '';
    }

}
