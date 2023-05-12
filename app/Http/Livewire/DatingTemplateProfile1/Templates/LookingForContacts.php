<?php

namespace App\Http\Livewire\DatingTemplateProfile1\Templates;

use App\Models\Article;
use Livewire\Component;

class LookingForContacts extends Component
{
    public $idSite;

    public function mount($idSite)
    {
        $this->idSite = $idSite;
    }
    public function render()
    {
        $randomProfiles = Article::getArticlesBySiteWithProvincie($this->idSite,30);
        return view('linksbuildingNew.livewire.dating-template-profile1.templates.looking-for-contacts',compact("randomProfiles"));
    }
}
