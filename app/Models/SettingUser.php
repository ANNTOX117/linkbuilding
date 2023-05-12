<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingUser extends Model
{
    use HasFactory;

    protected $table = 'settings_user';

    public $timestamps = false;

    protected $fillable = [
        'value',
        'option',
        'user'
    ];

    public static function setting_by_user() {
        return SettingUser::where('user', auth()->user()->id)->get();
    }

    public static function invoice_details(){
        return SettingUser::select('value')->where('user', auth()->user()->id)->where('option', 1)->first();
    }

    public static function allow_reminders($user, $option) {
        return SettingUser::where('user', $user)->where('option', $option)->where('value', 1)->exists();
    }

}
