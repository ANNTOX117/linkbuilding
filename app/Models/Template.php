<?php

namespace App\Models;

use Google\Api\Expr\V1alpha1\Expr\Select;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory;

    public static function insertTemplate($name){
        $template = new Template();
        $template->name = $name;
        $template->slug = Str::slug($name);
        $template->save();
        return $template->id;
    }

    public static function withFilterPagination($sort = 'id', $order = 'desc', $pagination="", $search = null) {
        return Template::select('templates.id','templates.name',\DB::raw('group_concat(sites.name,"  ") as sites'))
            ->where('templates.name', 'like', '%'.$search.'%')
            ->leftJoin("templates_sites","templates.id","=","templates_sites.template_id")
            ->leftJoin("sites","templates_sites.site_id","=","sites.id")
            ->orderBy($sort, $order)
            ->groupBy("templates.id")
            ->paginate($pagination);
    }

    public static function getDataTemplateById($idTemplate){
        return Template::find($idTemplate)->first();
    }

    public static function getTemplateName($idSite)
    {
        return self::select("templates.slug")
        ->join("templates_sites","templates.id","=","templates_sites.template_id")
        ->where("templates_sites.site_id",$idSite)
        ->first();
    }
    
    public static function getBannersByTemplatesBySite($idSite)
    {
        return self::select("templates_extra_settings.banner_large_image","templates_extra_settings.banner_large_redirect","templates_extra_settings.banner_compact_image","templates_extra_settings.banner_compact_redirect")
        ->join("templates_sites","templates.id","templates_sites.template_id")
        ->join("templates_extra_settings","templates_extra_settings.template_id","templates.id")
        ->where("templates_sites.site_id",$idSite)
        ->where("templates_sites.active",1)
        ->first();
    }

    public static function getContentByTemplateBySite($idSite)
    {
        return self::select("templates_extra_settings.content_top_register","templates_extra_settings.image_top_register")
        ->join("templates_sites","templates.id","templates_sites.template_id")
        ->join("templates_extra_settings","templates_extra_settings.template_id","templates.id")
        ->where("templates_sites.site_id",$idSite)
        ->where("templates_sites.active",1)
        ->first();
    }

    public static function getTemplateActiveBySite($siteId)
    {
        return self::select("templates.id")
        ->join("templates_sites","templates.id","templates_sites.template_id")
        ->where("templates_sites.active",1)
        ->where("templates_sites.site_id",$siteId)
        ->first();
    }

    public static function getContentFooterByTemplateAndSite($idSite)
    {
        return self::select("footer_content_first_part","footer_content_second_part","footer_content_third_part")
        ->join("templates_sites","templates.id","templates_sites.template_id")
        ->join("templates_extra_settings","templates.id","templates_extra_settings.template_id")
        ->where("templates_sites.active",1)
        ->where("templates_sites.site_id",$idSite)
        ->first();
    }

    public static function getContentButtomRegisterByTemplateAndSite($idSite)
    {
        return self::select("content_buttom_register")
        ->join("templates_sites","templates.id","templates_sites.template_id")
        ->join("templates_extra_settings","templates.id","templates_extra_settings.template_id")
        ->where("templates_sites.active",1)
        ->where("templates_sites.site_id",$idSite)
        ->first();
    }
}