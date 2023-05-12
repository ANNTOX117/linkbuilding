<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PackageCategory extends Model {

    use HasFactory;

    protected $table = 'packages_categories';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name'
    ];

    public static function all_items($sort = 'name', $order = 'asc') {
        return PackageCategory::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc') {
        return PackageCategory::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return PackageCategory::whereNotNull('name')
            ->filter($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilter($query, $val) {
        return $query->Where('name', 'LIKE', '%'.$val.'%');
    }

    public static function already_exists($name) {
        return PackageCategory::where('name', $name)->exists();
    }

    public static function name($category) {
        return PackageCategory::find($category)->name;
    }

    public static function availables() {
        return PackageCategory::select('packages_categories.*', DB::raw('null as packages'))
                                ->join('packages', 'packages.category', '=', 'packages_categories.id')
                                ->groupBy('packages_categories.id')
                                ->get();
    }

    public static function packages($category, $sort = 'name', $order = 'asc') {
        return PackageCategory::select('packages.id', 'packages.name', 'packages_categories.name as category', DB::raw('count(authority_sites.id) as sites'), DB::raw('avg(authority_sites.pa) as pa'), DB::raw('avg(authority_sites.da) as da'), DB::raw('avg(authority_sites.tf) as tf'), DB::raw('avg(authority_sites.cf) as cf'), DB::raw('avg(authority_sites.dre) as dre'), DB::raw('sum(authority_sites.price) as total'), 'packages.price')
                                ->join('packages', 'packages.category', '=', 'packages_categories.id')
                                ->join('packages_sites', 'packages_sites.package', '=', 'packages.id')
                                ->join('authority_sites', 'authority_sites.id', '=', 'packages_sites.authority_site')
                                ->where('packages_categories.id', $category)
                                ->groupBy('packages.id')
                                ->orderBy($sort, $order)
                                ->get();
    }

}
