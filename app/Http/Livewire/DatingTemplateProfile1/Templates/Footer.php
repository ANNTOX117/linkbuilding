<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Template;
use App\Models\TemplatesExtraSetting;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Footer extends Component
{
    public $templateExtrasSettings;
    public $domain;
    public $website;
    public $site;

    public function mount($domain,$siteId)
    {
        $this->domain = $domain;
        $this->website  = \App\Models\Site::get_info($this->domain);
        if(!empty($this->category)) $this->domain = $this->category . '.' . $this->domain;
        if(empty($this->website)) abort(404);
        $this->site = $this->website;
        $this->templateExtrasSettings = Template::getContentFooterByTemplateAndSite($siteId);
    }
    public function render()
    {
        $footer_content_first_part = $this->site->footer;
        $footer_content_second_part = $this->site->footer2;
        $footer_content_third_part = $this->site->footer3;        
        $footer_content_four_part = $this->site->footer4;
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.footer',compact("footer_content_first_part","footer_content_second_part","footer_content_third_part","footer_content_four_part"));
    }
}
