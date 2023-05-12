<?php

namespace App\Models;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail {

    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'lastname',
        'company',
        'email',
        'password',
        'city',
        'country',
        'kvk_number',
        'tax',
        'postal_code',
        'role',
        'email_verified_at',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        //'profile_photo_url',
    ];

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification() {
        $this->notify(new EmailVerificationNotification());
    }

    public function roles() {
        return $this->hasOne('App\Models\Role', 'id', 'role');
    }

    public function countries() {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function authorities()
    {
        return $this->belongsToMany('App\Models\AuthoritySite', 'authority_user', 'user','authority');
    }

    public function sites()
    {
        return $this->belongsToMany('App\Models\Site', 'site_user', 'user','site');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftjoin('roles', 'roles.id', '=', 'users.role')
            ->leftjoin('countries', 'countries.id', '=', 'users.country')
             ->where('users.name', 'like', '%'.$val.'%')
            ->orWhere('users.lastname', 'like', '%'.$val.'%')
            ->orWhere('users.credit', 'like', '%'.$val.'%')
            ->orWhere('users.email', 'like', '%'.$val.'%')
            ->orWhere('roles.description', 'like', '%'.$val.'%')
            ->orWhere('countries.name', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'users.name', $order = 'users.asc') {
        return User::where('id', '!=', Auth::user()->id)->orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return User::select('users.*')
            ->where('users.id', '!=', Auth::user()->id)
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function get_writers() {
        return User::where('role', 3)->orderBy('name', 'asc')->get();
    }

    public static function get_customers() {
        return User::where('role', 4)->orderBy('name', 'asc')->get();
    }

    public static function select_writers($selected = 0) {
        if(intval($selected) > 0) {
            return User::select('id', DB::raw('CONCAT(name, \' \', lastname) as text'), DB::raw('IF(id = '. $selected .', true, false) as selected'))->where('role', 3)->orderBy('name', 'asc')->get();
        } else {
            return User::select('id', DB::raw('CONCAT(name, \' \', lastname) as text'))->where('role', 3)->orderBy('name', 'asc')->get();
        }
    }

    public static function select_customers() {
        return User::select('id', DB::raw('CONCAT(name, \' \', lastname) as text'))->where('role', 4)->orderBy('name', 'asc')->get();
    }

    public static function select_filtered_for_newsletter() {
        return User::select('users.id', DB::raw('CONCAT(name, \' \', lastname) as text'))
            ->leftJoin('settings_user', function($join) {
                $join->on('settings_user.user', '=', 'users.id');
                $join->on('settings_user.option', '=', DB::raw('2'));
            })
            ->where('users.role', 4)
            ->whereRaw('(settings_user.value is null or settings_user.value != 1)')
            ->orderBy('text', 'asc')
            ->get();
    }

    public static function select_filtered_for_newsletter_to_array() {
        return User::select('users.id', DB::raw('CONCAT(name, \' \', lastname) as text'))
            ->leftJoin('settings_user', function($join) {
                $join->on('settings_user.user', '=', 'users.id');
                $join->on('settings_user.option', '=', DB::raw('2'));
            })
            ->where('users.role', 4)
            ->whereRaw('(settings_user.value is null or settings_user.value != 1)')
            ->orderBy('text', 'asc')
            ->get()
            ->pluck('id');
    }

    public static function select_filtered_for_promotions() {
        return User::select('users.id', DB::raw('CONCAT(name, \' \', lastname) as text'))
            ->leftJoin('settings_user', function($join) {
                $join->on('settings_user.user', '=', 'users.id');
                $join->on('settings_user.option', '=', DB::raw('3'));
            })
            ->where('users.role', 4)
            ->whereRaw('(settings_user.value is null or settings_user.value != 1)')
            ->orderBy('text', 'asc')
            ->get();
    }

    public static function selected_on_group($group) {
        return User::select('users.id', DB::raw('CONCAT(name, \' \', lastname) as text'), DB::raw('IF(members.user, true, false) as selected'))
                    ->leftJoin('members', function($join) use ($group) {
                        $join->on('members.user', '=', 'users.id');
                        $join->on('members.group','=', DB::raw('"'. $group .'"'));
                    })
                    ->orderBy('name', 'asc')
                    ->get();
    }

    public static function payments_are_validated($user = null) {
        if(empty($user)) {
            $user = Auth::user()->id;
        }

        return User::where('id', $user)->whereNotNull('payments_verified_at')->exists();
    }

    public static function already_have_code($user = null) {
        if(empty($user)) {
            $user = Auth::user()->id;
        }

        return User::where('id', $user)->whereNotNull('code')->exists();
    }

    public static function set_code($user = null) {
        if(empty($user)) {
            $user = Auth::user()->id;
        }

        $code = get_code();
        User::where('id', $user)->update(['code' => $code]);
        return $code;
    }

    public static function clean_code($user = null) {
        if(empty($user)) {
            $user = Auth::user()->id;
        }

        return User::where('id', $user)->update(['code' => null]);
    }

    public static function verify_payments($user = null) {
        if(empty($user)) {
            $user = Auth::user()->id;
        }

        return User::where('id', $user)->update(['payments_verified_at' => Carbon::now()]);
    }


    public static function my_liks_expired_mail() {

        /*return User::select('users.id', 'users.email')
            ->join('links','users.id','links.client')
            ->join('external_links','external_links.links','links.id')
            ->where('external_links.active', 3)->whereDate('external_links.ends_at', '<' , Carbon::today())
            ->groupBy('users.id')
            ->get();*/
    }

    public static function emailsettings($id, $option){

        return User::select('settings_user.value')
                ->leftJoin('settings_user', 'users.id', 'settings_user.user')
                ->where('users.id', $id)
                ->where('settings_user.option', $option)
                ->first()->value;
    }

    public function scopeLinkexpire($query) {
        return $query->select('users.id', 'users.email')
            ->join('links','users.id','links.client')
            ->join('external_links','external_links.links','links.id')
            ->where(['external_links.type' => 1])
            ->where(['external_links.active' => 3])
            ->whereDate('external_links.ends_at', '<' , Carbon::today())
            ->groupBy('users.id');
    }

    public static function count_last_users($days = 3) {
        return User::whereNotNull('email_verified_at')
            ->whereRaw('email_verified_at BETWEEN NOW() - INTERVAL '. $days .' DAY AND NOW()')
            ->get()
            ->count();
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'client');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'client');
    }

    public static function email_already_exists($email, $user) {
        return User::where('email', $email)->where('id', '!=', $user)->exists();
    }

    public static function invoice()
    {
        $users = User::select('name','lastname','company','postal_code','address', 'city', 'country')->where('id', Auth::user()->id)->first()->toArray();
        foreach ($users as $user) {
            if ($user == '' || $user == null) {
                return false;
            }
        }
        return true;
    }

    public static function set_last_login() {
        return User::where('id', auth()->id())->update(['last_login' => Carbon::now()]);
    }

    public static function members_for_recipients($role) {
        return User::where('role', $role)->get()->pluck('id');
    }

    public static function get_credits() {
        $credits = User::where('id', auth()->id())->first();
        return (floatval($credits->credit)) ? $credits->credit : 0;
    }

    public static function deduct_credits($total) {
        $credits = User::where('id', auth()->id())->first();
        $credits = (floatval($credits->credit)) ? $credits->credit : 0;
        $price   = floatval($credits) - floatval($total);
        return User::where('id', auth()->id())->update(['credit' => $price]);
    }

    public static function set_free_coins($user, $coins) {
        return User::where('id', $user)->update(['credit' => $coins]);
    }

    public static function already_exists($email, $id = null) {
        if(!empty($id)) {
            return User::where('id', '!=', $id)->where('email', $email)->exists();
        }

        return User::where('email', $email)->exists();
    }

}
