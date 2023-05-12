<?php

namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\Banner;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component {
    use WithPagination;
    public $title;
    public $site;
    public $bannersPC;
    public $bannersMovile;

    protected $domain;
    protected $category;
    protected $website;
    protected $indexPC = 0;
    protected $indexMovile = 0;
    protected $paginationTheme = 'bootstrap';

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
        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,1,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,1,1,true);
    }

    public function render() {
        $site_data = $this->website;
        $blogsRandom = Article::getBlogsbyRandom(18,$this->site->id,true);
        $domain = $this->domain;
        return view('livewire.dating-template-profile1.blog',compact("site_data","blogsRandom","domain"))->layout('layouts.datingTemplateProfile1', ['meta_title'=>"Blogs",'title' => $this->title, 'site' => $this->site, 'page' => $this->site->name,"noindex_follow"=>true]);
    }
}
