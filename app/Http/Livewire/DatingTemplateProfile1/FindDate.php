<?php

namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\Banner;
use App\Models\City;
use App\Models\Page;
use App\Models\Province as ModelsProvince;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Livewire\WithPagination;
use Livewire\Component;

class FindDate extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $title;
    public $site;
    public $profilesBySite;
    public $province;
    public $bannersPC;
    public $bannersMovile;
    public $cityName;
    public $city;
    public $allUsersByCity;

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
        $result = $arr[$this->indexPC];
        $this->indexPC = ($this->indexPC + 1) % count($arr);
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
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
    
        $this->website  = \App\Models\Site::get_info($this->domain);
        $this->cityName = htmlspecialchars(strip_tags(Request::segment(2)));
        $this->city = City::where("path",$this->cityName)->first();
        if(empty($this->website) || !isset($this->city)) abort(404);
        $this->site = $this->website;
        $this->allUsersByCity = Article::getArticlesByCityAndRadio($this->city->lat,$this->city->lon,$this->site->id,50,18);
        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,3,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,3,1,true);
    }

    public function render(){
        $site_data = $this->website;
        $domain = $this->domain;
        $path = "/".Request::segment(1);
        $path_complete = "/".Request::segment(1)."/".Request::segment(2);
        $findDateMetaData = Page::getDataByPage($this->website->id,$path_complete)??Page::getDataByPage($this->website->id,$path);
        if (isset($findDateMetaData) && (array)$findDateMetaData)  {
            return view('livewire.dating-template-profile1.find-date',compact("site_data","domain","findDateMetaData"))->layout('layouts.datingTemplateProfile1', 
        [
            'title' => replace_text($findDateMetaData->meta_title,$this->city->name),
            'meta_title'=> replace_text($findDateMetaData->meta_title,$this->city->name),
            'meta_description'=> replace_text($findDateMetaData->meta_description,$this->city->name),
            'meta_keyword'=> replace_text($findDateMetaData->meta_keyword,$this->city->name),
            'site' => $this->site,
            "page"=>$this->site->name,
            'noindex_follow'=>$findDateMetaData->noindex_follow,
        ]);
        }else{
            return view('livewire.dating-template-profile1.find-date',compact("site_data","domain"))->layout('layouts.datingTemplateProfile1', ['site' => $this->site, 'page' => $this->site->name]);
        }
    }
}