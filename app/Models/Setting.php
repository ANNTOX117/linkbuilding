<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public $timestamps = false;

    public static function mail_id($option) {
        return Setting::where('option', $option)->first()->id ?? null;
    }

}
