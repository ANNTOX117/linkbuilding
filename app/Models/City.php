<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory;

    public static function getNearCities($idCity,$distance = 90,$random = 18,$country=false)
    {
        $cityInfo = City::find($idCity);
        $latitude = $cityInfo->lat;
        $longitude = $cityInfo->lon;
        $query = City::select(DB::RAW('*, ROUND(DEGREES(ACOS(SIN(RADIANS('.$latitude.')) * SIN(RADIANS(lat)) + COS(RADIANS('.$latitude.')) * COS(RADIANS(lat)) * COS(RADIANS('.$longitude.' - lon)))) * 69.09) as distance'))
        ->where("id","!=",$idCity);
        if($country){
            $query->where("country_id","=",$country);
        }
        return $query->havingRaw('distance < "'.$distance.'"')
        ->orderBy('distance', 'asc')
        ->groupBy('id')
        ->limit($random)
        ->get();
    }

    public static function getRandomCities($limit,$except_this_city = null)
    {
        $query = self::select("id","name","path")->where("country_id",1);
        if(isset($except_this_city)) $query->where("id","!=",$except_this_city);
        return $query->inRandomOrder()->limit($limit)->get();
    }

    public static function getBiggestCityByProvince($provinceId)
    {
        return Province::join("cities","provinces.id","=","cities.province_id")
        ->where("provinces.id",$provinceId)
        ->orderBy("biggest","DESC")
        ->first();
    }

    public static function getAllCitiesUrl()
    {
        return self::selectRaw("path as url,now() as created_at")->get();
    }
}
