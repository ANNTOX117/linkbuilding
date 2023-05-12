<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model {

    use HasFactory;

    protected $table = 'generals';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'value'
    ];

    public static function mollie_key() {
        return General::where('key', 'mollie_key')->first()->value ?? null;
    }

    public static function currency() {
        return General::where('key', 'currency')->first()->value ?? '€';
    }

    public static function price_per_article() {
        return General::where('key', 'price_article')->first()->value ?? null;
    }

    public static function invoice_header() {
        return General::where('key', 'invoice_header')->first()->value ?? null;
    }

    public static function requested_articles() {
        return General::where('key', 'requested_articles')->first()->value ?? null;
    }

    public static function coins_on_register() {
        return General::where('key', 'register_coins')->first()->value ?? null;
    }

    public static function client_id() {
        return General::where('key', 'client_id')->first()->value ?? null;
    }

}
