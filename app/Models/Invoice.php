<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoices';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'number',
        'client',
        'status',
        'payment_method'
    ];

}
