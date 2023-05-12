<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model {

    use HasFactory;

    protected $table = 'article_images';

    public $timestamps = false;

    protected $fillable = [
        'article',
        'image',
        'featured'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'foreign_key', 'article');
    }
}
