<?php
/***
 * @author Antonio Razo
 */
namespace App\Http\Livewire\DatingTemplateProfile1;
use App\Models\Article;
use App\Models\Banner;
use App\Models\City;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use App\Models\SeoPage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class SeoPageGenerated extends Component {
    use WithPagination;
    public $title;
    public $site;
    public $url;
    public $categoryName;
    public $categoryId;
    public $cityId;
    public $seoPage;
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
        $this->url = Request::segment(1);
        $url_exploded = explode("-",Request::segment(1));
        $this->categoryId = array_pop($url_exploded);
        $this->categoryName = reset($url_exploded);
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "bullsandhornsmedia.com";
        $this->website  = \App\Models\Site::get_info($this->domain);
        $this->cityId = City::where("path",Str::slug(Request::segment(2)))->first();
        if(empty($this->website) || $this->cityId === null) abort(404);
        $this->seoPage = SeoPage::getSeoPageByCategoryCitySite($this->categoryId,$this->cityId->id,$this->website->id);
        if($this->seoPage === null) abort(404);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->bannersPC = Banner::getBannersBySite($this->website->id,7,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,7,1,true);
    }

    public function render() {
        $site_data = $this->website;
        $domain = $this->domain;
        $this->website  = \App\Models\Site::get_info($this->domain);
        $meta_data = array(
            "meta_title" => $this->seoPage->meta_title,
            "meta_description" => $this->seoPage->meta_description
        );
        $city = City::find($this->cityId->id);
        $profilesByCity = Article::getArticlesByCityAndRadioPaginateLimit($city->lat,$city->lon,50,18,50);
        $domain = $this->domain;
        return view('livewire.dating-template-profile1.seo-page-generated',compact("site_data","domain","profilesByCity"))->layout('layouts.datingTemplateProfile1', ['title' => strip_tags($this->seoPage->title),'noindex_follow'=>false, 'site' => $this->site,"page"=>$this->site->name,"meta_info"=>$meta_data]);
    }
}