<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Topbar extends Component {

    public $profile_image;
    public $profile_name;
    public $email;

    public function mount() {
        $user = User::find(Auth::user()->id);
        $this -> profile_image  = $user -> profile_image;
        $this -> name =  $user -> name;
        $this -> role = $user->role;
        if ($user->role == 'member'){
            $this -> role = "admin";
        }

        $this -> company = $user -> company;
        $this -> email = $user -> email;
        if (!empty($this -> company)){
            $this -> profile_name = $this -> company;
        }
        else{
            $this -> profile_name = $this -> name;
        }

    }

    public function render() {
        $user = Auth::user();

        return view('livewire.topbar', compact('user'));
    }

    public function uploadedImage(){
        $user = User::find(Auth::user()->id);
        $this -> profile_image  = $user -> profile_image;
    }

    public function changeName(){
        $user = User::find(Auth::user()->id);
        $this -> profile_name = $user -> name;
        if(!empty($user -> company)){
            $this -> profile_name = $user -> company;
        }
        $this -> email = $user -> email;
    }

}
