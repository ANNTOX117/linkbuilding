<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class PageEditionEdition extends Model {

    use HasFactory;
    use Rememberable;

    protected $table = 'pages_edition';

    protected $fillable = [
        'site',
        'slug',
        'title',
        'description',
        'content',
        'header',
        'seo_title',
        'seo_description',
        'status',
        'menu',
        'lock',
        'user'
    ];

    public function scopeCached($query){
        return env('APP_ENV') === 'production' ? $query->remember(60 * 60) : $query;
    }

    public function scopeActive($query) {
        return $query->where('status', 1);
    }

    public static function already_exists($name, $id = null) {
        if(!empty($id)) {
            return PageEdition::where('id', '!=', $id)->where('user', auth()->id())->where('title', $name)->exists();
        }

        return PageEdition::where('user', auth()->id())->where('title', $name)->exists();
    }

    public static function slug_already_exists($slug, $id = null) {
        if(!empty($id)) {
            return PageEdition::where('id', '!=', $id)->where('user', auth()->id())->where('slug', $slug)->exists();
        }

        return PageEdition::where('user', auth()->id())->where('slug', $slug)->exists();
    }

    public static function slug_already_exists_by_site($site, $slug, $id = null) {
        if(!empty($id)) {
            return PageEdition::where('id', '!=', $id)->where('site', $site)->where('slug', $slug)->exists();
        }

        return PageEdition::where('site', $site)->where('slug', $slug)->exists();
    }

    public static function slug_exists($slug) {
        return PageEdition::active()->where('user', owner_id())->where('slug', $slug)->exists();
    }

    public static function slug_exists_by_frontpage($slug) {
        return PageEdition::active()->where('site', site_id())->where('slug', $slug)->exists();
    }

    public static function info($slug) {
        return PageEdition::active()->where('user', owner_id())->where('slug', $slug)->first();
    }

    public static function info_by_frontpage($slug) {
        return PageEdition::active()->where('site', site_id())->where('slug', $slug)->first();
    }

    public static function list() {
        return PageEdition::where('user', auth()->id())->orderBy('title', 'asc')->get();
    }

    public static function list_by_site($site) {
        return PageEdition::where('site', $site)->orderBy('title', 'asc')->get();
    }

    public static function for_select($menu) {
        return PageEdition::active()->where('user', auth()->id())->where('menu', $menu)->orderBy('title', 'asc')->get()->pluck('title', 'id')->toArray();
    }

    public static function for_select_by_site($site, $menu) {
        return PageEdition::active()->where('site', $site)->where('menu', $menu)->orderBy('title', 'asc')->get()->pluck('title', 'id')->toArray();
    }

    public static function update_row($id, $array) {
        return PageEdition::where('id', $id)->update($array);
    }

}
