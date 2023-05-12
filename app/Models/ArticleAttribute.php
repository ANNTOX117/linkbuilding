<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class ArticleAttribute extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function setUpdatedAtAttribute($value)
    {
        // to Disable updated_at
    }

    public function setCreatedAtAttribute($value)
    {
        // to Disable updated_at
    }

    public static function insertArticleAttributes(int $idSite,string $name_attribute,string $value_attribute, int $article_id){
        //$articleAttributes = new ArticleAttribute();
        DB::insert("INSERT INTO article_attributes (site_id,name,value,article_id) VALUES (?,?,?,?)",[$idSite,$name_attribute,$value_attribute,$article_id]);
        return DB::getPdo()->lastInsertId();
        //return $articleAttributes->id;
    }

    public static function getAmountProfilesByProvience()
    {
        return self::select("value")
        ->where("name","province")
        ->groupBy("value")
        ->orderBy("value")
        ->get();
    }

    public static function getProfilesBySearch(int $site_id,int $paginate = 30,string $name_or_sex,$province = null)
    {
        $query = self::selectRaw("articles.title,articles.url,articles.description,articles.image,cities.name as cityName,cities.path,categories.id as categoryId,categories.url as categoryUrl");
        if (isset($province)) {
            $sex = $name_or_sex;
            $query->join('articles', function ($join) {
                $join->on('article_attributes.article_id', '=', 'articles.id')
                ->where("article_attributes.name","province");
            })
            ->join(DB::raw('article_attributes aa2'), function ($join) {
                $join->on('aa2.article_id', '=', 'articles.id')
                ->where("aa2.name","gender");
            })
            ->where("article_attributes.value",$province)
            ->where("aa2.value",$sex);
            
        }else{
            $name = $name_or_sex;
            $query->join('articles', function ($join) {
                $join->on('article_attributes.article_id', '=', 'articles.id')
                ->where("article_attributes.name","name");
            })
            ->where("article_attributes.value",'like', '%'.$name.'%');
        }
        return $query->join(DB::raw("article_attributes aa"),function ($join){
            $join->on('aa.article_id', '=', 'articles.id')
                ->where("aa.name","city_id");
        })
        ->join("cities","aa.value","cities.id")
        ->join("categories","articles.category","categories.id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","authority_sites.site","sites.id")
        ->where("sites.id",$site_id)
        ->groupBy("articles.id")->paginate($paginate);
    }
}
