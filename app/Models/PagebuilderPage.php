<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class PagebuilderPage extends Model {

    use HasFactory;
    use Rememberable;

    protected $table = 'pagebuilder__pages';

    protected $fillable = [
        'site',
        'name',
        'layout',
        'seo_title',
        'seo_description',
        'data',
        'created_at',
        'updated_at'
    ];

    public function scopeCached($query){
        return env('APP_ENV') === 'production' ? $query->remember(60 * 60) : $query;
    }

    public static function update_row($id, $array) {
        return PagebuilderPage::where('id', $id)->update($array);
    }

    public static function copy_template($site) {
        $now   = Carbon::now(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
        $pages = self::where('site', 3)->get();
        $total = PagebuilderTranslation::last_id();
        $menus = Menu::where('site', 3)->get();

        if(!empty($pages)) {
            foreach($pages as $i => $page) {
                $insert = array('site'            => $site,
                                'name'            => $page->name,
                                'layout'          => $page->layout,
                                'seo_title'       => null,
                                'seo_description' => null,
                                'data'            => $page->data,
                                'created_at'      => $now,
                                'updated_at'      => $now);

                $inserted = self::create($insert);

                $parent = PagebuilderTranslation::where('page_id', $page->id)->first();

                $insert = array('id'         => $total + ($i + 1),
                                'site'       => $site,
                                'page_id'    => $inserted->id,
                                'locale'     => $parent->locale,
                                'title'      => $parent->title,
                                'route'      => $parent->route,
                                'created_at' => $now,
                                'updated_at' => $now);

                PagebuilderTranslation::create($insert);
            }
        }

        if(!empty($menus)) {
            foreach($menus as $menu) {
                $insert = array('site'       => $site,
                                'title'      => $menu->title,
                                'url'        => $menu->url,
                                'page'       => Menu::menu_id($site, $menu->title),
                                'order'      => $menu->order,
                                'suborder'   => $menu->suborder,
                                'status'     => $menu->status,
                                'type'       => $menu->type,
                                'lock'       => $menu->lock,
                                'user'       => $menu->user,
                                'created_at' => $now,
                                'updated_at' => $now);

                Menu::create($insert);
            }
        }

        $pages = self::where('site', $site)->get();
        $site  = Site::find($site);

        // Replace default primary and secondary colors
        foreach($pages as $page) {
            $data = str_replace('#2123bc', $site->primary_color, $page->data);
            $data = str_replace('#ea5f52', $site->secondary_color, $data);

            self::where('id', $page->id)->update(['data' => $data]);
        }
    }

}
