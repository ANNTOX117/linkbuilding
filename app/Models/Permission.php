<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Permission extends Model {

    use HasFactory;

    protected $table = 'permissions';

    public $timestamps = false;

    protected $fillable = [
        'role'
    ];

    public static function by_role($role) {
        return Permission::where('role', $role)->first();
    }

}
