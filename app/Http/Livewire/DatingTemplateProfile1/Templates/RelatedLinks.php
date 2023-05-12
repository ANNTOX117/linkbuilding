<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Article;
use App\Models\Banner;
use Livewire\Component;

class RelatedLinks extends Component
{
    public $idSite;
    public $banners;
    protected $index = 0;

    public function getNextElement($arr)
    {
        if (count($arr) > 0) {
            $result = $arr[$this->index];
            $this->index = ($this->index + 1) % count($arr);   
            return $result;
        }else{
            return false;
        }
        

    }
    public function mount($idSite)
    {
        $this->idSite = $idSite;
        $this->banners = Banner::getBannersBySite($idSite,4,0,true);
    }
    public function render()
    {
        $randomProfiles = Article::selectRaw("articles.url,articles.title,articles.image")
        ->join("article_attributes","articles.id","article_attributes.article_id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","sites.id","authority_sites.site")->where("sites.id",$this->idSite)->where("article_attributes.site_id",$this->idSite)
        ->inRandomOrder()->limit(4)->get();
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.related-links',compact("randomProfiles"));
    }
}
