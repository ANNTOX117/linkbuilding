<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Category;
use App\Models\Province;
use App\Models\ArticleAttribute;
use App\Models\City;
use App\Models\SeoPage;
use App\Models\Template;
use App\Models\TemplatesExtraSetting;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CategoriesAndProvince extends Component
{
    public $idSite;
    public $dinamic;
    public $seoPage;
    public $templateExtrasSettings;
    public $domain;
    public $category;
    public $website;
    public $site;

    public function mount($idSite,$dinamic=false,$seoPage = null)
    {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        //$template = Template::where("slug",explode('/', Request::path())[0])->first();
        $template = Template::getTemplateActiveBySite($idSite);
        if(isset($template)){
            $this->templateExtrasSettings = TemplatesExtraSetting::where("template_id",$template->id)->first();
        }
        $this->idSite = $idSite;
        $this->dinamic = $dinamic;
        $this->seoPage = $seoPage;
    }
    public function render()
    {
        // list all filenames in given path
        $allFiles = Storage::files('public/slider/');
        // filter the ones that match the filename.* 
        $name_file = $this->idSite."_category";
        $matchingFiles = preg_grep('/^public\/slider\/'.$name_file.'\./', $allFiles);
        $url_image_categories_regions = $this->website->slider_category??$this->templateExtrasSettings->image_categories_regions;
        if ($this->dinamic) {
            $city_path = last(Request::segments());
            $city = City::where("path",$city_path)->first();
            $randomCities = City::getRandomCities(10,$city->id);
            $nearlyCities = City::getNearCities($city->id,50,10);
            $text_infront_left = $this->seoPage->text_infront_left;
            $text_infront_right = $this->seoPage->text_infront_right;
            return view('linksbuildingNew.livewire.dating-template-profile1.templates.categories-and-province',compact("randomCities","nearlyCities","url_image_categories_regions","text_infront_left","text_infront_right"));
        }else{
            $getCountryId = ArticleAttribute::select("value")->where("name","country_id")->first();
            $categories = $categories = Category::categoriesBySite($this->idSite);
            $provinces = Province::where("country_id",$getCountryId->value)->orderBy("name")->get();
            return view('linksbuildingNew.livewire.dating-template-profile1.templates.categories-and-province',compact("categories","provinces","url_image_categories_regions"));
        }
    }
}
