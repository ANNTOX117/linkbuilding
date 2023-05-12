<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteCategoryMain extends Model {

    use HasFactory;

    protected $table = 'sites_categories_main';

    public $timestamps = false;

    protected $fillable = [
        'category',
        'site',
        'headerText',
        'footerText'
    ];

    public static function cleanup($site) {
        return SiteCategoryMain::where('site', $site)->delete();
    }

    public static function cleanup_wp($site) {
        return SiteCategoryMain::where('wordpress', $site)->delete();
    }

    public static function by_site($site) {
        return SiteCategoryMain::where('site', $site)->orderBy('category', 'asc')->get()->pluck('category');
    }

    public static function by_site_category($site) {
        return SiteCategoryMain::select('sites_categories_main.id', 'categories.name as text')
            ->join('categories', 'categories.id', '=', 'sites_categories_main.category')
            ->where('site', $site)
            ->orderBy('category', 'asc')
            ->get();
    }

    public static function by_wp($site) {
        return SiteCategoryMain::where('wordpress', $site)->orderBy('category', 'asc')->get()->pluck('category');
    }

    public static function by_site_ids($site) {
        return SiteCategoryMain::select(DB::raw('GROUP_CONCAT(category) as category'))->where('site', $site)->orderBy('category', 'asc')->first()->category;
    }

    public static function by_wp_ids($site) {
        return SiteCategoryMain::select(DB::raw('GROUP_CONCAT(category) as category'))->where('wordpress', $site)->orderBy('category', 'asc')->first()->category;
    }

    public static function list($site) {
        return SiteCategoryMain::select('categories.*', 'sites_categories_main.visibility')
            ->join('categories', 'categories.id', '=', 'sites_categories_main.category')
            ->where('sites_categories_main.site', $site)
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function set_site_visibility($site, $category, $visibility) {
        return SiteCategoryMain::where('site', $site)->where('category', $category)->update(['visibility' => $visibility]);
    }

    public static function list_for_wordpress($wordpress) {
        return SiteCategoryMain::select('categories.*', 'sites_categories_main.visibility')
            ->join('categories', 'categories.id', '=', 'sites_categories_main.category')
            ->where('sites_categories_main.wordpress', $wordpress)
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function set_wordpress_visibility($wordpress, $category, $visibility) {
        return SiteCategoryMain::where('wordpress', $wordpress)->where('category', $category)->update(['visibility' => $visibility]);
    }

}
