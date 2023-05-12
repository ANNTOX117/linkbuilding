<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Province extends Model
{
    use HasFactory;

    public static function getAllProvincesUrl()
    {
        return self::select( DB::raw("path as url") )->get();
    }

    public static function getProvincieBycity(string $city)
    {
        $provincie = self::select("provinces.name")
        ->join("cities","cities.province_id","=","provinces.id")
        ->where("cities.name",$city)
        ->orWhere("cities.path",$city)
        ->first();
        return isset($provincie)? ucfirst($provincie->name)."-".ucfirst($city):false;
    }
}
