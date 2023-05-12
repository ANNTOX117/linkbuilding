<?php

namespace App\Jobs;
use App\Models\Category;
use App\Models\City;
use App\Models\SeoPages;
use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Spintax;

class CreateSEOPagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $country;
    private $category;
    private $site;
    private $msgMetadata;
    private $active;
    private $description_top;
    private $description_buttom;
    private $meta_title;
    private $meta_description;
    private $title_seo;
    private $text_infront_left;
    private $text_infront_right;
    private $text_city_nearby;

    public function __construct($country,$category,$site,$msgMetadata,$active,$description_top,$description_buttom,$meta_title,$meta_description,$title_seo,$text_infront_left,$text_infront_right,$text_city_nearby)
    {
        $this->category = $category;
        $this->country = $country;
        $this->site = $site;
        $this->msgMetadata = $msgMetadata;
        $this->active = $active;
        $this->description_top = $description_top;
        $this->description_buttom = $description_buttom;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->title_seo = $title_seo;
        $this->text_infront_left = $text_infront_left;
        $this->text_infront_right = $text_infront_right;
        $this->text_city_nearby = $text_city_nearby;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $counter = 0;
        foreach (City::where("country_id",$this->country)->pluck("id") as $idCity) {
            $city_province = City::select(DB::raw("cities.name as cityName"),DB::raw("provinces.name as provinceName"))->join("provinces","cities.province_id","=","provinces.id")->find($idCity);
            $this->generateMetadata($city_province->cityName,$city_province->provinceName);
            if($this->insertSeoPageToDB($this->category,$this->site,$idCity,$this->msgMetadata["meta_title"],$this->msgMetadata["meta_description"],$this->msgMetadata["title"],$this->msgMetadata["description_top"],$this->msgMetadata["description_buttom"],$this->text_infront_left,$this->text_infront_right,$this->active))$counter++;
        }
        return $counter;
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
    public function generateMetadata(/*bool $show = true,*/$stad = null,$province = null):void
    {
        try {
            $category = Category::findOrFail($this->category);
        } catch (\Throwable $th) {
            echo "I failed";
        }
        $template = Template::getTemplateName($this->site);
        $stand = 'Amsterdam';
        $this->msgMetadata['meta_title'] = $this->filterTekst($this->meta_title,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['meta_description'] = $this->filterTekst($this->meta_description,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['title'] = $this->filterTekst($this->title_seo,$stand,$category->id,$category->url,$template->slug);
        $this->msgMetadata['description_top'] = $this->filterTekst($this->description_top,$stand,$this->text_city_nearby,$category->id,$category->url,$template->slug);
        $this->msgMetadata['description_buttom'] = $this->filterTekst($this->description_buttom,$stand,$this->text_city_nearby,$category->id,$category->url,$template->slug);
    }

    /***
     * transform and prepare the text to insert
     * @params 
     *      $text = text to transform
     *      $stad = city to complement the text
     *      $province = province to complement the text
     */
    // private function filterTekst($text, $stad = "", $seosteden="", $link=""){
    //     if (!empty($stad)):
    //         $text = str_replace('{city}', $stad, $text);
    //     endif;
    //     if(stristr($text, 'provincie-') == TRUE || stristr($text,'stedenlink-') == TRUE) {
    //     $textAll = $this->my_explode($text);
    //     foreach($textAll as $item):
    //         preg_match('#\{(.*?)\}#', $item, $match);
    //         if (array($match) && !empty($match[0])):
    //             $replacement["cities"] = $match[0];
    //         endif;
    //         preg_match('#\[(.*?)\]#', $item, $match);
    //         if (array($match) && !empty($match[0])):
    //             $replacement["spintax"] = $match[0];
    //         endif;
    //     endforeach;
    //     }else{
    //         $aText = explode(' ',$text);
    //         $replacement = array();
    //         if (!empty($aText)):
    //             foreach($aText as $item){
    //                 if(stristr( $item, '{') == true) {
    //                     $replacement[] = $item;
    //                 }
    //             }
    //         endif;
    //     }
    //     if (!empty($replacement["cities"])) {
    //         if (!empty($replacement["cities"])):
    //             // foreach($replacement as $item):
    //             //     dd($item);
    //                 $text = SeoPages::shortCodes($replacement["cities"],$seosteden, $replacement["spintax"]);
    //                 // dd($txt,"before and forget");
    //                 // $text = str_replace($replacement["cities"], SeoPages::shortCodes($replacement["cities"], $seosteden, $replacement["spintax"]), $text);
    //                 // dd($text,"hello");
    //             //endforeach;
    //         endif;
    //     } else {
    //         if (!empty($replacement)):
    //             foreach($replacement as $item):
    //                 $text = str_replace($item, SeoPages::shortCodes($item, $seosteden, $link), $text);
    //             endforeach;
    //         endif;
    //     }
    
    //     return $text;
    // }

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
        
        $seopages = new SeoPages();
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
    // private function my_explode($str)
    // {
    
    //     $ret = array(); 
    //     $in_parenths = 0; 
    //     $pos = 0;
    //     for($i=0;$i<strlen($str);$i++)
    //     {
    //         $c = $str[$i];
    //         if($c == ' ' && !$in_parenths) {
    //             $ret[] = substr($str, $pos, $i-$pos);
    //             $pos = $i+1;
    //         }
    //         elseif($c == '{') $in_parenths++;
    //         elseif($c == '}') $in_parenths--;
    //     }
    //     if($pos > 0) $ret[] = substr($str, $pos);
    //     return $ret;
    // }
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
     * insert the data in the form
     * @params -> all data to be inserted in the database
     * @return bool
     */
    // private function insertSeoPageToDB($category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active):bool
    // {
    //     try {
        
    //         return DB::insert("INSERT INTO seo_pages ". 
    //             "(category_id,site_id,city_id,meta_title,meta_description,title,description_top,description_buttom,text_infront_left,text_infront_right,active) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
    //             [$category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active]);
    //     } catch (\Throwable $th) {
    //         dump($th);
    //         return false;
    //     }
    
    // }

    private function insertSeoPageToDB($category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active):bool
    {
        try {
            return DB::insert("INSERT INTO seo_pages ". 
                "(category_id,site_id,city_id,meta_title,meta_description,title,description_top,description_buttom,text_infront_left,text_infront_right,active) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
                [$category_id,$site_id,$city_id,$meta_title,$meta_description,$title,$description_top,$description_buttom,$text_infront_left,$text_infront_right,$active]);
        } catch (\Throwable $th) {
            return false;
        }
    }
}
