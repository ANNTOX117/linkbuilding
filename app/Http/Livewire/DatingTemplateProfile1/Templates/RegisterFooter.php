<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Template;
use App\Models\TemplatesExtraSetting;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class RegisterFooter extends Component
{
    public $templateExtrasSettings;
    public $email;
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
    
    public function mount($siteId)
    {
        $this->domain  = domain();
        $this->category = subdomain();
        $this->category = '';
        //$this->domain = "hotpaginas.mx";
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site = $this->website;
        $this->templateExtrasSettings = Template::getContentButtomRegisterByTemplateAndSite($siteId);
    }
    public function render()
    {
        $content_buttom_register = $this->site->footerText;
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.register-footer',compact("content_buttom_register"));
    }
}
