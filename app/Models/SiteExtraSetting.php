<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteExtraSetting extends Model
{
    use HasFactory;
    protected $table = "site_extra_settings";
    protected $fillable = ['google_analytics_code'];
    
}
