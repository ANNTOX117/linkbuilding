<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Return_;

class Article extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'articles';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'url',
        'title',
        'description',
        'meta_title',
        'meta_description',
        'keywords',
        'language',
        'category',
        'image',
        'active',
        'draft',
        'authority_site',
        'visible_at',
        'expired_at',
        'approved_at',
        'published_at',
        'permanent',
        'client',
    ];
    /***
     * @author Antonio
     * overwrite the method of Model object
     * return the value "url" stored in the database instead "id"
     */
    public function getRouteKeyName()
    {
        return "url";
    }

    public function authority_sites() {
        return $this->hasOne('App\Models\AuthoritySite', 'id', 'authority_site');
    }

    public function wordpressm() {
        return $this->hasOne('App\Models\Wordpress', 'id', 'wordpress');
    }

    public function clients() {
        return $this->hasOne('App\Models\User', 'id', 'client');
    }

    public function categories() {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }

    public function orders(){
        return $this->hasOne('App\Models\Order', 'id', 'order');
    }

    public function scopeActive($query) {
        return $query->where('active', 1)->orWhereNull('active');
    }

    public function scopePending($query) {
        return $query->where('active', 2);
    }

    public function scopeFilterApproval($query, $val){
        return $query->join('users', 'users.id', '=', 'articles.client')
            ->where('articles.url', 'like', '%'.$val.'%')
            ->orWhere('articles.title', 'like', '%'.$val.'%')
            ->orWhere('articles.description', 'like', '%'.$val.'%')
            ->orWhere('authority_sites.url', 'like', '%'.$val.'%')
            ->orWhere('published_at', 'like', '%'.$val.'%')
            ->orWhere('articles.visible_at', 'like', '%'.$val.'%')
            ->orWhere(\DB::raw('DATEDIFF(articles.expired_at, now())'), 'like', '%'.$val.'%')
            ->orWhere('users.name', 'like', '%'.$val.'%')
            ->orWhere('users.lastname', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'created_at', $order = 'desc') {
        return Article::active()->orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'created_at', $order = 'desc') {
        return Article::whereIn('active', array(1, 2))->orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'created_at', $order = 'desc', $pagination, $search = null) {
        return Article::select('articles.*', 'authority_sites.url')
                ->join('authority_sites', 'authority_sites.id', 'articles.authority_site')
                ->whereIn('active', array(1, 2))
                ->filterArticle($search)
                ->orderBy($sort, $order)
                ->paginate($pagination);
                //->get();
    }

    public static function withFilterBySite($sort = 'created_at', $order = 'desc', $pagination,$idSite, $search = null) {
        //return self::select('articles.*','article_attributes.value')
        return self::select('articles.*','article_attributes.value',DB::raw("aa.value as profileId"))
            ->join('authority_sites', 'authority_sites.id', 'articles.authority_site')
            ->join('article_attributes', 'article_attributes.article_id', 'articles.id')
            ->join(DB::raw('article_attributes aa'), 'aa.article_id', 'articles.id')
            ->where("authority_sites.site",$idSite)
            ->where("article_attributes.name","city")
            ->where("aa.name","profile_id")
            ->filterArticle($search)
            ->orderBy($sort, $order)
            ->groupBy('articles.id')
            ->paginate($pagination);
    }

    public function scopeFilterArticle($query, $val){
        return $query
        ->where('articles.title', 'like', '%'.$val.'%')
        ->orWhere('authority_sites.url', 'like', '%'.$val.'%')
        ->orWhere('articles.created_at', 'like', '%'.$val.'%');
    }

    public static function all_pendings($sort = 'created_at', $order = 'desc') {
        return Article::pending()->orderBy($sort, $order)->get();
    }

    public static function count_all_pendings() {
        return Article::pending()->get()->count();
    }

    public static function approve($article) {
        return Article::where('id', $article)->update(['active' => 1]);
    }

    public static function for_approval($article) {
        return Article::where('id', $article)->update(['active' => null , 'approved_at' => Carbon::today()]);
    }

    public function articleimages()
    {
        return $this->hasOne(ArticleImage::class, 'article');
    }

    public function  scopeMyarticle($query) {

        $query->select('articles.id', 'categories.name', 'articles.title', 'authority_sites.url','articles.keywords','articles.published_at','articles.visible_at', 'articles.expired_at', 'articles.external_url', \DB::raw('DATEDIFF(articles.expired_at, articles.visible_at) as days'))
        ->join('authority_sites' , 'authority_sites.id','=','articles.authority_site')
        ->join('wordpress' , 'wordpress.id','=','authority_sites.wordpress')
        ->join('sites_categories_main', 'sites_categories_main.wordpress', 'wordpress.id')
        ->join('categories', 'categories.id', 'sites_categories_main.category')
        ->where('articles.active', 1)
        ->where('articles.client',  auth()->user()->id)
        ->whereNotNull('published_at')
        ->whereDate('articles.expired_at', '>' , Carbon::today())
        ->whereDate('articles.published_at', '<=' , Carbon::today())
        ->groupBy('articles.id');
    }

    public function scopeFilter($query, $val)
    {
        if (!empty($val)) {
            return $query
            ->where('keywords', 'LIKE', '%'.$val.'%')
            ->orWhere('authority_sites.url', 'LIKE', '%'.$val.'%')
            ->orWhere('name', 'LIKE', '%'.$val.'%')
            ->orWhere('articles.published_at', 'LIKE', '%'.$val.'%')
            ->orWhere('articles.visible_at', 'LIKE', '%'.$val.'%')
            ->orWhere(\DB::raw('DATEDIFF(articles.expired_at, now())'), 'LIKE', '%'.$val.'%');
        }
    }

    public static function to_publish() {
        return Article::select('articles.*', DB::raw('IF(authority_sites.wordpress IS NOT NULL, "wp", "site") AS site'))
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->whereNull('articles.active')
            ->whereNotNull('articles.approved_at')
            ->whereNull('articles.published_at')
            ->where('articles.visible_at', '<=', Carbon::today())
            ->having('site', 'wp')
            ->get();
    }

    public static function to_remove($date) {
        return Article::select('articles.*', DB::raw('IF(authority_sites.wordpress IS NOT NULL, "wp", "site") AS site'))
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->where('articles.active', 1)
            ->whereNotNull('articles.approved_at')
            ->whereNotNull('articles.published_at')
            ->where('articles.expired_at', '<=', $date)
            ->having('site', 'wp')
            ->get();
    }

    public static function no_published($id){
        return Article::where('id', $id)->update(['active' => 1, 'published_at' => null]);
    }

    public static function published($id, $external) {
        return Article::where('id', $id)->update(['active' => 1, 'published_at' => Carbon::now(), 'external_url' => $external]);
    }

    public static function doesnt_exist($authority, $url) {
        return Article::select('articles.*')
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->where('authority_sites.site', $authority)
            ->where('articles.url', $url)
            ->where('articles.active', 1)
            ->whereRaw('"'. date('Y-m-d') .'" between `articles`.`visible_at` and `articles`.`expired_at`')
            ->whereNotNull('articles.approved_at')
            ->whereNotNull('articles.published_at')
            ->doesntExist();
    }

    public static function get_articles($ids) {
        return Article::whereIn('id', $ids)->get();
    }

    public static function getCategories($id, $category = null){
        $query = Article::select('categories.id', 'categories.name')
        ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
        ->join('categories', 'categories.id', '=', 'articles.category')
        ->where('authority_sites.site', $id)
        ->where('articles.active', 1)
        ->whereRaw('"'. date('Y-m-d') .'" between `articles`.`visible_at` and `articles`.`expired_at`')
        ->whereNotNull('articles.approved_at')
        ->whereNotNull('articles.published_at');
        if(!empty($category)){
            $query = $query->where('articles.category', $category);
        }
        return $query->groupBy('categories.id')->get();
    }

    public static function get_post($authority, $url) {
        return Article::select('articles.*')
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->where('authority_sites.site', $authority)
            ->where('articles.url', $url)
            ->where('articles.active', 1)
            ->whereRaw('"'. date('Y-m-d') .'" between `articles`.`visible_at` and `articles`.`expired_at`')
            ->whereNotNull('articles.approved_at')
            ->whereNotNull('articles.published_at')
            ->first();
    }

    public static function get_posts($authority, $category = null, $sort = 'created_at', $order = 'desc') {
        $query = Article::select(
                'articles.*',
                'articles.authority_site as auth_site',
                'categories.url as category_name',
                'categories.id as category_id',
                DB::raw('(SELECT sites_categories_main.headerText from sites_categories_main where sites_categories_main.category = category_id and sites_categories_main.site = '.$authority.' limit 1) as headerText'),
                DB::raw('(SELECT sites_categories_main.footerText from sites_categories_main where sites_categories_main.category = category_id and sites_categories_main.site = '.$authority.' limit 1) as footerText')
            )
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->join('categories', 'categories.id', 'articles.category');
            if(!empty($category)){
                $query->where('articles.category', $category);
            }
            return $query->where('authority_sites.site', $authority)
            ->where('articles.active', 1)
            ->whereRaw('"'. date('Y-m-d') .'" between `articles`.`visible_at` and `articles`.`expired_at`')
            ->whereNotNull('articles.approved_at')
            ->whereNotNull('articles.published_at')
            ->orderBy($sort, $order)
            ->paginate(10);
    }

    public static function get_posts_by_subdomain($authority, $category, $sort = 'created_at', $order = 'desc') {
        return Article::select('articles.*', 'categories.url as category_name')
            ->join('authority_sites', 'authority_sites.id', '=', 'articles.authority_site')
            ->join('categories', 'categories.id', '=', 'articles.category')
            ->where('authority_sites.site', $authority)
            ->where('categories.url', $category)
            ->where('articles.active', 1)
            ->whereRaw('"'. date('Y-m-d') .'" between `articles`.`visible_at` and `articles`.`expired_at`')
            ->whereNotNull('articles.approved_at')
            ->whereNotNull('articles.published_at')
            ->orderBy($sort, $order)
            ->paginate(10);
    }

    public function scopeUnpublished($query) {
        return $query->whereDate('expired_at', Carbon::today())->whereNotNull('published_at');
    }

    public static function waiting_for_approval($sort = 'created_at', $order = 'desc', $pagination, $search = null){

        return Article::select('articles.*', 'orders.details', 'authority_sites.url as site')
            ->join('orders', 'orders.id', 'articles.order')
            ->join('authority_sites', 'authority_sites.id', 'articles.authority_site')
            ->where('articles.active', 2)->whereNull('articles.approved_at')->whereNull('published_at')
            ->where('orders.status', 'paid')
            ->filterApproval($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function published_articles() {
        return Article::select('id', 'url')
            ->where('active', 1)
            ->where('expired_at', '>', Carbon::today())
            ->whereNotNull('approved_at')
            ->whereNotNull('published_at')
            ->get();
    }

    public static function set_error($id, $error) {
        return Article::where('id', $id)->update(['error' => $error]);
    }

    public static function insertArticleByProfile(int $idSite,int $idCategory,string $title,string $urlImage, string $description){
        $authority_site = new AuthoritySite();
        $lastIdInserted = isset(Article::all("id")->last()->id)?Article::all("id")->last()->id+1:1;
        $urlFreindly = Str::slug($title." ".$lastIdInserted);
        $url = htmlspecialchars($urlFreindly);
        $title = htmlspecialchars(ucfirst($title));
        $description = "<p>".htmlspecialchars($description)."</p>";
        $image = strtolower($urlImage);
        $visible_at = now();
        $expired_at = now()->addYears(10);
        $authority_site = $authority_site::where("site",$idSite)->first()->id;
        $category = $idCategory;
        DB::insert('INSERT INTO articles (url,title,description,image,visible_at,expired_at,authority_site,category) values (?,?,?,?,?,?,?,?)', [$url,$title,$description,$image,$visible_at,$expired_at,$authority_site,$category]);
        return DB::getPdo()->lastInsertId();
    }

    public static function getArticlesBySite(int $idSite,$total,$paginate = false,$random = true){
        $query = self::select("articles.id","articles.url","articles.title","articles.description","articles.image",
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(article_attributes.value), ',', 1) AS city"),
            DB::raw("SUBSTRING_INDEX(GROUP_CONCAT(article_attributes.value), ',', -1) AS province"))
            ->join("article_attributes","articles.id","article_attributes.article_id")
            ->join("authority_sites","articles.authority_site","authority_sites.id")
            ->join("sites","authority_sites.site","sites.id")
            //subquery to get all the articles that correspond for a woman
            ->whereIn("articles.id",function($query){
                $query->select('article_id')
                    ->from(with(new ArticleAttribute())->getTable())
                    ->where('name','gender')
                    ->where('value','Vrouw');})
            ->whereIn("article_attributes.name",['city','province'])
            ->where("sites.id",$idSite)
            ->groupBy("articles.id");
            $random?$query->inRandomOrder():$query->orderBy("articles.created_at");
            return $paginate?$query->paginate($total):$query->limit($total)->get();
    }

    public static function getArticlesByCityAndRadio(float $latitude,float $longitude,int $site_id,int $distance = 90,$limit=18,bool $paginate=false){
        $query = self::selectRaw('articles.id,articles.title,articles.url,articles.description,articles.image,cities.name,cities.path,categories.id as categoryId,categories.url as categoryUrl,
                ROUND(DEGREES(ACOS(SIN(RADIANS('.$latitude.')) * SIN(RADIANS(cities.lat)) + COS(RADIANS('.$latitude.')) * COS(RADIANS(cities.lat)) * COS(RADIANS('.$longitude.' - `cities`.`lon`)))) * 69.09) as distance')
                ->join('article_attributes', 'articles.id', 'article_attributes.article_id')
                ->join('cities',function($query)
                {
                    $query->on("cities.id","article_attributes.value")
                        ->where("article_attributes.name","city_id");
                })
                ->join("categories","articles.category","categories.id")
                ->join("authority_sites","articles.authority_site","authority_sites.id")
                ->join("sites","sites.id","authority_sites.site")
                ->where("sites.id",$site_id)
                ->groupBy("articles.id")
                ->havingRaw('distance < "'.$distance.'"')
                ->orderBy('distance');
        return $paginate?$query->paginate($limit):$query->limit($limit)->get();
    }

    public static function getArticlesByProvince($provinceId,$site_id,$limit=18,$paginate=false)
    {
        $query = self::selectRaw("articles.title,articles.url,articles.image,articles.description,cities.name,cities.path,categories.id as categoryId, categories.url as categoryUrl")
        ->join("article_attributes","articles.id","article_attributes.article_id")
        ->join("cities","article_attributes.value","cities.id")
        ->join("categories","articles.category","categories.id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","authority_sites.site","sites.id")
        ->where("article_attributes.name","city_id")
        ->where("cities.province_id",$provinceId)
        ->where("sites.id",$site_id);
        return $paginate?$query->paginate($limit):$query->limit($limit)->get();
    }

    public static function getArticlesByCityAndRadioPaginateLimit(float $latitude,float $longitude,int $distance = 90,$paginate=18,$limit=50)
    {
        $allIdByArticles = [];
        foreach (self::getArticlesByCityAndRadio($latitude,$longitude,$distance,$limit) as $article) {
            $allIdByArticles[] = $article->id;
        }
        return self::select("articles.url","articles.title","articles.description","articles.image","cities.name","cities.path")
        ->join('article_attributes', 'articles.id', 'article_attributes.article_id')
        ->join('cities',function($query)
        {
            $query->on("cities.id","article_attributes.value")
            ->where("article_attributes.name","city_id");
        })
        ->whereIn("articles.id",$allIdByArticles)
        ->groupBy("articles.id")
        ->paginate($paginate);
    }

    public static function getBlogsbyRandom($limit,$site_id,$paginate=false,$inRandomOrder=false)
    {
        $articlesWithAttributes = ArticleAttribute::distinct('article_id')->pluck('article_id')->all();
        $query= self::selectRaw("articles.url as blog_url,articles.image,articles.title,articles.description,categories.name,categories.url as category_url")
        ->join("categories","articles.category","categories.id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","sites.id","authority_sites.site")
        ->whereNotIn('articles.id', $articlesWithAttributes)
        ->where("sites.id",$site_id);
        if ($inRandomOrder) $query->inRandomOrder();
        return $paginate?$query->paginate($limit):$query->limit($limit)->get();
    }

    public static function getAllBlogsUrl()
    {
        $articlesWithAttributes = ArticleAttribute::distinct('article_id')->pluck('article_id')->all();
        return self::select("url","created_at")->whereNotIn('id', $articlesWithAttributes)->get();
    }

    public static function getArticlesByCity($idCity,$limit = null)
    {
        $query = self::select("articles.title","articles.image","cities.name")
        ->join('article_attributes', function($q)
        {
            $q->on('article_attributes.article_id', '=', 'articles.id')
                ->where('article_attributes.name', '=', "city_id");
        })
        ->join("cities","article_attributes.value","cities.id")
        ->where("cities.id",$idCity);
        return isset($limit)?$query->paginate($limit):$query->get();
    }

    public static function getAllProfileUrl()
    {
        return self::select("articles.url","articles.created_at")
        ->join("article_attributes","articles.id","=","article_attributes.article_id")
        ->groupBy("articles.url")
        ->get();
    }

    public static function getArticlesByCategory($categoryId,$site_id,$limit=18,$paginate=false)
    {
        $query = self::selectRaw("articles.title,articles.url,articles.description,articles.image,categories.id as categoryId,categories.url as categoryUrl,cities.name,cities.path")
        ->join("categories","articles.category","categories.id")
        ->join("article_attributes","article_attributes.article_id","articles.id")
        ->join("cities","article_attributes.value","cities.id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","authority_sites.site","sites.id")
        ->where("categories.id",$categoryId)
        ->where("sites.id",$site_id)
        ->groupBy("articles.id");
        return $paginate?$query->paginate($limit):$query->limit($limit)->get();
    }

    public static function getArticlesBySiteWithProvincie(int $idSite,$total,$paginate = false,$random = true)
    {
        $query = self::selectRaw("articles.id,articles.url,articles.title,articles.description,articles.image,cities.name as cityName,cities.path as cityPath,provinces.name as provincieName,provinces.path as provincieUrl,categories.id as categoryId,categories.url as categoryUrl")
        ->join("article_attributes","articles.id","article_attributes.article_id")
        ->join("authority_sites","articles.authority_site","authority_sites.id")
        ->join("sites","authority_sites.site","sites.id")
        ->join("cities","article_attributes.value","cities.id")
        ->join("provinces","cities.province_id","provinces.id")
        ->join("categories","articles.category","categories.id")
        ->whereIn("articles.id",function($query){
            $query->select('article_id')
                ->from(with(new ArticleAttribute())->getTable())
                ->where('name','gender')
                ->where('value','Vrouw');
        })
        ->where("sites.id",$idSite)
        ->where("article_attributes.name","city_id")
        ->groupBy("articles.id");
        if ($random) $query->inRandomOrder();
        return $paginate?$query->paginate($total):$query->limit($total)->get(); 
    }

    public static function getBlogByUrl($url,$site_id)
    {
        return self::selectRaw("articles.image,articles.title,articles.description,articles.created_at,categories.name,categories.url")
        ->join("categories","categories.id","articles.category")
        ->join("authority_sites","authority_sites.id","articles.authority_site")
        ->join("sites","sites.id","authority_sites.site")
        ->where("articles.url", $url)
        ->where("sites.id", $site_id)
        ->first();
    }

    public static function getMetaDataBlogByUrl($url)
    {
        return self::selectRaw("articles.meta_title,articles.meta_description,articles.keywords")
        ->join("categories","categories.id","articles.category")
        ->where("articles.url", $url)
        ->first();
    }

    public static function checkIfUrlArticleExist($url)
    {
        return self::where("url",$url)->exists();
    }
}