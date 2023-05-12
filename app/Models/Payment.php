<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $table = 'payments';

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'customer',
        'transaction',
        'key',
        'amount',
        'bank',
        'package',
        'limit',
        'status',
    ];

    public function customers() {
        return $this->hasOne('App\Models\User', 'id', 'customer');
    }

    public function packages() {
        return $this->hasOne('App\Models\Package', 'id', 'package');
    }

    public function scopeNotCancelled($query) {
        return $query->where('status', '!=', 'cancelled');
    }

    public static function all_items($sort = 'created_at', $order = 'desc') {
        return Payment::notCancelled()->orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'created_at', $order = 'desc') {
        return Payment::notCancelled()->orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function approve($payment) {
        return Payment::where('id', $payment)->update(['status' => 'success']);
    }

}
