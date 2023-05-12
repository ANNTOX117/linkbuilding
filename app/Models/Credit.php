<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model {

    use HasFactory;

    protected $table = 'credits';

    public $timestamps = false;

    protected $fillable = [
        'coins',
        'user'
    ];


    public static function getCredit(){
        return Credit::where('user', auth()->user()->id)->get()->sum('coins');
    }
}
