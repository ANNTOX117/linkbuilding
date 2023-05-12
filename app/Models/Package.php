<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Package extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'packages';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'language'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function package_sites() {
        return $this->hasOne('App\Models\PackageSite', 'id', 'package');
    }

    public static function all_items($sort = 'name', $order = 'desc') {
        return Package::select('packages.*', DB::raw('count(packages_sites.package) as pages'))
                        ->leftJoin('packages_sites', 'packages_sites.package', '=', 'packages.id')
                        ->groupBy('packages_sites.package')
                        ->orderBy($sort, $order)
                        ->get();
    }

    public static function with_pagination($sort = 'name', $order = 'desc') {
        return Package::select('packages.*', DB::raw('count(packages_sites.package) as pages'))
            ->leftJoin('packages_sites', 'packages_sites.package', '=', 'packages.id')
            ->groupBy('packages_sites.package')
            ->orderBy($sort, $order)
            ->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'name', $order = 'desc', $pagination, $search = null) {
        return Package::select('packages.*', DB::raw('count(packages_sites.package) as pages'))
            ->join('languages', 'languages.id', 'packages.language')
            ->leftJoin('packages_sites', 'packages_sites.package', '=', 'packages.id')
            ->filter($search)
            ->groupBy('packages_sites.package')
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilter($query, $val){
        return $query->Where('packages.name', 'LIKE', '%'.$val.'%')
                        ->orWhere('languages.description', 'LIKE', '%'.$val.'%')
                        ->orWhere('packages.price', 'LIKE', '%'.$val.'%');
    }

    public static function name($package) {
        return Package::find($package)->name;
    }

    public static function findByIds($packages) {
        return Package::whereIn($packages)->get();
    }

    public static function by_category($category, $sort = 'name', $order = 'desc') {
        return Package::where('category', $category)
                        ->orderBy($sort, $order)
                        ->get()
                        ->pluck('id');
    }

    public static function already_exists($name, $language, $id = null) {
        if(!empty($id)) {
            return Package::where('id', '!=', $id)->where('name', $name)->where('language', $language)->exists();
        }

        return Package::where('name', $name)->where('language', $language)->exists();
    }

}
