<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Country extends Model {

    use HasFactory;

    protected $table = 'countries';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name'
    ];

    public static function all_items($sort = 'name', $order = 'asc') {
        return Country::orderBy($sort, $order)->get();
    }

    public static function select_list($selected = 0) {
        if(intval($selected) > 0) {
            return Country::select('id', DB::raw('name as text'), DB::raw('IF(id = '. $selected .', true, false) as selected'))->orderBy('name', 'asc')->get();
        } else {
            return Country::select('id', DB::raw('name as text'))->orderBy('name', 'asc')->get();
        }
    }

    public static function get_id($name) {
        return Country::where('name', $name)->first()->id;
    }

}
