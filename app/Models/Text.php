<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model {

    use HasFactory;

    protected $table = 'texts';

    protected $fillable = [
        'name',
        'title',
        'description',
        'language'
    ];

    public function languages() {
        return $this->hasOne('App\Models\Language', 'id', 'language');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftJoin('languages', 'languages.id', '=', 'texts.language')
            ->where('texts.name', 'like', '%'.$val.'%')
            ->orWhere('texts.title', 'like', '%'.$val.'%')
            ->orWhere('texts.description', 'like', '%'.$val.'%')
            ->orWhere('languages.description', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Text::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return Text::select('texts.*')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function already_exists($name, $language) {
        return Text::where('name', $name)->where('language', $language)->exists();
    }

    public static function get_info($name, $param, $language = 'nl') {
        return Text::select('texts.*')
                    ->join('languages', 'languages.id', '=', 'texts.language')
                    ->where('texts.name', $name)
                    ->where('languages.name', $language)
                    ->first()
                    ->$param;
    }

}
