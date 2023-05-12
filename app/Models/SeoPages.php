<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Spintax;

class SeoPages extends Model
{
    use HasFactory;

    public static function shortCodes($shortCode = '',/*, $text = '', $link = ''*/$spintaxText="",$categoryId="",$categoryName="",$template="",$country=1){
        $shortCode = str_replace('{','',$shortCode);
        $shortCode = str_replace('}','',$shortCode);
        $aShortCode = explode('-',$shortCode);
        if(stristr($shortCode, 'provincie') === FALSE || stristr($shortCode, 'stedenlink') === FALSE || stristr($shortCode, 'stedenzonderlink') === FALSE ) {
            $shortCode = str_replace('{','',$shortCode);
            $shortCode = str_replace('}','',$shortCode);
            $aShortCode = explode('-',$shortCode);
            $aShortCode[0] = trim($aShortCode[0]);
        }
        else{  
            if (stristr($shortCode, 'provincie') === TRUE){
                $shortCode = str_replace('{','',$shortCode);
                $shortCode = str_replace('}','',$shortCode);            
                $aShortCode = explode('provincie-',$shortCode);
                $aShortCode[0] = 'provincie';
            }
            elseif(stristr($shortCode, 'stedenlink') === TRUE){
                $shortCode = str_replace('{','',$shortCode);
                $shortCode = str_replace('}','',$shortCode);            
                $aShortCode = explode('stedenlink-',$shortCode);
                $aShortCode[0] = 'stedenlink';

            }
            }
        if ($aShortCode[0] == 'provincie'){
            if (isset($aShortCode[2])){
                $aShortCode[1]=$aShortCode[1].'-'.$aShortCode[2];
            }
            if (isset($aShortCode[3])){
                $aShortCode[1]=$aShortCode[1].'-'.$aShortCode[3];
            }
            return SeoPages::provincie($aShortCode[1]);
        }
        if ($aShortCode[0] == 'stedenlink'){
            $seo = new Seopages();
            if (isset($aShortCode[2])){
                $aShortCode[1]=$aShortCode[1].'-'.$aShortCode[2];
            }
            if (isset($aShortCode[3])){
                $aShortCode[1]=$aShortCode[1].'-'.$aShortCode[3];
            }
            return $seo->makeTextByCitiesAndData($aShortCode[1],$spintaxText,$categoryId,$categoryName,$template,$country);
        }
        return false;
    }

    private function makeTextByCitiesAndData($nameCity,$spintaxText,$categoryId,$categoryName,$template,$country){
        $cityName = City::where("name",$nameCity)->orWhere("path",$nameCity)->first();
        $distance = 10;
        if (!empty($distance)){
            $distance = $distance * 0.6211371;
            $query = City::getNearCities($cityName->id,$distance,5,$country);
        }
        $returnText = '';
        if (!empty($query)):
            foreach($query as $item){
                $link =  "/".$template."/".slugify($categoryName.'-'.$categoryId).'/'.slugify($item->name);
                $spintax = new Spintax();
                $text = $spintax->process($spintaxText);
                $returnText .= '<a href="'. $link .'" title="'.$item->path.'">'." $text ".$item->name.'</a>, ';
            }
        endif;
        $returnText = trim($returnText);
        $returnText = substr($returnText,0,-1);
        return $returnText;
    }

    public static function provincie($city){
        return Province::getProvincieBycity($city);
    }
}
