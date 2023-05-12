<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Externallink extends Model
{
    use HasFactory;

    protected $table = 'external_links';

    protected $fillable = [
        'type',
        'active',
        'article',
        'authority_site',
        'wordpress',
        'url',
        'visible_at',
        'ends_at',
        'published_at',
        'approved_at',
    ];

    public function scopePublished($query) {
        return $query->whereDate('visible_at', Carbon::today())->whereNotNull('approved_at');
    }

    public function scopeUnpublished($query) {
        return $query->whereDate('ends_at', Carbon::today())->whereNotNull('published_at');
    }
}
