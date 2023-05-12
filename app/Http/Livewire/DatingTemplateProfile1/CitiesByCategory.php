<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Category;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Banner;
use App\Models\City;
use App\Models\SeoPage;
use Illuminate\Support\Facades\Request;

class CitiesByCategory extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $pageName = 'page';
    public $title;
    public $description;
    public $section;
    public $site;
    public $categories;
    public $slides;
    public $profilesBySite;
    public $categoryUrl;
    public $getAllCitiesId;
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

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->dispatchBrowserEvent('reloadPage');
    }

    public function mount() {
        $this->categoryUrl = Category::where("url",Request::segment(2))->first();
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "bullsandhornsmedia.com";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) {
            $this->domain = $this->category . '.' . $this->domain;
        }
        if(empty($this->website) || !isset($this->categoryUrl)) {
            abort(404);
        }
        $this->site       = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->getAllCitiesId = SeoPage::where("category_id",$this->categoryUrl->id)->groupBy("city_id")->pluck("city_id");
        $this->bannersPC = Banner::getBannersBySite($this->website->id,6,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,6,1,true);
    }

    public function render(){
        $AllCities = City::whereIn("id",$this->getAllCitiesId)->pluck("path");
        $site_data = $this->website;
        $domain = $this->domain;
        return view('livewire.dating-template-profile1.cities-by-category',compact("site_data","AllCities","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
    }
}
