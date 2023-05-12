<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageSite extends Model {

    use HasFactory;

    protected $table = 'packages_sites';

    public $timestamps = false;

    protected $fillable = [
        'package',
        'authority_site',
        'category'
    ];

    public static function package_category($package) {
        return PackageSite::where('package', $package)->first()->category ?? '0';
    }

    public static function package_ids($package) {
        return PackageSite::where('package', $package)->get()->pluck('authority_site');
    }

    public static function cleanup($package) {
        return PackageSite::where('package', $package)->delete();
    }

    public static function get_total($package) {
        return PackageSite::select('authority_sites.*')
                            ->join('authority_sites', 'authority_sites.id', '=', 'packages_sites.authority_site')
                            ->where('packages_sites.package', $package)
                            ->get()
                            ->count();
    }

    public static function get_avg($package, $column) {
        return PackageSite::select('authority_sites.*')
            ->join('authority_sites', 'authority_sites.id', '=', 'packages_sites.authority_site')
            ->where('packages_sites.package', $package)
            ->get()
            ->avg($column) ?? 0;
    }

    public static function get_sum($package, $column) {
        return PackageSite::select('authority_sites.*')
                ->join('authority_sites', 'authority_sites.id', '=', 'packages_sites.authority_site')
                ->where('packages_sites.package', $package)
                ->get()
                ->sum($column) ?? 0;
    }

    public static function get_info($package, $sort = 'url', $order = 'asc') {
        return PackageSite::select('authority_sites.*')
                ->join('authority_sites', 'authority_sites.id', '=', 'packages_sites.authority_site')
                ->where('packages_sites.package', $package)
                ->orderBy($sort, $order)
                ->get();
    }



}
