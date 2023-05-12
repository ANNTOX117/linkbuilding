<?php
/***
 * @author Antonio Razo
 */
namespace App\Http\Livewire\Admin;

use App\Models\Banner;
use App\Models\BannerSite;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Banners extends Component
{
    use WithFileUploads;
    public $title;
    public $page = 0;
    public $site;
    public $template;
    public $newImageBanner;
    public $newBannerRedirect;
    public $textModalError;
    public $allBannersBySite;
    public $type_banner = 0;
    public $message = array(
        "insert" => array(
            "success" => array(
                "msg" => "The banner was added successfully",
                "show" => false
            ),
            "error" => array(
                "msg" => "it is possible that some data couldn't be inserted",
                "show" => false
            )),
        "delete" => array(
            "success" => array(
                "msg" => "The banner was deleted successfully",
                "show" => false
            ),
            "error" => array(
                "msg" => "it is possible that some data couldn't be deleted",
                "show" => false
            ))
    );

    public function updateBannerOrder($bannerOrdered)
    {
        foreach ($bannerOrdered as $banner) {
            try {
                $bannerToFix = BannerSite::find($banner['value']);
                $bannerToFix->order_banner = (int)$banner['order'];
                $bannerToFix->save();
            } catch (\Throwable $th) {
                dd("i can't fix", $th, $banner);
            }
        }
    }
    public function mount() {   
        $this->title = trans('Banners by site');
    }

    public function insertBanner()
    {
        $this->setMessagesClean();
        $this->validate(
            [
                "newImageBanner" => "mimes:jpeg,jpg,png,gif|required",
                "newBannerRedirect" => "required|url",
                "site" =>"required"
            ]
        );
        $this->template = Template::getTemplateName($this->site);
        if(!isset($this->template)) dd("fail");
        $path = "public/templates/".$this->template->slug."/banners/";
        try {
            $nameImageBanner = "banner_".$this->page."_".time().".".$this->newImageBanner->getClientOriginalExtension();
            $this->newImageBanner->storeAs($path,$nameImageBanner);
        } catch (\Throwable $th) {
            dd($th);
        }
        $banner = new Banner();
        $path = str_replace("public/","/storage/",$path);
        $banner->url_file = $path.$nameImageBanner;
        $banner->url_redirect = $this->newBannerRedirect;
        $banner->type = $this->type_banner;
        try {
            $banner->save();
        } catch (\Throwable $th) {
            dd($th);
        }
        $order_banner = count(BannerSite::getAllBannersBySite($this->site,$this->page)) + 1;
        $bannerSite = new BannerSite();
        $bannerSite->banner = $banner->id;
        $bannerSite->site = $this->site;
        $bannerSite->page = $this->page;
        $bannerSite->order_banner = $order_banner;
        try {
            $bannerSite->save();
        } catch (\Throwable $th) {
            dd($th);
        }
        $this->cleanInputs();
        $this->message["insert"]["success"]["show"] = true;
    }

    private function setMessagesClean(){
        $this->message["insert"]["success"]["show"] = false;
        $this->message["insert"]["error"]["show"] = false;
        $this->message["delete"]["success"]["show"] = false;
        $this->message["delete"]["error"]["show"] = false;
    }
    private function cleanInputs()
    {
        $this->newBannerRedirect = "";
        $this->newImageBanner = "";
        $this->dispatchBrowserEvent('cleanInputsFile');
    }

    public function deleteBanner($idBanner)
    {
        $this->setMessagesClean();
        try {
            $banner = Banner::find(BannerSite::find($idBanner)->banner);
            $path_image = str_replace("storage","public",$banner->url_file);
            BannerSite::destroy($idBanner);
            Banner::destroy($banner->id);
            Storage::delete($path_image);
            $this->message["delete"]["success"]["show"] = true;
        } catch (\Throwable $th) {
            $this->message["delete"]["error"]["show"] = true;
        }        
    }
    public function render() {
        $allSites = Site::select("id","name")->orderBy("name")->get();
        if (isset($this->site) && isset($this->page)) {
            $this->allBannersBySite = BannerSite::getAllBannersBySite($this->site,$this->page);
        }
        return view('livewire.admin.banners',compact("allSites"))->layout('layouts.panel');
    }
}