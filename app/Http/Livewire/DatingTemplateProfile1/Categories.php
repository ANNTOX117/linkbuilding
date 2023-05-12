<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Article;
use App\Models\Category as ModelsCategory;
use App\Models\City;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Http\Livewire\Traits\CustomWithPagination;
use App\Models\Banner;
use App\Models\Page;

class Categories extends Component {
    use CustomWithPagination;
    protected $paginationTheme = 'bootstrap';
    public $pageName = 'page';
    public $title;
    public $description;
    public $section;
    public $site;
    public $categories;
    public $slides;
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

    public function getProfilesByCategory(int $idCategory)
    {
        return Article::where("category",$idCategory)->inRandomOrder()->paginate(12);
    }
    public function mount() {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) {
            $this->domain = $this->category . '.' . $this->domain;
        }
        if(empty($this->website)) {
            abort(404);
        }
        if(!empty($this->category)) {
            $this->site      = $this->website;
            App::setLocale($this->site->languages->name ?? 'nl');
            $this->title     = ucfirst($this->category);
        } else {
            $this->site       = $this->website;
            App::setLocale($this->site->languages->name ?? 'nl');
            $this->title      = $this->site->meta_title;
        }
        $this->bannersPC = Banner::getBannersBySite($this->website->id,6,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,6,1,true);
    }

    public function render(){
        $site_data = $this->website;
        $categoriesBySite = ModelsCategory::categoriesBySite($this->website->id);
        // dd($categoriesBySite);
        $domain = $this->domain;
        $categoriesMetaData = Page::getDataByPage($this->website->id,"/categories");
        if (isset($categoriesMetaData) && (array)$categoriesMetaData)  {
            return view('livewire.dating-template-profile1.categories',compact("categoriesBySite","site_data","domain","categoriesMetaData"))->layout('layouts.datingTemplateProfile1', 
        [
            'title' => $categoriesMetaData->title,
            'meta_title'=>$categoriesMetaData->meta_title,
            'meta_description'=>$categoriesMetaData->meta_description,
            'meta_keyword'=>$categoriesMetaData->meta_keyword,
            'site' => $this->site,
            "page"=>$this->site->name,
            'noindex_follow'=>$categoriesMetaData->noindex_follow,
        ]);
        }else{
            return view('livewire.dating-template-profile1.categories',compact("site_data","categoriesBySite","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
        }
    }
}
