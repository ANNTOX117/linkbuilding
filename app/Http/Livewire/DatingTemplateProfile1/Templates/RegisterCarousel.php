<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Template;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegisterCarousel extends Component
{
    public $idSite;
    public $templateExtrasSettings;
    public $email;
    public $url_image_carrusel;
    public $domain;
    public $category;
    public $website;
    public $site;


    public function redirectToLandpage()
    {
        $this->validate([
            "email" => "required|email"
        ]);
        $url = "https://www.flirtonline.nl/registreer?pi=".domain()."&email=$this->email";
        return redirect()->away($url);
    }

    public function updated($name, $value)
    {
        $this->resetValidation($name);
        $this->resetErrorBag($name);
    }
    public function mount($idSite)
    {
        
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site = $this->website;
        $this->templateExtrasSettings = Template::getContentByTemplateBySite($idSite);
        $this->idSite = $idSite;
        // list all filenames in given path
        $allFiles = Storage::files('public/slider/');
        // filter the ones that match the filename.* 
        $name_file = $this->idSite."_backslide";
        if (is_array($allFiles) && count($allFiles) > 0) {
            $matchingFiles = preg_grep('/^public\/slider\/'.preg_quote($name_file, '/').'\./', $allFiles);
            if (count($matchingFiles) > 0) {
                $matchingFiles = array_values($matchingFiles)[0];
            } else {
                // handle the case where no matching files are found
                $matchingFiles = "";
            }
        } else {
            $matchingFiles = "";
            // handle the case where $allFiles is not an array or is empty
        }
        $this->url_image_carrusel = $this->site->slider_background??$this->site->slider_background;
    }

    public function render()
    {        
        $content_top_register = $this->site->headerText;//$this->templateExtrasSettings->content_top_register;
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.register-carousel',compact("content_top_register"));
    }
}
