<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\ArticleAttribute;
use App\Models\Province;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InsertProfilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $allDataByFile;
    public $idSite;
    public $importImage;
    public $category;
    public $pathImage;
    public $cities;
    public $profileAttributes;
    public $idCountry;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allDataByFile,$idSite,$importImage,$category,$pathImage,$cities,$profileAttributes,$idCountry)
    {
        $this->allDataByFile = $allDataByFile;
        $this->idSite = $idSite;
        $this->importImage = $importImage;
        $this->category = $category;
        $this->pathImage = $pathImage;
        $this->cities = $cities;
        $this->profileAttributes = $profileAttributes;
        $this->idCountry = $idCountry;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $counterCity = 0;
        foreach ($this->allDataByFile as $file) {
            $url  = $file["profile_image"];
            $name = substr($url, strrpos($url, '/') + 1);
            $path = "/storage/profile_images/$this->idSite/$name";
            $idArticle = $this->importImage?Article::insertArticleByProfile($this->idSite, $this->category, $file["name"], $path, $file["about_me"]):Article::insertArticleByProfile($this->idSite, $this->category, $file["name"],$this->pathImage.$name, $file["about_me"]);
            $cityToBeStoredName = "";
            $cityToBeStoredId = "";
            $provinceName = "";
            if (!empty($this->cities[$counterCity])) {
                $cityToBeStoredName = $this->cities[$counterCity]->name;
                $cityToBeStoredId = $this->cities[$counterCity]->id;
                $provinceToBeStored = Province::where('id', $this->cities[$counterCity]->province_id)->first();
                if (!empty($provinceToBeStored)) {
                    $provinceName = $provinceToBeStored->name;
                }
            } else {
                $counterCity = '-1';
            }
            $counterCity++;
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
            // echo "article $idArticle inserted".PHP_EOL;
        }
    }
}
