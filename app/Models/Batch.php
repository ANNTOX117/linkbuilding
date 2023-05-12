<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model {

    use HasFactory;

    protected $table = 'batches';

    protected $fillable = [
        'mailing',
        'customer',
        'send_at',
        'waiting'
    ];

    public static function count_sent($mailing) {
        return Batch::where('mailing', $mailing)->whereNotNull('send_at')->get()->count();
    }

    public static function count_not_sent($mailing) {
        return Batch::where('mailing', $mailing)->whereNull('send_at')->get()->count();
    }

    public static function sent($batch) {
        return Batch::where('id', $batch)->update(['waiting' => null]);
    }

    public static function actives() {
        return Batch::select('batches.*', 'mailing.email', 'mailing.subject')
            ->join('mailing', 'mailing.id', '=', 'batches.mailing')
            ->where('mailing.active', 1)
            ->where('batches.waiting', 1)
            ->whereRaw('CONCAT(DATE(send_at), " ", TIME_FORMAT(DATE_ADD(send_at, INTERVAL 1 HOUR), "%H:00:00")) = CONCAT(CURDATE(), " ", TIME_FORMAT(NOW(), "%H:00:00"))')
            ->get();
    }

}
