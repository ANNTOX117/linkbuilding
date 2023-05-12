<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class PagebuilderTranslation extends Model {

    use HasFactory;
    use Rememberable;

    protected $table = 'pagebuilder__page_translations';

    protected $fillable = [
        'id',
        'site',
        'page_id',
        'locale',
        'title',
        'route',
        'created_at',
        'updated_at'
    ];

    public function scopeCached($query){
        return env('APP_ENV') === 'production' ? $query->remember(60 * 60) : $query;
    }

    public function sites() {
        return $this->hasOne('App\Models\Site', 'id', 'site');
    }

    public function pages() {
        return $this->hasOne('App\Models\PagebuilderPage', 'id', 'page_id');
    }

    public static function slug_already_exists_by_site($site, $route, $id = null) {
        if(!empty($id)) {
            return PagebuilderTranslation::where('id', '!=', $id)->where('site', $site)->where('route', $route)->exists();
        }

        return PagebuilderTranslation::where('site', $site)->where('route', $route)->exists();
    }

    public static function doesnt_exist($slug) {
        return PagebuilderTranslation::where('site', site_id())->where('route', prep_slash($slug))->doesntExist();
    }

    public static function info($slug) {
        return PagebuilderTranslation::where('site', site_id())->where('route', prep_slash($slug))->first();
    }

    public static function list_by_site($site) {
        return PagebuilderTranslation::where('site', $site)->orderBy('title', 'asc')->get();
    }

    public static function update_row($id, $array) {
        return PagebuilderTranslation::where('id', $id)->update($array);
    }

    public static function list($site) {
        return PagebuilderTranslation::where('site', $site)->orderBy('title', 'asc')->get()->pluck('title', 'id')->toArray();
    }

    public static function last_id() {
        return self::orderBy('id', 'desc')->first()->id ?? 0;
    }

}
