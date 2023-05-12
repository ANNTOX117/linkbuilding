<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageSite extends Model {

    use HasFactory;

    protected $table = 'pages_sites';

    public $timestamps = false;

    protected $fillable = [
        'page',
        'site'
    ];

    public static function cleanup($page) {
        return PageSite::where('page', $page)->delete();
    }

    public static function list($page) {
        return PageSite::where('page', $page)->get()->pluck('site');
    }

    public static function list_for_select($page) {
        return Site::select('sites.id', 'sites.url as text', DB::raw('IF(pages_sites.site, true, false) as selected'))
            ->leftJoin('pages_sites', function($join) use ($page){
                $join->on('pages_sites.site', '=', 'sites.id');
                $join->on('pages_sites.page', '=', DB::raw('"'. $page .'"'));
            })
            ->where('sites.type', 'Link building system')
            ->orderBy('sites.url', 'asc')
            ->get();
    }

    public static function is_restricted_for_some_users($page) {
        return PageSite::where('page', $page)->exists();
    }

    public static function the_site_is_allowed($page, $site) {
        return PageSite::where('page', $page)->where('site', $site)->exists();
    }

}
