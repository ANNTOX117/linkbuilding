<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $timestamps = false;
    public static function getReviewsByProfile($idSite,$idArticle)
    {
        return self::select("id","stars","comment","writted_by")
        ->where("site_id",$idSite)
        ->where("article_id",$idArticle)
        ->get();
    }
}
