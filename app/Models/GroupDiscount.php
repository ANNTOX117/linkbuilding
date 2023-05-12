<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDiscount extends Model {

    use HasFactory;

    protected $table = 'group_discounts';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public static function all_items($sort = 'name', $order = 'asc') {
        return GroupDiscount::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc') {
        return GroupDiscount::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function update_name($id, $name) {
        return GroupDiscount::where('id', $id)->update(['name' => $name]);
    }

    public static function with_name($name) {
        return GroupDiscount::where('name', $name)->first();
    }

}
