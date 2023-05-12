<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleRequested extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'articles_requested';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'customer'
    ];

    public function authority_sites() {
        return $this->hasOne('App\Models\AuthoritySite', 'id', 'authority_site');
    }

    public function customers() {
        return $this->hasOne('App\Models\User', 'id', 'customer');
    }

    public function writers() {
        return $this->hasOne('App\Models\User', 'id', 'writer');
    }

    public static function all_items($sort = 'created_at', $order = 'desc') {
        return ArticleRequested::select('articles_requested.*', 'authority_sites.url as site')
                                ->leftJoin('articles', 'articles.id', '=', 'articles_requested.article')
                                ->leftJoin('authority_sites', 'authority_sites.id', '=', 'articles_requested.authority_site')
                                ->orderBy($sort, $order)
                                ->get();
    }

    public static function with_pagination($sort = 'created_at', $order = 'desc') {
        return ArticleRequested::select('articles_requested.*', 'authority_sites.url as site')
            ->leftJoin('articles', 'articles.id', '=', 'articles_requested.article')
            ->leftJoin('authority_sites', 'authority_sites.id', '=', 'articles_requested.authority_site')
            ->orderBy($sort, $order)
            ->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'created_at', $order = 'desc', $pagination, $search = null) {
        return ArticleRequested::select('articles_requested.*', 'authority_sites.url as site', 'c.name' , 'c.lastname', 'w.name' , 'w.lastname')
            ->join('users as c', 'c.id','articles_requested.customer')
            ->join('users as w', 'w.id','articles_requested.writer')
            ->leftJoin('articles', 'articles.id', '=', 'articles_requested.article')
            ->leftJoin('authority_sites', 'authority_sites.id', '=', 'articles_requested.authority_site')
            ->filterArticleRequest($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilterArticleRequest($query, $val){
            return $query->where('c.name', 'LIKE', '%'.$val.'%')
                        ->orWhere('c.lastname', 'LIKE', '%'.$val.'%')
                        ->orWhere('w.name', 'LIKE', '%'.$val.'%')
                        ->orWhere('w.lastname', 'LIKE', '%'.$val.'%')
                        ->orWhere('articles_requested.article', 'LIKE', '%'.$val.'%')
                        ->orWhere('authority_sites.url', 'LIKE', '%'.$val.'%')
                        ->orWhere('articles_requested.created_at', 'LIKE', '%'.$val.'%');
    }

    public static function assigned_with_filter($sort = 'created_at', $order = 'desc', $pagination, $search = null) {
        return ArticleRequested::select('articles_requested.*', 'authority_sites.url as site', 'c.name', 'c.lastname', 'w.name', 'w.lastname')
            ->join('users as c', 'c.id','articles_requested.customer')
            ->join('users as w', 'w.id','articles_requested.writer')
            ->leftJoin('articles', 'articles.id', '=', 'articles_requested.article')
            ->leftJoin('authority_sites', 'authority_sites.id', '=', 'articles_requested.authority_site')
            ->where('articles_requested.writer', auth()->id())
            ->filterArticleRequestAssigned($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilterArticleRequestAssigned($query, $val){
            return $query->where('c.name', 'LIKE', '%'.$val.'%')
                        ->orWhere('c.lastname', 'LIKE', '%'.$val.'%')
                        ->orWhere('w.name', 'LIKE', '%'.$val.'%')
                        ->orWhere('w.lastname', 'LIKE', '%'.$val.'%')
                        ->orWhere('articles_requested.article', 'LIKE', '%'.$val.'%')
                        ->orWhere('authority_sites.url', 'LIKE', '%'.$val.'%')
                        ->orWhere('articles_requested.created_at', 'LIKE', '%'.$val.'%');
    }

    public static function writer_assigned($article) {
        return ArticleRequested::where('id', $article)->first()->writer ?? null;
    }

    public static function assign($article, $writer) {
        return ArticleRequested::where('id', $article)->update(['writer' => $writer]);
    }

    public static function approve($article) {
        return ArticleRequested::where('id', $article)->update(['approved_at' => Carbon::now()]);
    }

    public static function remove($article) {
        return ArticleRequested::where('article', $article)->delete();
    }

    public static function get_article($article) {
        return ArticleRequested::where('id', $article)->first();
    }

}
