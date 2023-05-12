<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model {

    use HasFactory;

    protected $table = 'languages';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public static function all_items($sort = 'description', $order = 'asc') {
        return Language::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'description', $order = 'asc') {
        return Language::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'description', $order = 'asc', $pagination="", $search = null) {
        return Language::whereNotNull('name')
            ->filter($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function by_name($name) {
        return Language::where('name', $name)->first();
    }

    public function scopeFilter($query, $val){
            return $query->where('name', 'like', '%'.$val.'%')->orWhere('description', 'like', '%'.$val.'%');
    }

    public static function already_exists($language, $id = null) {
        if(!empty($id)) {
            return Language::where('id', '!=', $id)->where('name', $language)->exists();
        }

        return Language::where('name', $language)->exists();
    }

}
