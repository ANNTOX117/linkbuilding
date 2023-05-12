<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleRule extends Model {

    use HasFactory;

    protected $table = 'article_rules';

    public $timestamps = false;

    protected $fillable = [
        'max_words',
        'max_links'
    ];

    public static function all_items() {
        return ArticleRule::first();
    }

    public static function max_links() {
        return @ArticleRule::first()->max_links ?? 0;
    }

}
