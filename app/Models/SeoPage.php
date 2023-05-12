<?php
/***
 * @author Antonio Razo
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SeoPage extends Model
{
    use HasFactory;
    public static function seoPagesByFilters($country,$city,$category,$site,$paginate)
    {
        $query = self::select("seo_pages.id","seo_pages.meta_title","seo_pages.meta_description","seo_pages.title","seo_pages.description_top","seo_pages.description_buttom","seo_pages.description_buttom","seo_pages.text_infront_left","seo_pages.text_infront_right","seo_pages.active",DB::raw("ca.name as category"),"s.url",DB::raw("ci.name as city"))
        ->join(DB::raw("categories ca"),"seo_pages.category_id","=","ca.id")
        ->join(DB::raw("sites s"),"seo_pages.site_id","=","s.id")
        ->join(DB::raw("cities ci"),"seo_pages.city_id","=","ci.id");
        if ($country != "") $query->where("ci.country_id",$country);
        if ($city != "") $query->where("ci.id",$city);
        if ($category != null) $query->where("seo_pages.category_id",$category);
        if ($site != "") $query->where("seo_pages.site_id",$site);
        return $query->orderBy("seo_pages.id")->paginate($paginate);
    }

    public static function getSeoPageByCategoryCitySite($categoryId,$cityId,$siteId)
    {
        return self::where("category_id",$categoryId)
        ->where("site_id",$siteId)
        ->where("city_id",$cityId)
        ->first();
    }

    public static function getAllSeoPagesUrl()
    {
        return self::select( DB::raw("concat(categories.url,'-',categories.id,'/',cities.path) as url"),"seo_pages.created_at")
        ->join("categories","seo_pages.category_id","=","categories.id")
        ->join("cities","seo_pages.city_id","=","cities.id")
        ->get();
    }
}
