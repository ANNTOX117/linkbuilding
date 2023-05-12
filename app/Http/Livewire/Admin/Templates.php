<?php

namespace App\Http\Livewire\Admin;

use App\Models\Site;
use App\Models\Template;
use App\Models\TemplatesExtraSetting;
use App\Models\TemplateSite;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Templates extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $title;
    public $titleText;
    public $pagination;
    public $search;
    public $nameTemplate;
    public $sites = [];
    protected $paginationTheme = 'bootstrap';
    public $confirm;
    public $idTemplate;
    public $sitesByTemplate;
    public $contentTopRegister;
    public $contentButtomRegister;
    public $contentFooterLeft;
    public $contentFooterCenter;
    
    public $contentFooterRight;
    public $imageTop;
    public $imageButtom;
    public $templateIdToUpdate;
    public $template;
    public $message = array(
        "insert" => [
            "success"=>[
                "msg" => "New template inserted",
                "show" => false
            ],
            "error"=>[
                "msg" => "The template couldn't be inserted",
                "show" => false
            ]
        ],
        "delete" => [
            "success"=>[
                "msg" => "The template was deleted",
                "show" => false
            ],
            "error"=>[
                "msg" => "The template couldn't be deleted",
                "show" => false
            ]
        ],
        "update" => [
            "success"=>[
                "msg" => "The template was updated",
                "show" => false
            ],
            "error"=>[
                "msg" => "The template couldn't be updated",
                "show" => false
            ]
        ],
        "find" => [
            "not_find"=>[
                "msg" => "It's not possible to find the template. Try again.",
                "show" => false
            ]
        ],
        "add_settings" => [
            "success"=>[
                "msg" => "The settings were inserted",
                "show" => false
            ],
            "error"=>[
                "msg" => "The settings couldn't be inserted",
                "show" => false
            ]
        ],
    );

    public function cleanInputs(){
        $this->nameTemplate = "";
        $this->sites = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function setMessageFalse(){
        $this->resetErrorBag();
        $this->resetValidation();
        $this->message["insert"]["success"]["show"] = false;
        $this->message["insert"]["error"]["show"] = false;
        $this->message["delete"]["success"]["show"] = false;
        $this->message["delete"]["error"]["show"] = false;
        $this->message["update"]["success"]["show"] = false;
        $this->message["update"]["error"]["show"] = false;
        $this->message["find"]["success"]["show"] = false;
        $this->message["add_settings"]["success"]["show"] = false;
        $this->message["add_settings"]["error"]["show"] = false;
    }
    /***
     * CRUD
     */
    //create
    public function modalAddTemplate(){
        $this->setMessageFalse();
        $this->dispatchBrowserEvent('showAddTemplate');
    }

    public function insertTemplate(){
        $this->validate([
           "nameTemplate" => "required|unique:templates,name",
            "sites" => "required|array|min:1"
        ]);
        $idTemplate = Template::insertTemplate($this->nameTemplate);
        foreach ($this->sites as $site){
            TemplateSite::insertTemplateBySite($idTemplate,$site);
        }
        $this->message["insert"]["success"]["show"] = true;
        $this->cleanInputs();
        $this->sites = [];
        $this->dispatchBrowserEvent('dismissModal');
    }

    public function confirm($id) {
        $this->setMessageFalse();
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function delete() {
        Template::destroy($this->confirm);
        $this->confirm   = '';
        $this->message["delete"]["success"]["show"] = true;
    }

    public function modalEditTemplate($idTemplate){
        $this->setMessageFalse();
        $this->dispatchBrowserEvent('updateModalTemplate');
        $this->idTemplate = $idTemplate;
        $this->sitesByTemplate = TemplateSite::getAllSitesByTemplate($this->idTemplate);
        $this->dispatchBrowserEvent('resetCategories', ['options' =>  $this->sitesByTemplate]);
    }

    public function updateTemplate(){
        TemplateSite::where("template_id",$this->idTemplate)->delete();
        foreach ($this->sitesByTemplate as $site){
            TemplateSite::insertTemplateBySite($this->idTemplate,$site);
        }
        $this->message["update"]["success"]["show"] = true;
    }

    public function modalMetaDataTemplate($templateId)
    {
        $this->setMessageFalse();
        $this->templateIdToUpdate = $templateId;
        try {
            $this->template = Template::findOrFail($templateId);
        } catch (\Throwable $th) {
            return $this->dispatchBrowserEvent('errorModal');
        }
        try {
            $extraSettings = TemplatesExtraSetting::where("template_id",$templateId)->firstOrFail();
        } catch (\Throwable $th) {
            return $this->dispatchBrowserEvent('addMetadata');
        }
        $this->contentTopRegister = $extraSettings->content_top_register;
        $this->contentButtomRegister = $extraSettings->content_buttom_register;
        $this->contentFooterLeft = $extraSettings->footer_content_first_part;
        $this->contentFooterCenter = $extraSettings->footer_content_second_part;
        $this->contentFooterRight = $extraSettings->footer_content_third_part;
        $this->imageTop = $extraSettings->image_top_register;
        $this->imageButtom = $extraSettings->image_categories_regions;
        return $this->dispatchBrowserEvent('addMetadata',[
            "contentTopRegister" => $this->contentTopRegister,
            "contentButtomRegister" => $this->contentButtomRegister,
            "contentFooterLeft" => $this->contentFooterLeft,
            "contentFooterCenter" => $this->contentFooterCenter,
            "contentFooterRight" => $this->contentFooterRight,
            "contentImageTop" => $this->imageTop,
            "contentImageButtom" => $this->imageButtom
        ]);
    }

    public function addExtraSettings()
    {
        if ($this->contentTopRegister === "<p><br></p>") {
            $this->contentTopRegister = "";
        }
        $this->validate([
            "contentTopRegister" => "required",
        ]);
        //$templateName = str_replace("-","_",Str::slug());
        $path = "public/templates/".$this->template->slug."/";
        try {
            if (gettype($this->imageTop) !== "string"){
                $nameImageTop = "imageTop.".$this->imageTop->getClientOriginalExtension();
                $this->imageTop->storeAs($path,$nameImageTop);
            }else{
                $nameImageTop = basename($this->imageTop);
            }
            if (gettype($this->imageButtom) !== "string"){
                $nameImageButtom = "imageButtom.".$this->imageButtom->getClientOriginalExtension();
                $this->imageButtom->storeAs($path,$nameImageButtom);
            }else{
                $nameImageButtom = basename($this->imageButtom);
            }
        } catch (\Throwable $th) {
            return $this->dispatchBrowserEvent('errorModal');
        }
        
        $extraSettings = TemplatesExtraSetting::where("template_id",$this->templateIdToUpdate)->first();
        if (!isset($extraSettings)) {
            $extraSettings = new TemplatesExtraSetting;
            $extraSettings->template_id = $this->templateIdToUpdate;
        }
        $path = str_replace("public/","/storage/",$path);
        $extraSettings->content_top_register = $this->contentTopRegister;
        $extraSettings->image_top_register = $nameImageTop === ""?null:$path.$nameImageTop;
        $extraSettings->content_buttom_register = $this->contentButtomRegister;
        $extraSettings->image_categories_regions = $nameImageButtom === ""?null:$path.$nameImageButtom;
        $extraSettings->footer_content_first_part = $this->contentFooterLeft;
        $extraSettings->footer_content_second_part = $this->contentFooterCenter;
        $extraSettings->footer_content_third_part = $this->contentFooterRight;
        
        try {
            $extraSettings->save();
            $this->message["add_settings"]["success"]["show"] = true;
            $this->message["add_settings"]["error"]["show"] = false;
        } catch (\Throwable $th) {
            $this->message["add_settings"]["success"]["show"] = false;
            $this->message["add_settings"]["error"]["show"] = true;
        }
    }

    public function mount() {
        $this->title = trans('Templates');
    }

    public function render()
    {
        $allTemplates = Template::withFilterPagination("id","desc",$this->pagination,$this->search);
        $allSites = Site::all("id","name");
        return view('linksbuildingNew.livewire.admin.templates',compact("allTemplates","allSites"))->layout('layouts.panel');
    }
}
