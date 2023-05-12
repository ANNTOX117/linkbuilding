<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaCategories extends Model {

    protected $table = 'meta_categories';

    protected $fillable = [
        'url',
        'category_id',
        'site_id',
        'header',
        'footer',
        'meta_title',
        'meta_description',
        'meta_keyword'
    ];

    public static function all_items($sort = 'category_id', $order = 'asc') {
        return MetaCategories::orderBy($sort, $order)->get();
    }

    public function scopeFilterSearch($query, $val) {
        return $query->where('meta_categories.category_id', 'like', '%'.$val.'%')
            ->orWhere('meta_categories.header', 'like', '%'.$val.'%')
            ->orWhere('meta_categories.footer', 'like', '%'.$val.'%');
    }

    public static function with_pagination($sort = 'category_id', $order = 'asc', $pagination, $search = null) {
        return MetaCategories::select('meta_categories.id', 'meta_categories.url','categories.name as category_name', 'sites.url as site_name')
            ->leftJoin('categories', 'categories.id', '=', 'meta_categories.category_id')
            ->join('sites', 'sites.id', '=', 'meta_categories.site_id')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }
}
