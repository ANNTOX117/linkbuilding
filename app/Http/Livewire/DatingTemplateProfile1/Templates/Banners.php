<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Banner;
use App\Models\Template;
use Livewire\Component;

class Banners extends Component
{
    public $img;
    public $class;
    public $redirect;
    public $dataTemplate;

    public function mount($siteId,$page,$type=null,$class="",$width="",$height="")
    {
        $page = trim(strtolower($page));
        switch ($page) {
            case 'home':
                $page = 0;
            break;
            case 'blog':
                $page = 1;
            break;
            default:
                $page = 0;
            break;
        }

        $this->dataTemplate = Banner::getBannersBySite($siteId,$page);
        $this->class = $class;
        switch ($type) {
            case 'large':
                $this->img = $this->dataTemplate->banner_large_image;
                $this->redirect = $this->dataTemplate->banner_large_redirect;
            break;
            case 'compact':
                $this->img = $this->dataTemplate->banner_compact_image;
                $this->redirect = $this->dataTemplate->banner_compact_redirect;
            break;
            
            default:
                # code...
                break;
        }
    }
    public function render()
    {
        $settingsTemplate = $this->dataTemplate; 
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.banners',compact("settingsTemplate"));
    }
}
