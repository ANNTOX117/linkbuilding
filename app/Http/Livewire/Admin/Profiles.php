<?php
namespace App\Http\Livewire\Admin;

use App\Jobs\InsertProfilesJob;
use App\Models\Article;
use App\Models\ArticleAttribute;
use App\Models\Category;
use App\Models\City;
use App\Models\Province;
use App\Models\Review;
use App\Models\Site;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Profiles extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title;
    public $idSite;
    public $idArticle;
    public $idCountry;
    public $categories;
    public $allSites;
    public $pagination;
    public $search = '';
    public $category;
    protected $paginationTheme = 'bootstrap';
    public $fileCsvProfiles;
    public $headers;
    public $pathImage;
    public $confirm;
    public $aboutMeToUpdate;
    public $cities;
    public $provinces;
    public $cityToUpdate;
    public $provinceToUpdate;
    public $photo;
    public $orderBy;
    public $stars = 3;
    public $text_review;
    public $getReviewsByProfile;
    public $writted_by;

    public function short($data){
        $this->orderBy = $data;
    }
    public $profileAttributes = array(
                "profile_image" => "",
                "name" => "",
                "age" => "",
                "looking_for" => "",
                "length" => "",
                "eyes_color" => "",
                "posture" => "",
                "cup_size" => "",
                "intimate_shaved" => "",
                "about_me" => "",
                "gender" => "",
                "profile_id" => ""
            );
    protected $validationAttributes = [
        'idCountry' => 'country',
        'idSite' => 'site',
    ];

    public $messageFileImport = array(
        "success" => array(
            "msg" => "The data was store successfully",
            "show" => false
        ),
        "success_bg" => array(
            "msg" => "The data is gonna be stored as a background",
            "show" => false
        ),
        "error" =>  array(
            "msg" => "The data wasn't inserted, try it again",
            "show" => false
        ),
        "error_dir" =>  array(
            "msg" => "The path doesnt exist, check the path",
            "show" => false
        ),
        "success_update" =>  array(
            "msg" => "The profile has been updated",
            "show" => false
        ),
        "error_update" =>  array(
            "msg" => "The profile hasn't been updated",
            "show" => false
        ),
        "success_review" =>  array(
            "msg" => "The review was added",
            "show" => false
        ),
        "error_review" =>  array(
            "msg" => "The review hasn't been inserted",
            "show" => false
        ),
    );
    public $importImage = true;
    public $countryIdByArticle;
    /***
     * CRUD
     */
    //CREATE

    public function uploadFormFile()
    {
        set_time_limit(0);
        if (!$this->importImage){
            $pathInServe = str_replace("storage","public",$this->pathImage);
            if (!Storage::exists($pathInServe))return $this->messageFileImport["error_dir"]["show"] = true;
        }
        $allDataByFile = array();
        $handle = fopen($this->fileCsvProfiles->path(), 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            foreach ($this->profileAttributes as $key=>$value) {
                $newArr[$key] = $data[$value];
            }
            $allDataByFile[] = $newArr;
        }
        unset($allDataByFile[0]); //delete headers of file
        $cities = City::inRandomOrder()->where("country_id", $this->idCountry)->get();
        $counterCity = 0;
        foreach ($allDataByFile as $file) {
            $url  = $file["profile_image"];
            $name = substr($url, strrpos($url, '/') + 1);
            $path = "/storage/profile_images/$this->idSite/$name";
            $idArticle = $this->importImage?Article::insertArticleByProfile($this->idSite, $this->category, $file["name"], $path, $file["about_me"]):Article::insertArticleByProfile($this->idSite, $this->category, $file["name"],$this->pathImage.$name, $file["about_me"]);
            $cityToBeStoredName = "";
            $cityToBeStoredId = "";
            $provinceName = "";
            if (!empty($cities[$counterCity])) {
                $cityToBeStoredName = $cities[$counterCity]->name;
                $cityToBeStoredId = $cities[$counterCity]->id;
                $provinceToBeStored = Province::where('id', $cities[$counterCity]->province_id)->first();
                if (!empty($provinceToBeStored)) {
                    $provinceName = $provinceToBeStored->name;
                }
            } else {
                $counterCity = '-1';
            }
            $headers = $this->profileAttributes;
            unset($headers["profile_image"]);
            foreach ($headers as $key=>$value){
                ArticleAttribute::insertArticleAttributes($this->idSite, $key, $file[$key], $idArticle);
            }
            if($file["profile_image"]){
                if ($this->importImage){
                    $contents = "";
                    try {
                        $contents = file_get_contents($url);
                    }catch (\Exception $e){
                        continue;
                    }
                    $path = "/public/profile_images/$this->idSite/$name";
                    ArticleAttribute::insertArticleAttributes($this->idSite, "profile_image",$path, $idArticle);
                    Storage::put($path, $contents);
                }else{
                    ArticleAttribute::insertArticleAttributes($this->idSite, "profile_image",$this->pathImage.$name, $idArticle);
                }
            }
            ArticleAttribute::insertArticleAttributes($this->idSite, "city", $cityToBeStoredName, $idArticle);
            ArticleAttribute::insertArticleAttributes($this->idSite, "city_id", $cityToBeStoredId, $idArticle);
            ArticleAttribute::insertArticleAttributes($this->idSite, "province", $provinceName, $idArticle);
            ArticleAttribute::insertArticleAttributes($this->idSite, "country_id", $this->idCountry, $idArticle);
            $counterCity++;
        }
        $this->cleanInputsByModal(false);
        $this->messageFileImport["success"]["show"] = true;
    }

    public function uploadFormFileBgTask()
    {
        if (!$this->importImage){
            $pathInServe = str_replace("storage","public",$this->pathImage);
            if (!Storage::exists($pathInServe))return $this->messageFileImport["error_dir"]["show"] = true;
        }
        $allDataByFile = array();
        $handle = fopen($this->fileCsvProfiles->path(), 'r');
        while (($data = fgetcsv($handle)) !== FALSE) {
            foreach ($this->profileAttributes as $key=>$value) {
                $newArr[$key] = $data[$value];
            }
            $allDataByFile[] = $newArr;
        }
        unset($allDataByFile[0]); //delete headers of file
        $cities = City::inRandomOrder()->where("country_id", $this->idCountry)->get();
        InsertProfilesJob::dispatch($allDataByFile,$this->idSite,$this->importImage,$this->category,$this->pathImage,$cities,$this->profileAttributes,$this->idCountry);
        $this->cleanInputsByModal(false);
        $this->messageFileImport["success_bg"]["show"] = true;
    }

    public function insertCsvDataProfiles()
    {
        $this->validate([
            'profileAttributes.profile_image' => "required",
            'profileAttributes.name' => "required",
            'profileAttributes.age' => "required",
            'profileAttributes.providence' => "required",
            'profileAttributes.looking_for' => "required",
            'profileAttributes.length' => "required",
            'profileAttributes.eyes_color' => "required",
            'profileAttributes.posture' => "required",
            'profileAttributes.cup_size' => "required",
            'profileAttributes.intimate_shaved' => "required",
            'profileAttributes.profile_id' => "required"
        ]);
    }

    public function modalAddProfiles() {
        if ($this->importImage){
            $this->validate([
                'idCountry' => 'required',
                'idSite' => 'required',
                'category' => "required"
            ]);
        }else{
            $this->validate([
                'idCountry' => 'required',
                'idSite' => 'required',
                'category' => "required",
                'pathImage' => 'required'
            ]);
        }
        $this->dispatchBrowserEvent('showAddProfiles');
    }

    public function validateCvsFile()
    {
        $this->validate([
            'fileCsvProfiles' => "required|mimes:csv,txt"
        ]);
        //$this->showForm = true;
    }
    //////

    //READ
    public function getCategoriesBySite(){
        $this->categories = Category::categoriesBySite($this->idSite);
    }
    function cleanInputsByModal(){
        foreach ($this->profileAttributes as $key=>$value){
            $this->profileAttributes["$key"] = "";
        }
        unset($this->headers);
        unset($this->text_review,$this->writted_by);
        $this->stars = 3;
        $this->messageFileImport["success"]["show"] = false;
        $this->messageFileImport["success_bg"]["show"] = false;
        $this->messageFileImport["error"]["show"] = false;
        $this->messageFileImport["error_dir"]["show"] = false;
        $this->messageFileImport["success_review"]["show"] = false;
        $this->messageFileImport["error_review"]["show"] = false;

    }
    //////

    //UPDATE
    public function modalEditArticle($idArticle){
        $this->messageFileImport["error_update"]["show"] = false;
        $this->messageFileImport["success_update"]["show"] = false;
        $this->idArticle = $idArticle;
        $this->countryIdByArticle = ArticleAttribute::select("value")->where("article_id",$idArticle)->where("name","country_id")->first()->value;
        $this->aboutMeToUpdate = ArticleAttribute::where("article_id",$idArticle)->where("name","about_me")->first()->value;
        $this->cityToUpdate = ArticleAttribute::where("article_id",$idArticle)->where("name","city")->first()->value;
        $this->provinceToUpdate = ArticleAttribute::where("article_id",$idArticle)->where("name","province")->first()->value;
        $this->cities = City::select("name")->where("country_id",$this->countryIdByArticle)->get();
        $this->provinces = Province::select("name")->where("country_id",$this->countryIdByArticle)->get();
        $this->dispatchBrowserEvent('modalEditArticle');
    }

    public function modalWriteReview($idArticle)
    {
        $this->getReviewsByProfile = Review::getReviewsByProfile($this->idSite,$idArticle);
        $this->messageFileImport["success_review"]["show"] = false;
        $this->messageFileImport["error_review"]["show"] = false;
        $this->idArticle = $idArticle;
        $this->dispatchBrowserEvent('modalWriteReview');
    }

    public function insertReview()
    {
        $review = new Review();
        $review->site_id = $this->idSite;
        $review->article_id = $this->idArticle;
        $review->stars = $this->stars;
        $review->comment = $this->text_review;
        $review->writted_by = $this->writted_by;
        try {
            $review->save();
            $this->messageFileImport["success_review"]["show"] = true;
            $this->getReviewsByProfile = Review::getReviewsByProfile($this->idSite,$this->idArticle);
            $this->cleanInputsByModal();
        } catch (\Throwable $th) {
            $this->messageFileImport["error_review"]["show"] = true;
        }
    }

    public function deleteReview($idReview)
    {
        try {
            Review::destroy($idReview);
            $this->getReviewsByProfile = Review::getReviewsByProfile($this->idSite,$this->idArticle);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function updateArticle(){
        $this->validate([
            "aboutMeToUpdate" => "required",
            "cityToUpdate" => "required",
            "provinceToUpdate" => "required"
        ]);
        $articleToUpdate = Article::find($this->idArticle);
        $articleToUpdate->description = "<p>".$this->aboutMeToUpdate."</p>";
        $articleToUpdate->save();
        $articleAttributesAboutMeToUpdate = ArticleAttribute::where("article_id",$this->idArticle)->where("name","about_me")->first();
        $articleAttributesAboutMeToUpdate->value = $this->aboutMeToUpdate;
        $articleAttributesAboutMeToUpdate->save();
        $articleAttributesCityToUpdate = ArticleAttribute::where("article_id",$this->idArticle)->where("name","city")->first();
        $articleAttributesCityToUpdate->value = $this->cityToUpdate;
        $articleAttributesCityToUpdate->save();
        $articleAttributesProvinceToUpdate = ArticleAttribute::where("article_id",$this->idArticle)->where("name","province")->first();
        $articleAttributesProvinceToUpdate->value = $this->provinceToUpdate;
        $articleAttributesProvinceToUpdate->save();
        $this->messageFileImport["success_update"]["show"] = true;
    }
    //////

    //DELETE
    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function confirmDeleteOldProfiles() {
        $this->dispatchBrowserEvent('confirmDeleteOldProfiles');
    }

    public function delete() {
        Article::destroy($this->confirm);
        $this->confirm   = '';
    }

    public function deleteOldProfiles(){
        $old_profiles = Article::withTrashed()->whereNotNull("deleted_at")->get();
        foreach ($old_profiles as $profile){
            ArticleAttribute::where("article_id",$profile->id)->forceDelete();
        }
        Article::withTrashed()->whereNotNull("deleted_at")->forceDelete();
    }
    /////

    public function mount() {
        $this->title = trans('Profiles');
        $this->allSites = Site::select("id","name")->orderBy("name","asc")->get();
        ksort($this->profileAttributes);
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $allSitesRender = Article::withFilterBySite("id","asc",$this->pagination,$this->idSite,$this->search);
        $allCountries = array(
            ["id"=>1,"name"=>"Holland"],
            ["id"=>2,"name"=>"Belgium"],
            ["id"=>79,"name"=>"England"]
        );
        if (isset($this->countryIdByArticle)){
            $this->cities = City::select("name")->where("country_id",$this->countryIdByArticle)->get();
            $this->provinces = Province::select("name")->where("country_id",$this->countryIdByArticle)->get();
        }
        return view('livewire.admin.profiles',compact('allSitesRender','allCountries'))->layout('layouts.panel');
    }
}