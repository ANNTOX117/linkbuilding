<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discount extends Model {

    use HasFactory;

    protected $table = 'discounts';

    public $timestamps = false;

    protected $fillable = [
        'from',
        'to',
        'percentage'
    ];

    public function groupdiscounts() {
        return $this->hasOne('App\Models\GroupDiscount', 'id', 'group');
    }

    public static function all_items($sort = 'from', $order = 'asc') {
        return Discount::orderBy($sort, $order)->get();
    }

    public static function get_values($group, $column) {
        return Discount::where('group', $group)->pluck($column);
    }

    public static function remove($group) {
        return Discount::where('group', $group)->delete();
    }

    //select percentage from discounts where `group` = 3 AND 25 BETWEEN `from` AND `to`;
    public static function get_percentage($group, $quantity) {
        return Discount::where('group', $group)->whereBetween(DB::raw('"'. $quantity .'"'), [DB::raw("`from`"), DB::raw("`to`")])->first();
    }

    public static function per_volume($total, $group = 'Default') {
        return Discount::select('discounts.*')
            ->leftJoin('group_discounts', function($join) use ($group){
                $join->on('group_discounts.id', '=', 'discounts.group');
                $join->on('group_discounts.name', '=', DB::raw('"'. $group .'"'));
            })
            ->where('discounts.from', '<=', $total)
            ->orderBy('discounts.from', 'desc')
            ->first();
    }


}
