<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Site extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'sites';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'slug',
        'name',
        'url',
        'meta_title',
        'slider',
        'slider_text',
        'slider_background',
        'meta_description',
        'logo',
        'type',
        'header',
        'menu',
        'links',
        'box',
        'headerText',
        'footerText',
        'blog_header',
        'blog_footer',
        'daughter_header',
        'daughter_footer',
        'daughter_home_header',
        'daughter_home_footer',
        'daughter_blog_header',
        'daughter_blog_footer',
        'footer',
        'footer2',
        'footer3',
        'footer4',
        'contact',
        'currency',
        'automatic',
        'ip',
        'users',
        'language',
        'permanent'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function categories() {
        return $this->belongsToMany('App\Models\Category', 'sites_categories_child', 'site', 'category');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','site_user','site','user');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Site::select('sites.*', DB::raw('count(links.id) as active_links'))
            ->leftJoin('authority_sites', 'authority_sites.site', '=', 'sites.id')
            ->leftJoin('links', function ($join) {
                $join->on('links.authority_site', '=', 'authority_sites.id');
                $join->on('links.active', '=', DB::raw('1'));
            })
            ->groupBy('sites.id')
            ->orderBy($sort, $order)
            ->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc') {
        return Site::select('sites.*', DB::raw('count(links.id) as active_links'))
            ->leftJoin('authority_sites', 'authority_sites.site', '=', 'sites.id')
            ->leftJoin('links', function ($join) {
                $join->on('links.authority_site', '=', 'authority_sites.id');
                $join->on('links.active', '=', DB::raw('1'));
            })
            ->groupBy('sites.id')
            ->orderBy($sort, $order)
            ->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'name', $order = 'asc', $pagination="", $search = null) {
        return Site::select('sites.*', DB::raw('count(links.id) as active_links'))
                ->leftJoin('authority_sites', 'authority_sites.site', '=', 'sites.id')
                ->leftJoin('links', function ($join) {
                    $join->on('links.authority_site', '=', 'authority_sites.id');
                    $join->on('links.active', '=', DB::raw('1'));
                })
                ->groupBy('sites.id')
                ->filter($search)
                ->orderBy($sort, $order)
                ->paginate($pagination);
    }

    public function scopeFilter($query, $val){
            return $query
            ->join('languages', 'languages.id','sites.language')
            ->where('sites.url', 'like', '%'.$val.'%')
            ->orWhere('sites.type', 'like', '%'.$val.'%')
            ->orWhere('sites.currency', 'like', '%'.$val.'%')
            ->orWhere('languages.description', 'like', '%'.$val.'%');
    }

    public static function is_normal_site($url) {
        return Site::where('url', $url)->first();
    }

    public static function is_admin_section() {
        return self::where('url', domain())->doesntExist();
    }

    public static function get_info($url) {
        $website  = Site::where('url', 'like', '%'.$url.'%')->whereIn('type', ['Link building system', 'Blog page'])->first();
        if (!empty($website->language)){
          	$language = Language::find($website->language);
            \App::setLocale($language -> name);
        }
        return $website;
    }

    public static function doesnt_exist($slug) {
        return Site::where('slug', $slug)->where('type', 'Link building system')->doesntExist();
    }

    public static function by_slug($slug) {
        return Site::where('slug', $slug)->first();
    }

    public static function all_websites() {
        return Site::select('id', 'url as text')->where('type', 'Link building system')->orderBy('name', 'asc')->get();
    }

    public static function already_exists($url, $id = null) {
        if(!empty($id)) {
            return Site::where('id', '!=', $id)->where('url', $url)->exists();
        }

        return Site::where('url', $url)->exists();
    }

}
