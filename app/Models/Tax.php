<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model {

    use HasFactory;

    protected $table = 'taxes';

    public $timestamps = false;

    protected $fillable = [
        'country',
        'tax'
    ];

    public function countries() {
        return $this->hasOne('App\Models\Country', 'id', 'country');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->where('countries.name', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'country', $order = 'asc') {
        return Tax::select('taxes.id', 'countries.name as country', 'taxes.tax')
                    ->join('countries', 'countries.id', '=', 'taxes.country')
                    ->orderBy($sort, $order)
                    ->get();
    }

    public static function with_pagination($sort = 'country', $order = 'asc', $pagination, $search = null) {
        return Tax::select('taxes.id', 'countries.name as country', 'taxes.tax')
            ->join('countries', 'countries.id', '=', 'taxes.country')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function countries_array() {
        return Tax::get()->pluck('country');
    }

    public static function already_exists($country) {
        return Tax::where('country', $country)->exists();
    }

    public static function by_country($country) {
        $taxes = Tax::where('country', $country)->first();
        return (!empty($taxes)) ? $taxes->tax : 0;
    }

}
