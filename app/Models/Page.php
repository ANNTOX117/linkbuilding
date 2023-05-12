<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'url',
        'language',
        'title',
        'content_top',
        'content_buttom',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'all_cities',
        'noindex_follow'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftJoin('languages', 'languages.id', '=', 'pages.language')
            ->where('pages.url', 'like', '%'.$val.'%')
            ->orWhere('pages.title', 'like', '%'.$val.'%')
            ->orWhere('pages.content_top', 'like', '%'.$val.'%')
            ->orWhere('pages.content_buttom', 'like', '%'.$val.'%')
            ->orWhere('languages.description', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'title', $order = 'asc') {
        return Page::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'title', $order = 'asc', $pagination, $search = null) {
        return Page::select('pages.*')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function already_exists($url) {
        return Page::where('url', $url)->exists();
    }

    public static function get_info($url, $param) {
        return Page::where('url', $url)->first()->$param;
    }

    public static function slug_exists($url, $lang) {
        return Page::where('url', prep_slash($url))->where('language', $lang)->exists();
    }

    public static function info($url, $lang) {
        return Page::where('url', prep_slash($url))->where('language', $lang)->first();
    }

    public static function getDataByPage($idSite,$slug)
    {
        return self::join("pages_sites","pages_sites.page","pages.id")
        ->where("pages_sites.site",$idSite)
        ->where("pages.url",$slug)
        ->first();
    }

}
