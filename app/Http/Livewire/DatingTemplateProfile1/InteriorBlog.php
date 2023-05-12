<?php

namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\Banner;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class InteriorBlog extends Component {
    public $pageName = 'page';

    public $title;
    public $site;
    public $blog_by_url;
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

    public function mount() {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        $this->site      = $this->website;
        $this->blog_by_url = Article::getBlogByUrl(Request::segment(2),$this->site->id);

        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website) || !isset($this->blog_by_url)) abort(404);
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,1,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,1,1,true);
    }

    public function render() {
        $site_data = $this->website;
        $domain = $this->domain;
        $metaDataBlog = Article::getMetaDataBlogByUrl(Request::segment(2));
        if (isset($metaDataBlog) && (array)$metaDataBlog)  {
            return view('livewire.dating-template-profile1.interior-blog',compact("site_data","domain"))->layout('layouts.datingTemplateProfile1', 
            [
                'meta_title' => $metaDataBlog->meta_title,
                'meta_description' => $metaDataBlog->meta_description,
                'meta_keyword' => $metaDataBlog->keywords,
                'title' => $this->title,
                'site' => $this->site, 
                'page' => $this->site->name,
                'noindex_follow'=>true
            ]);
        }else{
            return view('livewire.dating-template-profile1.interior-blog',compact("site_data","domain"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
        }
    }
}
