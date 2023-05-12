<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteCategoryChild extends Model {

    use HasFactory;

    protected $table = 'sites_categories_child';

    public $timestamps = false;

    protected $fillable = [
        'category',
        'site'
    ];

    public function links(){
        return $this->hasMany(Link::class, 'category');
    }

    public static function cleanup($site) {
        return SiteCategoryChild::where('site', $site)->delete();
    }

    public static function by_site($site) {
        return SiteCategoryChild::where('site', $site)->orderBy('category', 'asc')->get()->pluck('category');
    }

    public static function by_site_ids($site) {
        return SiteCategoryChild::select(DB::raw('GROUP_CONCAT(category) as category'))->where('site', $site)->orderBy('category', 'asc')->first()->category;
    }

    public static function list_categories($site) {
        return SiteCategoryChild::select(DB::raw('GROUP_CONCAT(categories.url) as categories'))->leftJoin('categories', 'categories.id', '=', 'sites_categories_child.category')->where('sites_categories_child.site', $site)->orderBy('categories.url', 'asc')->first()->categories;
    }

    public static function get_categories($site) {
        return SiteCategoryChild::select('categories.*')
                    ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
                    ->where('sites_categories_child.site', $site)
                    ->get();
    }

    public static function get_categories_wp($wordpress) {
        return SiteCategoryChild::select('categories.*')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('sites_categories_child.wordpress', $wordpress)
            ->get();
    }

    public function wordpress()
    {
        return $this->belongsTo(Wordpress::class, 'foreign_key', 'wordpress');
    }

}
