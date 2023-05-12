<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component {

    public $profile_name;
    public $name;
    public $email;
    public $role;
    public $company;
    public $profile_image;
    protected $listeners = ['uploadedImage', 'changeName'];

    public function mount(){
        $user = User::find(Auth::user()->id);
        $this -> profile_image  = $user -> profile_image;
        $this -> name =  $user -> name;
        $this -> company = $user -> company;
        $this -> email = $user -> email;
        if ($user -> role == 'member'){
            $user -> role = 'owner';
        }

        $this -> role = $user -> role;
        if (!empty($this -> company)){
            $this -> profile_name = $this -> company;
        }
        else{
            $this -> profile_name = $this -> name;
        }
    }

    public function uploadedImage(){
        $user = User::find(Auth::user()->id);
        $this -> profile_image  = $user -> profile_image;
    }

    public function changeName() {
        $user = User::find(Auth::user()->id);
        $this -> profile_name = $user -> name;
        if(!empty($user -> company)){
            $this -> profile_name = $user -> company;
        }
        $this -> email = $user -> email;
    }

    public function render() {
        return view('livewire.sidebar');
    }

}
