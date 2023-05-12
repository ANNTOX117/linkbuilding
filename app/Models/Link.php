<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Link extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'links';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'order',
        'url',
        'anchor',
        'follow',
        'blank',
        'alt',
        'description',
        'active',
        'authority_site',
        'wordpress',
        'language',
        'category',
        'ends_at',
        'visible_at',
        'approved_at',
        'published_at',
        'permanent',
        'client'
    ];

    public function clients() {
        return $this->hasOne('App\Models\User', 'id', 'client');
    }

    public function sitecategorychild()
    {
        return $this->belongsTo(SiteCategoryChild::class);
    }

    public function authority_sites() {
        return $this->hasOne('App\Models\AuthoritySite', 'id', 'authority_site');
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

    public static function all_items($sort = 'url', $order = 'asc') {
        return Link::active()->orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'url', $order = 'asc') {
        return Link::whereIn('active', array(1, 2))->orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'url', $order = 'asc', $pagination, $search = null) {
        return Link::select('links.*', 'links.url as url_link','users.name', 'users.lastname', 'authority_sites.url')
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->whereIn('links.active', array(1, 2))
            ->filterLinks($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }


    public function scopeFilterLinks($query, $val) {
        return $query->where('links.url', 'like', '%'.$val.'%')
                    ->orWhere('links.anchor', 'like', '%'.$val.'%')
                    ->orWhere('links.url', 'like', '%'.$val.'%')
                    ->orWhere('users.name', 'like', '%'.$val.'%')
                    ->orWhere('users.lastname', 'like', '%'.$val.'%')
                    ->orWhere('authority_sites.url', 'like', '%'.$val.'%');
    }

    public static function all_pendings($sort = 'url', $order = 'asc') {
        return Link::pending()->orderBy($sort, $order)->get();
    }

    public static function count_all_pendings() {
        return Link::pending()->get()->count();
    }

    public static function pendings_with_pagination($sort = 'url', $order = 'asc') {
        return Link::pending()->orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function pendings_with_filter($sort = 'url', $order = 'asc', $pagination, $search = null) {
        return Link::select('links.*', 'users.name', 'users.lastname', 'authority_sites.url')
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->where('links.active',2)
            ->filterLinks($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function approve($link) {
        return Link::where('id', $link)->update(['active' => 1]);
    }

    public static function for_approval($link, $data) {
        return Link::where('id', $link)->update($data);
    }

    public function scopeMylinks($query) {
        return $query->select('links.id', 'links.url as href', 'links.anchor', 'links.follow', 'links.permanent' , 'authority_sites.url' , 'categories.name' , 'links.published_at' , 'links.visible_at' , 'links.ends_at',  \DB::raw('DATEDIFF(links.ends_at, now()) as days'))
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('categories','categories.id','links.category')
            ->where('links.client',  auth()->user()->id)
            ->whereDate('links.ends_at', '>' , Carbon::today());
    }

    public function scopeMylinksabout($query) {
        return $query->select('links.id', 'links.url as href', 'links.anchor', 'links.follow' , 'authority_sites.url' , 'categories.name' , 'links.published_at' , 'links.visible_at' , 'links.ends_at',  \DB::raw('DATEDIFF(links.ends_at, now()) as days'))
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('categories','categories.id','links.category')
            ->where('links.active',1)
            ->whereNotNull('published_at')
            ->where('links.client',  auth()->user()->id)
            ->where(\DB::raw('DATEDIFF(links.ends_at, now())'),'<=', 60)
            ->whereDate('links.ends_at', '>' , Carbon::today());
    }

    public function scopeMyliksexpired($query) {
        return $query->select('links.id', 'links.url as href', 'links.anchor', 'links.follow' , 'authority_sites.url' , 'categories.name' , 'links.published_at' , 'links.visible_at' , 'links.ends_at',
            \DB::raw('DATEDIFF(links.ends_at, now()) as days'))
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('categories','categories.id','links.category')
            ->whereNull('published_at')
            ->where('links.client',  auth()->user()->id)
            ->where('links.active', 1)->whereDate('links.ends_at', '<' , Carbon::today());
    }

    public function scopeFilter($query, $val){
        return $query
        ->where('anchor', 'LIKE', '%'.$val.'%')
        ->orWhere('authority_sites.url', 'LIKE', '%'.$val.'%')
        ->orWhere('name', 'LIKE', '%'.$val.'%')
        ->orWhere('published_at', 'LIKE', '%'.$val.'%')
        ->orWhere('links.visible_at', 'LIKE', '%'.$val.'%')
        ->orWhere(\DB::raw('DATEDIFF(links.ends_at, now())'), 'LIKE', '%'.$val.'%');
    }

    public function scopeFilterApproval($query, $val){
        return $query->join('users', 'users.id', '=', 'links.client')
            ->where('links.url', 'like', '%'.$val.'%')
            ->orWhere('anchor', 'like', '%'.$val.'%')
            ->orWhere('alt', 'like', '%'.$val.'%')
            ->orWhere('authority_sites.url', 'like', '%'.$val.'%')
            ->orWhere('published_at', 'like', '%'.$val.'%')
            ->orWhere('links.visible_at', 'like', '%'.$val.'%')
            ->orWhere(\DB::raw('DATEDIFF(links.ends_at, now())'), 'like', '%'.$val.'%')
            ->orWhere('users.name', 'like', '%'.$val.'%')
            ->orWhere('users.lastname', 'like', '%'.$val.'%');
    }

    public static function my_liks_expired_mail() {
        return Link::select('links.id as link_id', 'links.url','links.anchor','links.follow','authority_sites.url','users.id','users.email','users.name','users.lastname','links.published_at','links.visible_at','links.ends_at', \DB::raw('DATEDIFF(links.ends_at, now()) as days'), 'languages.name as lang')
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->leftJoin('languages', 'languages.id', '=', 'users.language')
            ->whereNotNull('published_at')
            ->where('links.active', 1)
            ->whereDate('links.ends_at', '>' , Carbon::today())
            ->where(\DB::raw('DATEDIFF(links.ends_at, now())'),'<=', 60)
            ->get();
    }

    public static function my_liks_expired_mail_by_id($user_id) {
        return Link::select('links.url','links.anchor','links.follow','authority_sites.url','links.published_at','links.visible_at','links.ends_at', \DB::raw('DATEDIFF(now(), links.ends_at) as days'))
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->whereNull('published_at')
            ->where(['links.active' => 1 , 'users.id' => $user_id])
            ->whereYear('links.ends_at', '=', Carbon::now()->year)
            ->whereMonth('links.ends_at', '=', Carbon::now()->subMonth()->month)
            ->get();
    }

    public static function dashboard_by_user_id($status, $user_id, $about = false) {

        if (!$about) {
            return Link::join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->where(['links.active' => $status , 'users.id' => $user_id])
            ->get()->count();
        }
        else{
           return Link::join('external_links','external_links.links','links.id')
            ->join('categories','categories.id','links.category')
            ->where('external_links.active',1)
            ->where(\DB::raw('DATEDIFF(external_links.ends_at, now())'),'<', 60)
            ->whereDate('external_links.ends_at', '>' , Carbon::today())
            ->get()->count();
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'foreign_key', 'client');
    }

    public function externallinks(){
        return $this->hasOne(Externallink::class, 'links');
    }

    public static function actives() {
        return Link::where('client', auth()->user()->id)
                    ->where('active', 1)
                    ->where('ends_at', '>', Carbon::today())
                    ->get()
                    ->count();
    }

    public static function will_expire($months = 2) {
        return Link::where('client', auth()->user()->id)
            ->where('active', 1)
            ->where('ends_at', '<', Carbon::today()->add($months, 'month'))
            ->get()
            ->count();
    }

    public static function expired() {
        return Link::where('client', auth()->user()->id)
            ->where('active', 1)
            ->where('ends_at', '<', Carbon::today())
            ->get()
            ->count();
    }

    public static function to_publish() {
        return Link::select('links.*', DB::raw('IF(authority_sites.wordpress IS NOT NULL, "wp", "site") AS site'))
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->whereNull('links.active')
            ->whereNotNull('links.approved_at')
            ->whereNull('links.published_at')
            ->where('links.visible_at', '<=', Carbon::today())
            ->having('site', 'wp')
            ->get();
    }

    public static function published($id, $external) {
        return Link::where('id', $id)->update(['active' => 1, 'published_at' => Carbon::now(), 'external_url' => $external]);
    }

    public static function no_published($id) {
        return Link::where('id', $id)->update(['active' => 1, 'published_at' => null]);
    }

    public static function set_update($id, $column, $value) {
        return Link::whereIn('id', $id)->update([$column => $value]);
    }

    public static function get_links($ids) {
        return Link::whereIn('id', $ids)->get();
    }

    public static function categories_for_website($site) {
        return SiteCategoryMain::select('categories.*', 'sites_categories_main.visibility')
            ->join('categories', 'categories.id', '=', 'sites_categories_main.category')
            ->where('sites_categories_main.site', $site)
            ->where('sites_categories_main.visibility', '>', 0)
            ->orderBy('categories.name', 'asc')
            ->get();
    }

    public static function categories_list_for_website($site, $category) {
        return Link::select('links.*')
                    ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
                    ->where('links.active', 1)
                    ->where('authority_sites.type', 'startingpage')
                    ->where('authority_sites.site', $site)
                    ->where('links.category', $category)
                    ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
                    ->whereNotNull('links.approved_at')
                    ->whereNotNull('links.published_at');
                    // ->get();
    }

    public static function categories_list_with_links($site) {
        return SiteCategoryMain::select('categories.*', 'links.*', 'sites_categories_main.visibility')
                    ->join('categories', 'categories.id', '=', 'sites_categories_main.category')
                    ->join('links', 'categories.id', '=', 'links.category')
                    ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
                    ->where('links.active', 1)
                    ->where('authority_sites.type', 'startingpage')
                    ->where('authority_sites.site', $site)
                    ->where('sites_categories_main.site', $site)
                    ->where('sites_categories_main.visibility', '>', 0)
                    ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
                    ->whereNotNull('links.approved_at')
                    ->whereNotNull('links.published_at')
                    ->orderBy('links.category', 'asc');
                    // ->get();
    }

    public static function index_by_letter($site) {
        return Link::select(DB::raw('substr(categories.name, 1, 1) as letter'))
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('links.active', 1)
            ->where('authority_sites.site', $site)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy(DB::raw('substr(categories.name, 1, 1)'))
            ->orderBy('letter', 'asc')
            ->get();
    }

    public static function daughters_by_letter($site, $letter) {
        return Link::select('categories.*')
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->join('sites_categories_child', 'sites_categories_child.site', '=', 'authority_sites.site')
            ->join('categories', 'categories.id', '=', 'sites_categories_child.category')
            ->where('links.active', 1)
            ->where('authority_sites.site', $site)
            ->where('categories.name', 'like', $letter . '%')
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy('categories.id')
            ->get();
    }

    public static function daughters_for_website($site) {
        return Link::select('categories.*')
            ->join('categories', 'categories.id', '=', 'links.category')
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy('links.category')
            ->get();
    }

    public static function daughters_for_category($site, $category) {
        return Link::select('categories.*')
            ->join('categories', 'categories.id', '=', 'links.category')
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            ->where('categories.url', $category)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy('links.category')
            ->get();
    }

    public static function daughters_list_for_website($site, $category) {
        return Link::select('links.*')
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            ->where('links.category', $category)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->get();
    }

    public static function daughters_list_for_category($site, $category) {
        return Link::select('links.*')
            ->join('categories', 'categories.id', '=', 'links.category')
            ->join('authority_sites', 'authority_sites.id', '=', 'links.authority_site')
            ->where('links.active', 1)
            ->where('authority_sites.type', 'childstartingpage')
            ->where('authority_sites.site', $site)
            ->where('categories.url', $category)
            ->whereRaw('"'. date('Y-m-d') .'" between `links`.`visible_at` and `links`.`ends_at`')
            ->whereNotNull('links.approved_at')
            ->whereNotNull('links.published_at')
            ->groupBy('links.category')
            ->get();
    }


    public function scopeUnpublished($query) {
        return $query->whereDate('ends_at', Carbon::today())->whereNotNull('published_at')->whereNotNull('external_url');
    }

    public static function waiting_for_approval($sort = 'created_at', $order = 'desc', $pagination, $search = null){
        return Link::select('links.*', 'orders.details', 'authority_sites.url as site')
            ->join('orders', 'orders.id', 'links.order')
            ->join('authority_sites', 'authority_sites.id', 'links.authority_site')
            ->where('orders.status', 'paid')
            ->where('links.active', 2)
            ->whereNull('approved_at')
            ->whereNull('published_at')
            ->filterApproval($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function published_links() {
        return Link::select('id', 'url')
            ->where('active', 1)
            ->where('ends_at', '>', Carbon::today())
            ->whereNotNull('approved_at')
            ->whereNotNull('published_at')
            ->get();
    }

    public static function set_error($id, $error) {
        return Link::where('id', $id)->update(['error' => $error]);
    }

    public static function expired_links() {
        return Link::select('links.id as link_id', 'links.url','links.anchor','links.follow','authority_sites.url','users.id','users.email','users.name','users.lastname','links.published_at','links.visible_at','links.ends_at', \DB::raw('DATEDIFF(links.ends_at, now()) as days'), 'languages.name as lang')
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->leftJoin('languages', 'languages.id', '=', 'users.language')
            ->whereNotNull('approved_at')
            ->whereNull('published_at')
            ->where('links.active', 1)
            ->whereDate('links.ends_at', '<' , Carbon::today())
            ->groupBy('email')
            ->get();
    }

    public static function expired_link($user) {
        return Link::select('links.url','links.anchor','links.follow','authority_sites.url','links.published_at','links.visible_at','links.ends_at', \DB::raw('DATEDIFF(now(), links.ends_at) as days'))
            ->join('authority_sites','authority_sites.id','links.authority_site')
            ->join('users','users.id','links.client')
            ->whereNotNull('approved_at')
            ->whereNull('published_at')
            ->where('links.active', 1)
            ->where('users.id', $user)
            ->whereDate('links.ends_at', '<' , Carbon::today())
            ->get();
    }

}
