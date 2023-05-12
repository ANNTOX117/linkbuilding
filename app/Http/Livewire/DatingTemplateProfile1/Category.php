<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Category as ModelsCategory;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Banner;
use App\Models\MetaCategories;
use App\Models\SeoPage;
use Illuminate\Support\Facades\Request;

class Category extends Component {
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
    public $bannersPC;
    public $bannersMovile;
    public $seoPages;

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
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        $this->categoryUrl = ModelsCategory::select("categories.id","categories.name")->join('sites_categories_main', 'sites_categories_main.category', 'categories.id')->where("url",Request::segment(2))->first();
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website) || !isset($this->categoryUrl)) abort(404);
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,6,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,6,1,true);
        $this->seoPages = count(SeoPage::all());
    }

    public function render(){
        $site_data = $this->website;
        $ProfilesByCategory = Article::getArticlesByCategory($this->categoryUrl->id,30,true);//Article::where("category",$this->categoryUrl->id)->paginate(30);
        $randomUsers = Article::getArticlesBySite($this->site->id,30);
        $domain = $this->domain;
        $categoryMetadata = MetaCategories::where("url","/category")->where("category_id",$this->categoryUrl->id)->first();
        if (isset($categoryMetadata) && (array)$categoryMetadata)  {
            return view('livewire.dating-template-profile1.category',compact("site_data","ProfilesByCategory","domain","randomUsers","categoryMetadata"))->layout('layouts.datingTemplateProfile1', 
            [
                'title' => $this->title,
                'meta_title' => $categoryMetadata->meta_title,
                'meta_description' => $categoryMetadata->meta_description,
                'meta_keyword' => $categoryMetadata->meta_keyword,
                'site' => $this->site, 
                'page' => $this->site->name,
                'noindex_follow'=>$categoryMetadata->noindex_follow,
            ]);
        }else{
            return view('livewire.dating-template-profile1.category',compact("site_data","ProfilesByCategory","domain","randomUsers"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
        }
    }
}
