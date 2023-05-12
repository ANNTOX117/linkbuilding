<?php

namespace App\Http\Livewire\DatingTemplateProfile1;

use App\Models\Article;
use App\Models\ArticleAttribute;
use App\Models\Banner;
use App\Models\Review;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class InteriorProfile extends Component {

    public $pageName = 'page';

    public $title;
    public $site;
    public $profile_by_url;
    public $emailToRegister;
    public $msgToProfile;
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

    public function sendMessageModal()
    {
        $this->dispatchBrowserEvent("sendMessage");
    }

    public function sendMessageToProfile()
    {
        $this->validate([
            "emailToRegister" => "required|email",
            "msgToProfile" => "required",
        ]);
        
        $profile_id = ArticleAttribute::where("article_id",$this->profile_by_url->id)->where("name","profile_id")->first()->value;
        $url = "https://www.flirtonline.nl/registreer?pi=".domain()."&profile=$profile_id&email=$this->emailToRegister";
        return redirect()->away($url);
    }
    public function mount() {
        $this->profile_by_url = Article::where("url",Request::segment(2))->first();
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.nl";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category))$this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website) || !isset($this->profile_by_url))abort(404);
        $this->site      = $this->website;
        App::setLocale($this->site->languages->name ?? 'nl');
        $this->title = (!empty($this->category))?ucfirst($this->category):$this->site->meta_title;
        $this->bannersPC = Banner::getBannersBySite($this->website->id,5,0,true);
        $this->bannersMovile = Banner::getBannersBySite($this->website->id,5,1,true);
    }

    public function render() {
        $site_data = $this->website;
        $atributesByProfile = ArticleAttribute::select("name","value")->where("article_id",$this->profile_by_url->id)->get()->toArray();
        $domain = $this->domain;
        $site = \App\Models\Site::get_info($this->domain); 
        $reviews = Review::getReviewsByProfile($site->id,$this->profile_by_url->id);
        $profileMetadata = $this->profile_by_url;
        if (isset($profileMetadata) && (array)$profileMetadata)  {
            return view('livewire.dating-template-profile1.interior-profile',compact("site_data","atributesByProfile","domain","reviews"))->layout('layouts.datingTemplateProfile1', 
            [
                'title' => $profileMetadata->title,
                'meta_title' => $profileMetadata->title,
                'noindex_follow'=>true,
                'site' => $this->site, 
                'page' => $this->site->name
            ]);
        }else{
            return view('livewire.dating-template-profile1.interior-profile',compact("site_data","atributesByProfile","domain","reviews"))->layout('layouts.datingTemplateProfile1', ['title' => $this->title, 'site' => $this->site, 'page' => $this->site->name]);
        }
        
    }
}
