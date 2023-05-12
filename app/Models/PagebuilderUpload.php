<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class PagebuilderUpload extends Model {

    use HasFactory;
    use Rememberable;

    protected $table = 'pagebuilder__uploads';

    protected $fillable = [
        'site',
        'public_id',
        'original_file',
        'mime_type',
        'server_file',
        'created_at',
        'updated_at'
    ];

    public function scopeCached($query){
        return env('APP_ENV') === 'production' ? $query->remember(60 * 60) : $query;
    }

}
