<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerSite extends Model
{
    use HasFactory;
    protected $table = 'banner_by_site';

    public static function getAllBannersBySite($siteId,$page)
    {
        return self::select("banner_by_site.id","banners.url_file","banners.url_redirect","banner_by_site.order_banner","banners.type")
        ->join("sites","banner_by_site.site","sites.id")
        ->join("banners","banner_by_site.banner","banners.id")
        ->where("banner_by_site.site",$siteId)
        ->where("banner_by_site.page",$page)
        ->orderBy("banner_by_site.order_banner")
        ->get();
    }
}
