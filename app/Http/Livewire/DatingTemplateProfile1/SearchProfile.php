<?php

namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Category as ModelsCategory;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\ArticleAttribute;
use App\Models\Banner;
use Illuminate\Support\Facades\Request;

class SearchProfile extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $title;
    public $site;
    public $name;
    public $sex;
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

    public function gotoPage($page)
    {
        $this->setPage($page);
        $this->dispatchBrowserEvent('reloadPage');
    }

    public function mount() {
        if(isset($_POST["sex"])){
            $this->sex = $_POST["sex"];
            $this->province = $_POST["province"];
        }else{
            $this->name = Request::segment(2);
        }
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        if (Request::segment(2) == null && !isset($_POST["sex"])) redirect()->route('home'); 
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,8,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,8,1,true);
    }

    public function render(){
        $site_data = $this->website;
        $profiles = isset($this->province)?ArticleAttribute::getProfilesBySearch($this->site->id,30,$this->sex,$this->province):ArticleAttribute::getProfilesBySearch($this->site->id,30,$this->name);
        $domain = $this->domain;
        return view('livewire.dating-template-profile1.search-profile',compact("site_data","profiles","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'noindex_follow'=>false,'site' => $this->site, 'page' => $this->site->name]);
    }
}
