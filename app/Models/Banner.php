<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public static function getBannersBySite($siteId,$page,$type=0,$retunArr=false)
    {
        $query = self::select("url_file","url_redirect","type")
        ->join("banner_by_site","banners.id","banner_by_site.banner")
        ->join("templates_sites","templates_sites.site_id","banner_by_site.site")
        ->join("templates","templates_sites.template_id","templates.id")
        ->where("templates_sites.site_id",$siteId)
        ->where("templates_sites.active",1)
        ->where("banner_by_site.page",$page)
        ->where("banners.type",$type)
        ->orderBy("banner_by_site.order_banner","asc")
        ->orderBy("banners.type","desc")
        ->get();
        return $retunArr?$query->toArray():$query;
    }
}
