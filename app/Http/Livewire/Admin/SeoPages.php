<?php
/***
 * @author Antonio Razo
 */
namespace App\Http\Livewire\Admin;

use App\Jobs\CreateSEOPagesJob;
use App\Models\Category;
use App\Models\City;
use App\Models\SeoPage;
use App\Models\SeoPages as ModelsSeoPages;
use App\Models\Site;
use App\Models\Template;
use App\Spintax;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Str;

class SeoPages extends Component
{
    use WithPagination;
    public $title;
    public $pagination = 25;
    public $search = "";
    public $category;
    public $meta_title;
    public $meta_description;
    public $title_seo;
    public $description_top;
    public $description_buttom;
    public $text_infront_right;
    public $text_infront_left;
    public $text_city_nearby;
    public $activeSeoPage = true;
    public $showMetadata = false;
    public $msgMetadata;
    public $countries = array(
        "1" => "Netherlands",
        "2" => "Belguim",
        //"3" => "UK",
    );
    public $country = 1;
    public $siteFilter;
    public $cityFilter;
    public $seoPageIdToDelete;
    public $textModalError;
    public $SeoPageToUpdateId;
    public $SeoPageToUpdateCategoryId;
    public $SeoPageToUpdateSiteId;
    public $SeoPageToUpdateCityId;
    public $SeoPageToUpdateMetaTitle;
    public $SeoPageToUpdateMetaDescription;
    public $SeoPageToUpdateTitle;
    public $SeoPageToUpdateDescriptionTop;
    public $SeoPageToUpdateDescriptionButtom;
    public $SeoPageToUpdateTextInfrontLeft;
    public $SeoPageToUpdateTextInfrontRight;
    public $SeoPageToUpdateActive;
    public $message = array(
        "update" => array(
            "success" => array(
                "msg" => "The seo page was updated successfully",
                "show" => false
            ),
            "error" => array(
                "msg" => "The seo page couldn't be updated",
                "show" => false
            )),
        "insert" => array(
            "success" => array(
                "msg" => "The seo pages were add successfully",
                "show" => false
            ),
            "error" => array(
                "msg" => "it is possible that some data couldn't be inserted",
                "show" => false
            ))
    );
    
    protected $paginationTheme = 'bootstrap';

    ////// show and clean modals
    /***
     * show modal to create a seo page
     * @return void
     */
    public function modalCreateSeoPage():void
    {
        $this->message["insert"]["success"]["show"] = false;
        $this->message["insert"]["error"]["show"] = false;
        $this->dispatchBrowserEvent('modalCreateSeoPage');
    }

    /***
     * clean the updating modal
     * @return void
     */
    public function cleanModal():void
    {
        $this->message["update"]["success"]["show"] = false;
        $this->message["update"]["error"]["show"] = false;
    }

    /***
     * clean the inserting modal
     * @return void
     */
    public function cleanInputsByModal():void
    {
        $this->meta_title = "";
        $this->meta_description = "";
        $this->title_seo = "";
        $this->text_infront_left = "";
        $this->text_infront_right = "";
        $this->text_city_nearby = "";
        $this->activeSeoPage = true;
        $this->showMetadata = false;
        $this->resetErrorBag();
    }

    /***
     * this method transform the data to be stored
     * @methods 
     *      $show = if is necesary to generate example of how the data is going to be stored
     *      $stand = city to strore
     *      $stand = province to strore
     * 
     * @return void
     */
    public function generateMetadata(bool $show = true,$stad = null,$province = null)
    {
        $this->validate([
            "category" => "required",
            "meta_title" => "required",
            "meta_description" => "required",
            "title_seo" => "required",
            "description_top" => "required",
            "text_infront_left" => "required",
            "text_infront_right" => "required",
            "text_city_nearby" => ['required', 'regex:#\[(.*?)\]#'],
            "siteFilter" => "required"
        ]);
        $category = Category::find($this->category);
        $template = Template::getTemplateName($this->siteFilter);
        if (!isset($category) || !isset($template)) return $this->dispatchBrowserEvent('modalShowError',["msg"=>"The category or the template can't be finded"]);
        $stand = 'Amsterdam';
        $this->msgMetadata['meta_title'] = $this->filterTekst($this->meta_title,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['meta_description'] = $this->filterTekst($this->meta_description,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['title'] = $this->filterTekst($this->title_seo,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['description_top'] = $this->filterTekst($this->description_top,$stand,$this->text_city_nearby,$category->id,$category->url,$template->slug);
        $this->msgMetadata['description_buttom'] = $this->filterTekst($this->description_buttom,$stand,$this->text_city_nearby,$category->id,$category->url,$template->slug);
        $this->showMetadata = $show;
    }

    /***
     * transform and prepare the text to insert
     * @params 
     *      $text = text to transform
     *      $stad = city to complement the text
     *      $province = province to complement the text
     */
    private function filterTekst($text, $stad = "", $seosteden="",$categoryId ="",$categoryUrl="",$seo_pagesSlug=""){
        $text = str_replace('data-cke-filler="true">','',$text);
        if (!empty($stad)):
            $text = str_replace('{city}', $stad, $text);
        endif;
        $aText = explode(' ',$text);
        $replacement = array();
        if (!empty($aText)):
            foreach($aText as $item){
                if(stristr( $item, '{') == true) {
                    $replacement[] = $item;
                }
            }
        endif;
        $seopages = '';
        $seopages = new ModelsSeoPages();
        if (!empty($replacement)):
            foreach($replacement as $item):
                $text = str_replace($item, $seopages::shortCodes($item, $seosteden,$categoryId,$categoryUrl,$seo_pagesSlug), $text);
            endforeach;
        endif;
        $spintax = new Spintax();
        $text = $spintax->process($text);
        return $text;
    }

    /***
     * transform and prepare the text to insert
     * @params 
     *      $text = text to transform
     */
    private function my_explode($str)
    {
        $ret = array(); 
        $in_parenths = 0; $pos = 0;
        for($i=0;$i<strlen($str);$i++)
        {
            $c = $str[$i];
            if($c == ' ' && !$in_parenths) {
                $ret[] = substr($str, $pos, $i-$pos);
                $pos = $i+1;
            }
            elseif($c == '{') $in_parenths++;
            elseif($c == '}') $in_parenths--;
        }
        if($pos > 0) $ret[] = substr($str, $pos);
        return $ret;
    }

    /***
     * set the id of the seo page to be deleted and show the modal to confirm the deleting
     * @params
     *      int $seoPageId -> id of the seo to be deleted
     * @return void
     */
    public function modalDelete(int $seoPageId):void
    {
        $this->seoPageIdToDelete = $seoPageId;
        $this->dispatchBrowserEvent('modalDeleteSeoPage');
    }

    ///// CREATE

    /***
     * insert a seo page for all the cities in the country selected,show a message for error o for success
     * @return void
     */
    public function addSeoPage():void
    {
        $active = ($this->activeSeoPage)?1:0;
        set_time_limit(0);
        $counter = 0;
        foreach (City::where("country_id",$this->country)->pluck("id") as $idCity) {
            $city_province = City::select(DB::raw("cities.name as cityName"),DB::raw("provinces.name as provinceName"))->join("provinces","cities.province_id","=","provinces.id")->find($idCity);
            $this->generateMetadata(false,$city_province->cityName,$city_province->provinceName);
            if($this->insertSeoPageToDB($this->category,$this->siteFilter,$idCity,$this->msgMetadata["meta_title"],$this->msgMetadata["meta_description"],$this->msgMetadata["title"],$this->msgMetadata["description_top"],$this->msgMetadata["description_buttom"],$this->text_infront_left,$this->text_infront_right,$active))$counter++;
        }
        ($counter >0 && $counter === count(City::where("country_id",$this->country)->pluck("id")))?$this->message["insert"]["success"]["show"] = true:$this->message["insert"]["error"]["show"] = true;
        $this->dispatchBrowserEvent('hideModalInsertSeoPage');
        $this->cleanInputsByModal();
    }

    private function insertSeoPageToDB($category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active):bool
    {
        try {
            return DB::insert("INSERT INTO seo_pages ". 
                "(category_id,site_id,city_id,meta_title,meta_description,title,description_top,description_buttom,text_infront_left,text_infront_right,active) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
                [$category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active]);
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }

    public function addSeoPageAsBgTask():void
    {
        $this->validate([
            "category" => "required",
            "meta_title" => "required",
            "meta_description" => "required",
            "title_seo" => "required",
            "description_top" => "required",
            "text_infront_left" => "required",
            "text_infront_right" => "required",
            "text_city_nearby" => ['required', 'regex:#\[(.*?)\]#'],
            "siteFilter" => "required"
        ]);
        $active = ($this->activeSeoPage)?1:0;
        CreateSEOPagesJob::dispatch($this->country,$this->category,$this->siteFilter,$this->msgMetadata,$active,$this->description_top,$this->description_buttom,$this->meta_title,$this->meta_description,$this->title_seo,$this->text_infront_left,$this->text_infront_right,$this->text_city_nearby);
        $this->dispatchBrowserEvent('hideModalInsertSeoPage');
        $this->cleanInputsByModal();
    }

    


    ////// UPDATE
    /***
     * check if the seo page exists and get the data of it, if exists the seo show a modal with the information, if not show a modal with an error.
     * @method $idSeoPage = the id of the seo page to be updated
     */
    public function modalUpdateSeoPage(int $idSeoPage)
    {
        $userToUpdateData = SeoPage::find($idSeoPage);
        if($userToUpdateData === null) return $this->dispatchBrowserEvent('modalShowError',["msg"=>"The seo page couldn't be updated"]);
        $this->SeoPageToUpdateId = $userToUpdateData->id;
        $this->SeoPageToUpdateCategoryId = $userToUpdateData->category_id;
        $this->SeoPageToUpdateSiteId = $userToUpdateData->site_id;
        $this->SeoPageToUpdateCityId = $userToUpdateData->city_id;
        $this->SeoPageToUpdateMetaTitle = $userToUpdateData->meta_title;
        $this->SeoPageToUpdateMetaDescription = $userToUpdateData->meta_description;
        $this->SeoPageToUpdateTitle = $userToUpdateData->title;
        $this->SeoPageToUpdateDescriptionTop = $userToUpdateData->description_top;
        $this->SeoPageToUpdateDescriptionButtom = $userToUpdateData->description_buttom;
        $this->SeoPageToUpdateTextInfrontLeft = $userToUpdateData->text_infront_left;
        $this->SeoPageToUpdateTextInfrontRight = $userToUpdateData->text_infront_right;
        $this->SeoPageToUpdateActive = $userToUpdateData->active;
        $this->dispatchBrowserEvent('modalUpdateSeoPage',["description_top"=>$this->SeoPageToUpdateDescriptionTop,"description_buttom"=>$this->SeoPageToUpdateDescriptionButtom]);
    }

    /***
     * update the seo page with the new information, if it's or it isn't posible to update show a message.
     */
    public function updateSeoPage()
    {
        $this->message["update"]["success"]["show"] = false;
        $this->message["update"]["error"]["show"] = false;
        $seoPage = SeoPage::find($this->SeoPageToUpdateId);
        if($seoPage === null) return $this->dispatchBrowserEvent('modalShowError',["msg"=>"The seo page couldn't be updated"]);
        $seoPage->category_id = $this->SeoPageToUpdateCategoryId;
        $seoPage->site_id = $this->SeoPageToUpdateSiteId;
        $seoPage->city_id = $this->SeoPageToUpdateCityId;
        $seoPage->meta_title = $this->SeoPageToUpdateMetaTitle;
        $seoPage->meta_description = $this->SeoPageToUpdateMetaDescription;
        $seoPage->title = $this->SeoPageToUpdateTitle;
        $seoPage->description_top = $this->SeoPageToUpdateDescriptionTop;
        $seoPage->description_buttom = $this->SeoPageToUpdateDescriptionButtom;
        $seoPage->text_infront_left = $this->SeoPageToUpdateTextInfrontLeft;
        $seoPage->text_infront_right = $this->SeoPageToUpdateTextInfrontRight;
        $seoPage->active = $this->SeoPageToUpdateActive;
        try {
            $seoPage->save();
            $this->message["update"]["success"]["show"] = true;
        } catch (\Throwable $th) {
            dd($th);
            $this->message["update"]["error"]["show"] = true;
        }
    }

    ///// DELETE

    /***
     * delete the seo pages, show a error if it isn't be possible delete the seo
     * @return void
     */
    public function deleteSeoPage():void
    {
        $this->dispatchBrowserEvent("hideModal");
        if(!SeoPage::destroy($this->seoPageIdToDelete)){
            $this->dispatchBrowserEvent('modalShowError',["msg"=>"The seo page couldn't be deleted"]);
        }
        $this->seoPageIdToDelete = "";
    }

    public function mount(Request $request) {   
        $this->title = trans('Seo pages');
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $citiesByCountry = City::select("id","name")->where("country_id",$this->country)->get();
        $categories = Category::all("id","name");
        $allSites = Site::select("id","name")->orderBy("name")->get();
        $allSeoPages = SeoPage::seoPagesByFilters( $this->country,$this->cityFilter,$this->category,$this->siteFilter,$this->pagination);
        return view('livewire.admin.seo-pages',compact("allSeoPages","categories","citiesByCountry","allSites"))->layout('layouts.panel');
    }
}