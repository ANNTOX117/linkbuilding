<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use Livewire\Component;
use App\Models\Site;

class Menu extends Component
{
    public $nameToSeach;
    public $site;
    public function seachUser()
    {
        $stringCleaned = strip_tags(htmlspecialchars($this->nameToSeach));
        return redirect()->route('search.name',["name" => $stringCleaned]);
    }

    
    public function mount(Site $site)
    {
        $this->site = $site;
    }
    
    public function render()
    {
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.menu');
    }
}
