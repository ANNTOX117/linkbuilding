<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailing extends Model {

    use HasFactory;

    protected $table = 'mailing';

    protected $fillable = [
        'email',
        'subject',
        'batch',
        'size',
        'interval'
    ];

    public function templates() {
        return $this->hasOne('App\Models\MailingText', 'id', 'email');
    }

    public function batches() {
        return $this->hasMany('App\Models\Batch', 'mailing', 'id');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftJoin('mailing_text', 'mailing_text.id', '=', 'mailing.email')
            ->where('mailing.subject', 'like', '%'.$val.'%')
            ->orWhere('mailing_text.name', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'created_at', $order = 'desc') {
        return Mailing::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'created_at', $order = 'desc', $pagination, $search = null) {
        return Mailing::select('mailing.*')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function cancel($mailing) {
        return Mailing::where('id', $mailing)->update(['active' => null]);
    }

}
