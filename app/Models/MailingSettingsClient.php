<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingSettingsClient extends Model {

    use HasFactory;

    protected $table = 'mailing_settings_client';

    public $timestamps = false;

    protected $fillable = [
        'notification_link_expire',
        'client',
        'newsletter'
    ];

}
