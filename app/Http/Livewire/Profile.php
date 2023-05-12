<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $country;
    protected $listeners = ['toggleModal' => 'toggle', 'open', 'closed', 'fileUpload'];
    public $name;
    public $first_name;
    public $last_name;
    public $role;
    public $address;
    public $postalcode;
    public $city;
    public $countrySelected;
    public $company;
    public $vatno;
    public $tab = "Personal";
    public $email;
    public $password;
    public $password_confirm;
    public $image;
    public $profile_image;
    public $profile_name;
    public $created;
    public $lastLogin;
    public $uploaded = false;
    public $countries;

    protected $rules = [
        'company' => 'min:3',
        'address' => 'required',
        'postalcode' => 'required|min:4',
        'first_name' => 'required',
        'last_name' => 'required',
        'city' => 'required',
        'country' => 'required',
        'email' => 'unique:users,id|required|email',
        'name' => 'required',
        'password' => 'nullable|min:6',
    ];

    public function mount(){
        $this -> tab = "Personal";
        $user = Auth::user();
        $this -> countrySelected = $user -> country;
        $this -> first_name =  $user -> name;
        $this -> name = $user -> name;
        $this -> last_name = $user -> lastname;
        $this -> role = $user -> role;
        $this -> address = $user -> address;
        $this -> postalcode = $user -> postal_code;
        $this -> city = $user -> city;
        $this -> company = $user -> company;
        $this -> vatno  = $user -> tax;
        $this -> email  = $user -> email;
        $this -> profile_image = $user -> profile_image;
        $param = (!empty($user -> country)) ? $user -> country : 0;
        $this -> countries = Country::all_items();

        if (!empty($this -> company)){
            $this -> profile_name = $this -> company;
        }
        else{
            $this -> profile_name = $this -> name;
        }

        $date = new \DateTime($user->created_at);
        $newCreate = $date->format('d M Y');

        $this -> created = $newCreate;

        $lastLogin  = new \DateTime($user->last_login);

        $lastLogin = $lastLogin->format('d M Y');

        $this -> lastLogin = $lastLogin;

        if (!empty(session('subuser'))){
            $userN = User::find(session('subuser')->id);
            $this -> profile_image  = $userN  -> profile_image;
            $this -> name =  $userN  -> name;
            $this -> email = $userN  -> email;
            $this -> profile_name = $userN -> first_name.' '.$userN -> last_name;


            $date = new \DateTime($userN ->created_at);
            $newCreate = $date->format('d M Y');

            $this -> created = $newCreate;

            $lastLogin  = new \DateTime($userN->last_login);
            $lastLogin = $lastLogin->format('d M Y');

            $this -> lastLogin = $lastLogin;
        }

    }

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }


    public function fileUpload($imageData){
        $this->image = $imageData;
        $this -> uploaded = false;
    }

    public function uploadImage(Request $request){
        $this->validate([
            'profile_image' => 'mimes:jpeg,jpg,png,gif|required'
        ]);
        $images = [
            'name' => $this->profile_image->getClientOriginalName(),
            'path' => $this->profile_image->getRealPath(),
            'extension' => $this->profile_image->getClientOriginalExtension(),
        ];
        $filenameSmall = substr(md5(microtime() . rand(0, 9999)), 0, 20) . '.' .  $images['extension'];
        $path = public_path('uploads/' . $filenameSmall);
        ImageManagerStatic::make( $images['path'])->orientate()->fit(200, 200, function ($constraint) {
            $constraint->upsize();

        },'top')->save($path);

        $user = User::find(Auth::user()->id);
        if (!empty(session('subuser'))){
            $user = User::find(session('subuser')->id);
        }

        if (!empty($user -> profile_image)){
            @unlink(public_path("uploads/".$user -> profile_image.""));
        }

        $user -> profile_image =  $filenameSmall;
        $user -> save();
        $this -> uploaded = true;
        $user = User::find(Auth::user()->id);
        if (!empty(session('subuser'))){
            $user = User::find(session('subuser')->id);
        }

        $this -> profile_image  = $user -> profile_image;
        $this -> emit('uploadedImage');
        session()->flash('successImage', __('Your profile image has been uploaded succesfully.'));
    }

    public function storePersonal(){
        if(!is_numeric($this->countrySelected)) {
            $this -> countrySelected = Country::get_id($this->countrySelected);
        }

        $this->validate([
            'company' => 'min:3',
            'address' => 'required',
            'postalcode' => 'required|min:4',
            'first_name' => 'required',
            'last_name' => 'required',
            'city' => 'required',
            'countrySelected' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user -> name =  $this -> first_name;
        $user -> lastname =  $this -> last_name;
        $user -> address =  $this -> address;
        $user -> postal_code = $this -> postalcode;
        $user -> city =  $this -> city;
        $user -> country = $this -> countrySelected;
        $user -> company =  $this -> company;
        $user -> tax  =  $this -> vatno;
        $user -> save();

        $this -> emit('changeName');
        session()->flash('success', __('Your personal details has been stored succesfully.'));
        $this->dispatchBrowserEvent('tabchange');
    }

    public function storeInlog(){
        if (!empty($this -> password) && empty($this -> password_confirm)){
            session()->flash('error', __('Please confirm your password.'));
            return Redirect::back();
            die();
        }
        if (!empty($this -> password) && !empty($this -> password_confirm)){
            if ($this -> password != $this -> password_confirm){
                session()->flash('error', __('Passwords doesnot match.'));
                return Redirect::back();
                die();
            }
        }

        $this->validate([
            'email' => 'unique:users,id|required|email',
            'name' => 'required',
            'password' => 'nullable|min:6',
        ]);
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user -> name = $this -> name;
        $user -> email = $this -> email;
        if (!empty($this -> password)){
            $user -> password = bcrypt($this -> password);
        }
        $user -> save();
        $this -> password = "";
        $this -> password_confirm = "";
        $this -> emit('changeName');
        session()->flash('success2', __('Your login details has been stored succesfully.'));
        $this->dispatchBrowserEvent('tabchange');
    }

    public function toggle()
    {
        if ($this->isOpen) {
            $this->dispatchBrowserEvent('open');
            $this->isOpen = false;
        } else {
            $this->dispatchBrowserEvent('closed');
            $this->isOpen = true;
        }
    }

    public function changeTab($value){
        //Personal or Login
        $this->dispatchBrowserEvent('tabchange');
        $this -> tab = $value;
    }

    public function render()
    {
        self::loadContries();
        return view('livewire.profile')->layout('layouts.panel');
    }

    private function loadContries() {
        $this -> countries = Country::all_items();
    }
}
