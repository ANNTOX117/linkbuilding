<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class PagebuilderSetting extends Model {

    use HasFactory;
    use Rememberable;

    protected $table = 'pagebuilder__settings';

    protected $fillable = [
        'site',
        'setting',
        'value',
        'is_array',
        'created_at',
        'updated_at'
    ];

    public function scopeCached($query){
        return env('APP_ENV') === 'production' ? $query->remember(60 * 60) : $query;
    }

}
