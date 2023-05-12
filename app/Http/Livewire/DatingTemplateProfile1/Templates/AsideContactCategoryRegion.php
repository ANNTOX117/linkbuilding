<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\ArticleAttribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Province;
use Livewire\Component;

class AsideContactCategoryRegion extends Component
{
    public $idSite;
    public $iAm;
    public $sex;
    public $province;
    public $banners;

    protected $index = 0;

    public function getNextElement($arr)
    {
        $result = $arr[$this->index];
        $this->index = ($this->index + 1) % count($arr);
        return $result;
    }

    public function mount($idSite)
    {
        $this->idSite = $idSite;
        $this->banners = Banner::getBannersBySite($idSite,4,0,true);
    }
    public function render()
    {
        $allProvinces = Province::all("id","name");
        $getCountryId = ArticleAttribute::select("value")->where("name","country_id")->first();
        $categories = Category::categoriesBySite($this->idSite);
        $regions = Province::where("country_id",$getCountryId->value)->orderBy("name")->get();
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.aside-contact-category-region',compact("allProvinces","categories","regions"));
    }
}
