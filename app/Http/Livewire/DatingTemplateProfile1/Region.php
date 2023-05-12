<?php
/***
 * @author Antonio Razo
 */
namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\ArticleAttribute;
use App\Models\Banner;
use App\Models\City;
use App\Models\Page;
use App\Models\Province;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
//use Illuminate\Support\Str;

class Region extends Component {
    public $title;
    public $site;
    public $profilesBySite;
    public $idProvince;
    public $bannersPC;
    public $bannersMovile;

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

    public function getProfilesByProvince(int $idProvince)
    {
        
        return Article::getArticlesByProvince($idProvince,$this->site->id);
    }

    public function mount() {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site      = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,3,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,3,1,true);
    }

    public function render(){
        $site_data = $this->website;
        $profilesAmountByProvince = ArticleAttribute::getAmountProfilesByProvience();
        $idCountry = ArticleAttribute::select("value")->where("name","country_id")->first();
        $provincesBySite = Province::select("id","name")->where("country_id",$idCountry->value)->get();
        $domain = $this->domain;
        $regionsPageData = Page::getDataByPage($this->website->id,"/regions");
        if (isset($regionsPageData) && (array)$regionsPageData) {
            return view('livewire.dating-template-profile1.region',compact("profilesAmountByProvince","provincesBySite","site_data","domain","regionsPageData"))->layout('layouts.datingTemplateProfile1', 
            [
                'title' => $regionsPageData->title,
                'meta_title' => $regionsPageData->meta_title,
                'meta_description' => $regionsPageData->meta_description,
                'meta_keyword' => $regionsPageData->meta_keyword,
                'noindex_follow'=>$regionsPageData->noindex_follow,
                'site' => $this->site,
                'page' => $this->site->name]);
        }else{
            return view('livewire.dating-template-profile1.region',compact("profilesAmountByProvince","provincesBySite","site_data","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
        }
    }
}