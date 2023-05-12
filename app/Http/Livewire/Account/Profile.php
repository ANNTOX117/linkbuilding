<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;
use Intervention\Image\Facades\Image;
use App\Models\User;
use App\Models\Country;
use App\Models\Language;
use App\Models\MailingText;
use File;

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
	public $menu;
	public $title;
	public $languages;
    public $language;
	public $kvk_number;
	public $tax;
    public $type;
    public $is_company = false;

	public function mount(){

		$this->tab = "Personal";
		$this->title  = trans('Profile');
		$this->menu   = 'Profile';

		$user = Auth::user();

		$this->countrySelected = $user->country;
		$this->first_name =  $user->name;
		$this->name = $user->name;
		$this->last_name = $user->lastname;
		$this->role = $user->role;
		$this->address = $user->address;
		$this->postalcode = $user->postal_code;
		$this->city = $user->city;

		$this->kvk_number = $user->kvk_number;
		$this->tax =  $user->tax;

		$this->company = $user->company;
		$this->vatno  = $user->tax;
		$this->email  = $user->email;
		$this->profile_image = $user->profile_image;
		$this->created = $user->created_at;
		$this->lastLogin = $user->last_login;
		$this->language  = $user->language;
		$this->countries = Country::all_items();
        $this->languages = Language::all();
        $this->type       = $user->type;
        $this->is_company = ($user->type == 'company');
	}

	public function changeTab($value){
		$this->tab = $value;
	}

	public function fileUpload($imageData){
		$this->image = $imageData;
		$this->uploaded = false;
		$this->resetErrorBag();
	}

	public function uploadImage(){
		$this->validate([
			'profile_image' => 'mimes:jpeg,jpg,png,gif|required|max:2048'
		]);

		$images = [
			'name' => $this->profile_image->getClientOriginalName(),
			'path' => $this->profile_image->getRealPath(),
			'extension' => $this->profile_image->getClientOriginalExtension(),
		];

		$filenameSmall = substr(md5(microtime().rand(0, 9999)), 0, 20).'.'.$images['extension'];
		
		$path = public_path('storage/profile/'.$filenameSmall);

		$img = Image::make($images['path']);
		$img->resize(200,200);
		$img->save($path);

		// ImageManagerStatic::make($images['path'])->orientate()->fit(200, 200, function ($constraint) {
		// 	$constraint->upsize();
		// },'top')->save($path);

		/*Image::make($image->getRealPath())->resize(468, 249)->save('public/img/products'.$filename);*/

		$user = User::find(Auth::user()->id);

		$image_prev = $user->profile_image;
		$user->profile_image = $filenameSmall;

		if ($user->save()) {

			$this->uploaded = true;
			$this->profile_image  = $user->profile_image;
			session()->flash('successImage', __('Your profile image has been uploaded succesfully.'));
			$this->resetErrorBag();

			if(File::exists(public_path()."/storage/profile/".$image_prev)) {
				File::delete(public_path()."/storage/profile/".$image_prev);
			}
		}
	}

	public function storePersonal(){

		if(!is_numeric($this->countrySelected)) {
			$this->countrySelected = Country::get_id($this->countrySelected);
		}

        if($this->type == 'company') {
            $this->validate([
                'company' => 'required|min:3',
                'address' => 'required',
                'postalcode' => 'required|min:4',
                'first_name' => 'required',
                'last_name' => 'required',
                'city' => 'required',
                'countrySelected' => 'required',
                'language' => 'nullable',
                'type' => 'required',
            ]);
        } else {
            $this->validate([
                'company' => 'min:3',
                'address' => 'required',
                'postalcode' => 'required|min:4',
                'first_name' => 'required',
                'last_name' => 'required',
                'city' => 'required',
                'countrySelected' => 'required',
                'language' => 'nullable',
                'type' => 'required',
            ]);
        }

		$user_id = Auth::user()->id;
		$user = User::find($user_id);
		$user->name =  $this->first_name;
		$user->lastname =  $this->last_name;
		$user->address =  $this->address;
		$user->postal_code = $this->postalcode;
		$user->city =  $this->city;
		$user->country = $this->countrySelected;
		$user->company =  $this->company;
        $user->language = mysql_null($this->language);
        $user->type = $this->type;

        $home = '/profile';
        App::setLocale('nl');

        if(!empty($this->language) and !empty($user->language)) {
            if($user->languages->name != 'nl') {
                $home = '/' . $user->languages->name . '/profile';
                App::setLocale($user->languages->name);
            }
        }

		if ($user->save()) {
            $this->is_company = ($user->type == 'company');
			$this->emit('changeName');
			session()->flash('success', __('Your personal details has been stored succesfully.'));
			$this->dispatchBrowserEvent('tabchange');
			$this->resetErrorBag();
		}

        return redirect()->intended($home);
	}

    public function storeCompany(){

        $this->validate([
            'vatno' => 'nullable',
            'language' => 'nullable',
        ]);

        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->tax  =  mysql_null($this->vatno);
        $user->kvk_number = mysql_null($this->kvk_number);

        if ($user->save()) {
            $this->emit('changeName');
            session()->flash('success3', __('Your company details has been stored succesfully.'));
            $this->dispatchBrowserEvent('tabchange');
            $this->resetErrorBag();
        }
    }

	public function storeInlog(){

		if (!empty($this->password) && empty($this->password_confirm)){
			session()->flash('error', __('Please confirm your password.'));
			return Redirect::back();
			die();
		}
		if (!empty($this->password) && !empty($this->password_confirm)){
			if ($this->password != $this->password_confirm){
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

        if(User::email_already_exists($this->email, auth()->id())) {
            $this->addError('email', trans('Email is already in use'));
            return false;
        }

		$user_id = Auth::user()->id;
		$user = User::find($user_id);
		$user->name = $this->name;
		$user->email = $this->email;

		if (!empty($this->password)){
		    $user->password = bcrypt($this->password);
		}

		if ($user->save()) {
            self::send_email($user->id);

			$this->password = "";
			$this->password_confirm = "";
            $this->emit('changeName');
			session()->flash('success2', __('Your login details has been stored succesfully.'));
			$this->dispatchBrowserEvent('tabchange');
			$this->resetErrorBag();
		}
	}

	public function render() {
		return view('livewire.account.profile')->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
	}

	private function send_email($user) {
	    $template = MailingText::template('Password', App::getLocale());

        if(!empty($template)) {
            $user    = User::find($user);
            $subject = replace_variables($template->name, $user->id);
            $content = replace_variables($template->description, $user->id);
            $name    = $user->name . ' ' . $user->lastname;
            $email   = $user->email;

            Mail::send('mails.template', ['content' => $content, 'align' => 'center', 'password' => $this->password, 'email' => $this->email, 'link' => env('APP_URL').'/login' , 'link_text' => 'Log in' ], function ($mail) use ($email, $name, $subject) {
                $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                $mail->to($email, $name)->subject($subject);
            });
        }
    }
}
