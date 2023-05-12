<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSite extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "templates_sites";

    public static function insertTemplateBySite($idTemplate,$idSite){
        self::deactivateAll($idSite,$idTemplate);
        $templateSite = new TemplateSite();
        $templateSite->template_id = $idTemplate;
        $templateSite->site_id = $idSite;
        $templateSite->active = 1;
        $templateSite->save();
        return $templateSite->id;
    }

    public static function getAllSitesByTemplate($idTemplate){
        return TemplateSite::select("sites.id")->where("template_id",$idTemplate)
            ->join("sites","templates_sites.site_id","=","sites.id")
            ->get()
            ->pluck("id");
    }

    public static function deactivateAll($siteId)
    {
        return self::where('site_id', $siteId)->update(['active' => 0]);
    }
}
