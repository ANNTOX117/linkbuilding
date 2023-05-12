<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ByRegistration extends Model {

    use HasFactory;

    protected $table = 'by_registration';

    public $timestamps = false;

    protected $fillable = [
        'free_advertisement_coins'
    ];

}
