<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingStaffel extends Model {

    use HasFactory;

    protected $table = 'pricing_staffels';

    public $timestamps = false;

    protected $fillable = [
        'role',
        'discount'
    ];

}
