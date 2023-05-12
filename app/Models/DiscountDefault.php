<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountDefault extends Model {

    use HasFactory;

    protected $table = 'discounts_default';

    public $timestamps = false;

    protected $fillable = [
        'percentage',
        'years'
    ];

    public static function all_items() {
        return DiscountDefault::all();
    }

    public static function list() {
        return DiscountDefault::orderBy('years', 'desc')->get();
    }

    public static function by_years($years) {
        return DiscountDefault::where('years', $years)->first();
    }

}
