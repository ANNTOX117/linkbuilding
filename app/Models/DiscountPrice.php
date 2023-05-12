<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountPrice extends Model {

    use HasFactory;

    protected $table = 'discounts_price';

    public $timestamps = false;

    protected $fillable = [
        'percentage',
        'price'
    ];

    public static function all_items() {
        return DiscountPrice::all();
    }

    public static function list() {
        return DiscountPrice::orderBy('price', 'desc')->get();
    }

    public static function by_price($price) {
        return DiscountPrice::where('price', '<=', $price)->orderBy('price', 'desc')->first();
    }

}
