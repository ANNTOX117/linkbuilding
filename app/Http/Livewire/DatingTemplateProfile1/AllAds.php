<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Page;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;

class AllAds extends Component {
    use WithPagination;
    public $title;
    public $site;
    public $bannersPC;
    public $bannersMovile;

    protected $domain;
    protected $category;
    protected $website;
    protected $paginationTheme = 'bootstrap';
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

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->dispatchBrowserEvent('reloadPage');
    }
    // public function previousPage(){
    //     $this->previousPage();
    //     $this->dispatchBrowserEvent('reloadPage');
    // }
    // public function nextPage()
    // {
    //     $this->nextPage();
    //     $this->dispatchBrowserEvent('reloadPage');
    // }

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
        $this->bannersPC = Banner::getBannersBySite($this->website->id,5,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,5,1,true);
    }

    public function render() {
        $this->website  = \App\Models\Site::get_info($this->domain);
        $site_data = $this->website;
        $randomProfiles = Article::getArticlesBySiteWithProvincie($this->site->id,48,true,false);
        $domain = $this->domain;
        $adsPageData = Page::getDataByPage($this->website->id,"/ads");
        if (isset($adsPageData) && (array)$adsPageData)  {
            return view('livewire.dating-template-profile1.all-ads',compact("randomProfiles","site_data","domain","adsPageData"))->layout('layouts.datingTemplateProfile1', 
            [
                'title' => $adsPageData->title,
                'noindex_follow'=>$adsPageData->noindex_follow,
                'meta_title' => $adsPageData->meta_title,
                'meta_description' => $adsPageData->meta_description,
                'meta_keyword' => $adsPageData->meta_keyword,
                'site' => $this->site,
                "page"=>$this->site->name
            ]);
        }else{
            return view('livewire.dating-template-profile1.all-ads',compact("randomProfiles","site_data","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site,"page"=>$this->site->name]);
        }
    }
}