<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Category extends Model {

    use HasFactory;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'name',
        'language'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function links(){
        return $this->hasMany(Link::class, 'category');
    }

    /***
     * @author Antonio
     * overwrite the method of Model object
     * return the value "url" stored in the database instead "id"
     */
    public function getRouteKeyName()
    {
        return "url";
    }

    public static function by_site($site){
        return Category::select('categories.id', 'categories.name as text')
            ->join('sites_categories_main', 'sites_categories_main.category', 'categories.id')
            ->whereNotNull('categories.name')
            ->where('sites_categories_main.site', $site)
            ->get();
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Category::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc') {
        return Category::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return Category::select('categories.*')
            ->whereNotNull('categories.name')
            ->filter($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilter($query, $val){
            return $query->join('languages', 'languages.id', 'categories.language')
                ->where('categories.name', 'LIKE', '%'.$val.'%')
                ->orWhere('languages.description', 'LIKE', '%'.$val.'%');
    }

    public static function by_language($language) {
        return Category::select('id', 'name as text')->where('language', $language)->orderBy('name', 'asc')->get();
    }

    public static function category_by_language($site, $language) {
        return Category::select('categories.id', 'categories.name as text', DB::raw('IF(sites_categories_main.id IS NULL, false, true) as selected'))
                        ->leftJoin('sites_categories_main', function($join) use ($site) {
                            $join->on('sites_categories_main.category', '=', 'categories.id');
                            $join->on('sites_categories_main.site', '=', DB::raw("'". $site ."'"));
                        })
                        ->where('categories.language', $language)
                        ->orderBy('categories.name', 'asc')
                        ->get();
    }

    public static function wp_category_by_language($site, $language) {
        return Category::select('categories.id', 'categories.name as text', DB::raw('IF(sites_categories_main.id IS NULL, false, true) as selected'))
            ->leftJoin('sites_categories_main', function($join) use ($site) {
                $join->on('sites_categories_main.category', '=', 'categories.id');
                $join->on('sites_categories_main.wordpress', '=', DB::raw("'". $site ."'"));
            })
            ->where('categories.language', $language)
            ->orderBy('categories.name', 'asc')
            ->get();
    }

    public static function categoriesBySite($site) {
        if (!isset($site)) return ;
        return Category::select('categories.id', 'categories.name','categories.url')
            ->join('sites_categories_main', function($join) use ($site) {
                $join->on('sites_categories_main.category', '=', 'categories.id');
            })
            ->where('sites_categories_main.site', $site)
            ->orderBy("categories.name","asc")
            ->get();
    }

    public static function subcategory_by_language($site, $language) {
        return Category::select('categories.id', 'categories.name as text', DB::raw('IF(sites_categories_child.id IS NULL, false, true) as selected'))
                        ->leftJoin('sites_categories_child', function($join) use ($site) {
                            $join->on('sites_categories_child.category', '=', 'categories.id');
                            $join->on('sites_categories_child.site', '=', DB::raw("'". $site ."'"));
                        })
                        ->where('categories.language', $language)
                        ->orderBy('categories.name', 'asc')
                        ->get();
    }

    public static function category_by_id($id) {
        return Category::select('name')->where('id', $id)->first();
    }

    public static function check_category($row)
    {

        return Category::select('categories.name', 'categories.url', 'languages.name')
        ->join('languages', 'languages.id', 'categories.language')
        ->where('categories.name', $row[0])
        ->where('categories.url', get_slug($row[0]))
        ->where('languages.name', $row[1])
        ->first();
    }

    public static function already_exists($name, $language, $id = null) {
        if(!empty($id)) {
            return Category::where('id', '!=', $id)->where('name', $name)->where('language', $language)->exists();
        }

        return Category::where('name', $name)->where('language', $language)->exists();
    }

}
