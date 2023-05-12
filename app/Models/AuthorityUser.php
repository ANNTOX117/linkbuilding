<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorityUser extends Model
{
    use HasFactory;

    protected $table = 'authority_user';

    public $timestamps = false;

    protected $fillable = [
        'authority',
        'user'
    ];


    public static function getAuthority($authorities = [], $option = false){
        if (is_array($authorities)) {
            if ($option) {
                return AuthorityUser::whereIn('authority',$authorities)->delete();
            }
            return AuthorityUser::select(\DB::raw('distinct users.*'))
            ->join('users', 'authority_user.user', 'users.id')
            ->whereIn('authority',$authorities)->get()->pluck('id')->toArray();
        }
        return $authorities;
    }

    public function users() {
        return $this->hasMany('App\Models\User', 'id', 'user');
    }
}
