<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model {

    use HasFactory;

    protected $table = 'members';

    public $timestamps = false;

    protected $fillable = [
        'group',
        'user'
    ];

    public static function cleanup($group) {
        return Member::where('group', $group)->delete();
    }

    public static function members_for_recipients($group) {
        return Member::where('group', $group)->get()->pluck('user');
    }
}
