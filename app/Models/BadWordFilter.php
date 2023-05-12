<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadWordFilter extends Model {

    use HasFactory;

    protected $table = 'bad_word_filter';

    public $timestamps = false;

    protected $fillable = [
        'badword'
    ];

    public static function all_items($sort = 'badword', $order = 'asc') {
        return BadWordFilter::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'badword', $order = 'asc') {
        return BadWordFilter::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function with_filter($sort = 'badword', $order = 'asc', $pagination, $search = null) {
        return BadWordFilter::whereNotNull('badword')
            ->filterBadWord($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public function scopeFilterBadWord($query, $val){
        return $query->where('badword', 'like', '%'.$val.'%');
    }

    public static function already_exists($word) {
        return BadWordFilter::where('badword', $word)->exists();
    }

    public static function already_exists_on_edit($id, $word) {
        return BadWordFilter::where('id', '!=', $id)->where('badword', $word)->exists();
    }

}
