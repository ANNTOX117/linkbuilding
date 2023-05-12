<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Page;
use App\Models\SiteExtraSetting;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class Home extends Component {
    public $title;
    public $site;
    public $bannersPC;
    public $bannersMovile;
    public $nextBanner;
    public $currentBanner;
    public $extraSettings;

    protected $domain;
    protected $category;
    protected $website;
    protected $indexPC = 0;
    protected $indexMovile = 0;

    public function getNextElementPC($arr)
    {
        $result = $arr[$this->indexPC];
        $this->indexPC = ($this->indexPC + 1) % count($arr);
        return $result;
    }

    public function getNextElementMovile($arr)
    {
        $result = $arr[$this->indexMovile];
        $this->indexMovile = ($this->indexMovile + 1) % count($arr);
        return $result;
    }

    public function mount() {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,0,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,0,1,true);
    }

    public function render() {
        $site_data = $this->website;
        $randomProfiles = Article::getArticlesBySiteWithProvincie($site_data->id,12);
        $homePageData = Page::getDataByPage($this->website->id,"/home");
        $domain = $this->domain;
        $extraSettings = SiteExtraSetting::where("site_id",$this->website->id)->first();
        if (isset($homePageData) && (array)$homePageData)  {
            return view('livewire.dating-template-profile1.home',compact("randomProfiles","site_data","domain","homePageData"))->layout('layouts.datingTemplateProfile1', 
        [
            'title' => $homePageData->title,
            'meta_title'=>$homePageData->meta_title,
            'meta_description'=>$homePageData->meta_description,
            'meta_keyword'=>$homePageData->meta_keyword,
            'noindex_follow'=>$homePageData->noindex_follow,
            'site' => $this->site,
            "page"=>$this->site->name]);
        }else{
            return view('livewire.dating-template-profile1.home',compact("randomProfiles","site_data","domain","homePageData"))->layout('layouts.datingTemplateProfile1', 
        ['title' => $this->site, 'site' => $this->site,"page"=>$this->site->name]);
        }
    }
}
