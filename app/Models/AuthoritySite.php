<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AuthoritySite extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'authority_sites';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'site',
        'wordpress',
        'url',
        'subnet',
        'pa',
        'da',
        'tf',
        'cf',
        'dre',
        'refering_domains',
        'type',
        'preview',
        'price',
        'price_special'
    ];

    public function sites() {
        return $this->hasOne('App\Models\Site', 'id', 'site');
    }

    public function wordpresses() {
        return $this->hasOne('App\Models\Wordpress', 'id', 'wordpress');
    }

    public function customers() {
        return $this->hasOne('App\Models\User', 'id', 'client');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','authority_user','authority','user');
    }

    public static function all_items($sort = 'url', $order = 'asc') {
        return AuthoritySite::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'url', $order = 'asc') {
        return AuthoritySite::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }
    public static function with_filter($sort = 'url', $order = 'asc', $pagination, $search = null) {
        return AuthoritySite::whereNotNull('url')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilterSearch($query, $val){
            return $query->where('url', 'LIKE', '%'.$val.'%')
                            ->orWhere('pa', 'LIKE', '%'.$val.'%')
                            ->orWhere('da', 'LIKE', '%'.$val.'%')
                            ->orWhere('tf', 'LIKE', '%'.$val.'%')
                            ->orWhere('cf', 'LIKE', '%'.$val.'%')
                            ->orWhere('backlinks', 'LIKE', '%'.$val.'%')
                            ->orWhere('price', 'LIKE', '%'.$val.'%')
                            ->orWhere('price_special', 'LIKE', '%'.$val.'%');
    }

    public static function items_for_packages($sort = 'url', $order = 'asc') {
        return AuthoritySite::whereIn('type', array('startingpage', 'wordpress'))->orderBy($sort, $order)->get();
    }

    public static function select_for_packages() {
        return AuthoritySite::select('id', 'url as text')
                        ->whereIn('type', array('startingpage', 'wordpress'))
                        ->orderBy('url', 'asc')
                        ->get();
    }

    public function scopeselectbytype($query, $type){

        if ($type =='onlyhomepage') {
            return $query->whereIn('authority_sites.type', ['startingpage'])
            ->where('price', '>', 0);
        }
        if ($type =='blog-content') {
            return $query->whereIn('authority_sites.type', ['startingpage', 'wordpress'])->where('price', '>', 0)->whereNotNull('preview');
        }
        return $query->whereIn('authority_sites.type', ['startingpage', 'childstartingpage'])->where('price', '>', 0);
    }

    public static function selectType($type, $language = null ,$search = ''){
        $first = AuthoritySite::select(
            'authority_sites.*', 
            'authority_sites.site as this_site',
            DB::raw('(SELECT sites.permanent FROM sites WHERE sites.id = this_site LIMIT 1) as permanent'),
            DB::raw('(SELECT sites.language FROM sites WHERE sites.id = this_site LIMIT 1) as language')
        );
            if(!is_null($language)){
                $first->whereRaw('(SELECT sites.language FROM sites WHERE sites.id = authority_sites.site LIMIT 1) = '.$language);
            }
            $first->doesntHave("users")
            ->selectbytype($type)
            ->filter($search);

        $second = AuthoritySite::select(
            'authority_sites.*', 
            'authority_sites.site as this_site', 
            DB::raw('(SELECT sites.permanent FROM sites WHERE sites.id = this_site LIMIT 1) as permanent'),
            DB::raw('(SELECT sites.language FROM sites WHERE sites.id = this_site LIMIT 1) as language')
        );
            if(!is_null($language)){
                $second->whereRaw('(SELECT sites.language FROM sites WHERE sites.id = authority_sites.site LIMIT 1) = '.$language);  
            }
                $second->join('authority_user', 'authority_user.authority', 'authority_sites.id')
                ->selectbytype($type)
                ->where('authority_user.user', auth()->user()->id)
                ->union($first)
                ->filter($search);
        return $second;
    }

    public static function selectTypeWordpress($type, $language = null){
        $first = AuthoritySite::select(
                    'wordpress.id',
                    'authority_sites.id as authority',
                    'authority_sites.site as this_site',
                    'wordpress.url',
                    'authority_sites.pa',
                    'authority_sites.da',
                    'authority_sites.tf',
                    'authority_sites.cf',
                    'wordpress.ip',
                    'authority_sites.price',
                    'authority_sites.price_special',
                    'authority_sites.subnet',
                    'wordpress.permanent'
        );
            if(!is_null($language)){
                $first->where('wordpress.language' ,$language);  
            }
            $first->doesntHave("users")
                ->authorityavgtype($type)
                ->where('authority_sites.price', '>', 0)
                ->whereNotNull('authority_sites.price');

        $second = AuthoritySite::
                select(
                    'wordpress.id',
                    'authority_sites.id as authority',
                    'authority_sites.site as this_site',
                    'wordpress.url',
                    'authority_sites.pa',
                    'authority_sites.da',
                    'authority_sites.tf',
                    'authority_sites.cf',
                    'wordpress.ip',
                    'authority_sites.price',
                    'authority_sites.price_special',
                    'authority_sites.subnet',
                    'wordpress.permanent'
                );
            if(!is_null($language)){
                $second->where('wordpress.language' ,$language);  
            }
                $second->join('authority_user', 'authority_user.authority', 'authority_sites.id')
                ->authorityavgtype($type)
                ->where('authority_user.user', auth()->user()->id)
                ->where('authority_sites.price', '>', 0)
                ->whereNotNull('authority_sites.price')
                ->union($first);

        return $second;
    }

    public static function all_index_by_letter($site) {
        return AuthoritySite::select(DB::raw('substr(categories.name, 1, 1) as letter'))
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('authority_sites.site', $site)
            ->groupBy(DB::raw('substr(categories.name, 1, 1)'))
            ->orderBy('letter', 'asc')
            ->get();
    }

    public static function all_daughters_by_letter($site, $letter) {
        return AuthoritySite::select('categories.*')
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('authority_sites.site', $site)
            ->where('categories.name', 'like', $letter . '%')
            ->groupBy('categories.id')
            ->get();
    }

    public static function all_daughters_for_category($site, $category) {
        return AuthoritySite::select('categories.*')
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            // ->where('categories.url', $category)
            ->groupBy('categories.id')
            ->get();
    }

    public static function all_daughters_list_for_category($site, $category) {
        return AuthoritySite::select('links.*')
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->leftjoin('links', 'links.category', '=', 'categories.id')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            // ->where('categories.url', $category)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy('links.category')
            ->get();
    }

    public static function all_daughters_list_for_letter($site, $letter, $category) {
        return AuthoritySite::select('links.*')
            // ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            // ->join('categories', 'categories.id', '=',  'sites_categories_child.category')
            // ->leftjoin('links', 'links.category', '=', 'categories.id')
            ->leftjoin('links', 'links.authority_site', '=', 'authority_sites.id')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            // ->where('categories.url', $category)
            ->where('links.anchor', 'like', $letter.'%')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->whereNull('links.deleted_at');
            // ->groupBy('links.category');
            // ->get();
    }

    public static function scopeauthorityavgtype($query,$type){
        $query->join('wordpress', 'authority_sites.wordpress', 'wordpress.id')
        ->where('wordpress.type', $type)
        ->orWhere('wordpress.type', 'both');
    }

    public static function select_by_type() {
        return AuthoritySite::whereIn('type', ['startingpage', 'childstartingpage'])
                        ->orderBy('url', 'asc')
                        ->get();
    }

    public static function list_for_packages($package) {
        return AuthoritySite::select('authority_sites.id', 'authority_sites.url as text', DB::raw('IF(packages_sites.id IS NULL, false, true) as selected'))
                        ->leftJoin('packages_sites', function($join) use ($package){
                            $join->on('packages_sites.authority_site', '=', 'authority_sites.id');
                            $join->on('packages_sites.package', '=', DB::raw("'".$package."'")); })
                        ->whereIn('authority_sites.type', array('startingpage', 'wordpress'))
                        ->orderBy('authority_sites.url', 'asc')
                        ->get();
    }

    public static function cleanup($site) {
        return AuthoritySite::where('site', $site)->delete();
    }

    public static function clean_by_url($url) {
        return AuthoritySite::where('url', $url)->delete();
    }

    public static function remove($site, $url) {
        return AuthoritySite::where('site', $site)->where('url', 'like', "%$url%")->delete();
    }

    public static function get_id($site, $url) {
        return AuthoritySite::where('site', $site)->where('url', 'like', "%$url%")->first();
    }

    public static function get_id_by_url($url) {
        $site = AuthoritySite::where('url', 'like', '%'.trim($url).'%')->first();
        return (!empty($site)) ? $site->id : null;
    }

    public static function get_site_by_url($url) {
        return AuthoritySite::where('url', $url)->first();
    }

    public static function featured($id, $featured) {
        return AuthoritySite::where('id', $id)->update(['featured' => mysql_null($featured)]);
    }

    public static function get_automatic($id) {
        $authority = AuthoritySite::join('sites', 'sites.id', '=', 'authority_sites.site')->where(['authority_sites.id' => $id])->select('sites.automatic')->first();
        return ($authority == null) ? $authority : $authority->automatic;
    }

    public static function get_authority_by_wp($wordpress) {
        return AuthoritySite::where('wordpress', $wordpress)->first();
    }

    public function scopeFilter($query, $val){
            return $query
            ->where('url', 'LIKE', '%'.$val.'%')
            ->orWhere('pa', 'LIKE', '%'.$val.'%')
            ->orWhere('da', 'LIKE', '%'.$val.'%')
            ->orWhere('tf', 'LIKE', '%'.$val.'%')
            ->orWhere('cf', 'LIKE', '%'.$val.'%')
            ->orWhere('subnet', 'LIKE', '%'.$val.'%');
    }

    public function scopeFilterWordpress($query, $val){
            return $query
            ->where('wordpress.url', 'LIKE', '%'.$val.'%')
            ->orWhere('pa', 'LIKE', '%'.$val.'%')
            ->orWhere('da', 'LIKE', '%'.$val.'%')
            ->orWhere('tf', 'LIKE', '%'.$val.'%')
            ->orWhere('cf', 'LIKE', '%'.$val.'%')
            ->orWhere('subnet', 'LIKE', '%'.$val.'%');
    }

    public static function category_by_authority_site($authority_id){
        return AuthoritySite::select('categories.name', 'authority_sites.id' , 'sites.id' ,'categories.id', 'sites.permanent')
                            ->join('sites', 'sites.id', 'authority_sites.site')
                            ->join('sites_categories_main', 'sites_categories_main.site', 'sites.id')
                            ->join('categories','categories.id','sites_categories_main.category')
                            ->where('authority_sites.id', $authority_id)
                            ->orderBy('categories.name', 'asc')
                            ->get();
    }

    public static function doesnt_exist($url) {
        return AuthoritySite::where('url', $url)->doesntExist();
    }

    public static function calculate_price($sites) {
        return AuthoritySite::select(DB::raw('IF(price_special IS NOT NULL and price_special < price, price_special, price) AS total'))
            ->whereIn('id', $sites)
            ->get()
            ->sum('total');
    }

    public static function getSite($site){
        return AuthoritySite::where('site', $site)->get()->pluck('id')->toArray();
    }

    public static function getWordpress($wordpress){
        return AuthoritySite::where('wordpress', $wordpress)->get()->pluck('id')->toArray();
    }

}
