<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'invoice_items';

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
