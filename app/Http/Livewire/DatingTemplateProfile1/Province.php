<?php

namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\Banner;
use App\Models\City;
use App\Models\Province as ModelsProvince;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Livewire\WithPagination;
use Livewire\Component;

class Province extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $title;
    public $site;
    public $profilesBySite;
    public $provinceName;
    public $province;
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

    /***
     * This method send and event in js when the paginate is clicked
     * trying to keep the styles (the grid breaks with the paginate)
     */
    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->dispatchBrowserEvent('reloadPage');
    }
    public function mount() {
        $this->provinceName = htmlspecialchars(strip_tags(Request::segment(2)));
        $this->province = ModelsProvince::where("path",$this->provinceName)->first();
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.nl";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website) || !isset($this->province)) abort(404);
        
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,3,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,3,1,true);
    }

    public function render(){
        $site_data = $this->website;
        $province = ucfirst(strip_tags(Request::segment(2)));
        $biggetCity = ModelsProvince::selectRaw("cities.*")->join("cities","provinces.id","cities.province_id")->where("provinces.path",strip_tags(Request::segment(2)))->orderBy("biggest")->first();
        $profilesByProvince = [];
        if (isset($biggetCity) && !empty($biggetCity)) {
            $profilesByProvince = Article::getArticlesByCityAndRadio($biggetCity->lat,$biggetCity->lon,$this->site->id,50,30,true);
        }else{
            $profilesByProvince = Article::getArticlesByCityAndRadio("51.6561505967378","4.9414012199886",$this->site->id,50,30,true);
        }
        $domain = $this->domain;
        $randomCities = City::getRandomCities(10);
        $biggestCity = City::getBiggestCityByProvince($this->province->id);
        $getCitiesByRadio = City::getNearCities($biggestCity->id,50,10);
        return view('livewire.dating-template-profile1.province',compact("profilesByProvince","site_data","domain","randomCities","getCitiesByRadio"))->layout('layouts.datingTemplateProfile1', 
        [
            'meta_title'=> $province,
            "meta_description" => $province,
            "meta_keyword" => $province,
            'noindex_follow'=>false,
            'title' => $this->title, 'site' => $this->site, 
            'page' => $this->site->name]);
    }
}