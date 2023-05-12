<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Wordpress extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'wordpress';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'url',
        'type',
        'ip',
        'automatic',
        'language',
        'username',
        'password',
        'permanent'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function articles() {
        return $this->hasOne('App\Models\Article', 'wordpress');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Wordpress::select('wordpress.*', DB::raw('count(links.id) as active_links'))
            ->leftJoin('authority_sites', 'authority_sites.wordpress', '=', 'wordpress.id')
            ->leftJoin('links', function ($join) {
                $join->on('links.authority_site', '=', 'authority_sites.id');
                $join->on('links.active', '=', DB::raw('1'));
            })
            ->groupBy('wordpress.id')
            ->orderBy($sort, $order)
            ->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc') {
        return Wordpress::select('wordpress.*', DB::raw('count(links.id) as active_links'))
            ->leftJoin('authority_sites', 'authority_sites.wordpress', '=', 'wordpress.id')
            ->leftJoin('links', function ($join) {
                $join->on('links.authority_site', '=', 'authority_sites.id');
                $join->on('links.active', '=', DB::raw('1'));
            })
            ->groupBy('wordpress.id')
            ->orderBy($sort, $order)
            ->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return Wordpress::select('wordpress.*', DB::raw('count(links.id) as active_links'),  DB::raw('(CASE WHEN wordpress.type = "article" THEN "Article link" WHEN wordpress.type = "sidebar" THEN "Sidebar link" WHEN wordpress.type = "both" THEN "Article + Sidebar link" ELSE "-" END) AS type_site'))
            ->leftJoin('authority_sites', 'authority_sites.wordpress', '=', 'wordpress.id')
            ->leftJoin('links', function ($join) {
                $join->on('links.authority_site', '=', 'authority_sites.id');
                $join->on('links.active', '=', DB::raw('1'));
            })
            ->filterSearch($search)
            ->groupBy('wordpress.id')
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilterSearch($query, $val){
            return $query->join('languages', 'languages.id', 'wordpress.language')
            ->where('wordpress.name', 'LIKE', '%'.$val.'%')
            ->orWhere('wordpress.url', 'LIKE', '%'.$val.'%')
            ->orWhere('wordpress.ip', 'LIKE', '%'.$val.'%')
            ->orWhere('languages.description', 'LIKE', '%'.$val.'%');
    }


    public static function is_wordpress_site($url) {
        return Wordpress::where('url', $url)->first();
    }

    public static function by_type($type, $sort = 'name', $order = 'asc') {
        return Wordpress::where('type', $type)->orwhere('type', 'both')->orderBy($sort, $order)->get();
    }

    public static function category_by_wordpress($wordpress_id)
    {
        return Wordpress::join('sites_categories_main', 'sites_categories_main.wordpress', 'wordpress.id')
                ->join('categories', 'categories.id', 'sites_categories_main.category')
                ->select('categories.name', 'categories.id', 'wordpress.permanent')
                ->where('wordpress.id', $wordpress_id)
                ->get();
    }

    public function sitecategorychild()
    {
        return $this->hasMany(SiteCategoryChild::class, 'wordpress');
    }

    public static function scopeauthorityavgtype($query,$type){
        $query->join('authority_sites', 'authority_sites.wordpress', '=', 'wordpress.id')
        ->select(
            'wordpress.id',
            'authority_sites.id as authority',
            'wordpress.url',
            'authority_sites.pa',
            'authority_sites.da',
            'authority_sites.tf',
            'authority_sites.cf',
            'wordpress.ip',
            'authority_sites.price',
            'authority_sites.price_special',
        )
        ->where('wordpress.type', $type)
        ->orWhere('wordpress.type', 'both');
    }

    public static function authority_avg_type($type, $sort = 'url', $order = 'asc')
    {
        return Wordpress::Join('authority_sites', 'authority_sites.wordpress', '=', 'wordpress.id')
        ->select(
            'wordpress.id',
            'wordpress.url',
            \DB::raw('AVG(authority_sites.pa) as pa'),
            \DB::raw('AVG(authority_sites.da) as da'),
            \DB::raw('AVG(authority_sites.tf) as tf'),
            \DB::raw('AVG(authority_sites.cf) as cf'),
            'wordpress.ip',
            \DB::raw('sum(authority_sites.price) as price'),
            \DB::raw('sum(authority_sites.price_special) as price_special'),
        )
        ->where('wordpress.type', $type)
        ->orWhere('wordpress.type', 'both')
        ->orderBy($sort, $order)
        ->groupBy('wordpress.id')
        ->get();
    }

    public function scopeFilter($query, $val){
            return $query
            ->where('wordpress.url', 'LIKE', '%'.$val.'%')
            ->orWhere('pa', 'LIKE', '%'.$val.'%')
            ->orWhere('da', 'LIKE', '%'.$val.'%')
            ->orWhere('tf', 'LIKE', '%'.$val.'%')
            ->orWhere('cf', 'LIKE', '%'.$val.'%')
            ->orWhere('subnet', 'LIKE', '%'.$val.'%');
    }


    public static function error_site_wordpress($wordpress_id, $error_id){
        return Wordpress::where('id', $wordpress_id)->update(['error' => $error_id]);
    }

    public static function already_exists($url, $id = null) {
        if(!empty($id)) {
            return Wordpress::where('id', '!=', $id)->where('url', $url)->exists();
        }

        return Wordpress::where('url', $url)->exists();
    }

}
