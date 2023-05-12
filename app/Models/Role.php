<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model {

    use HasFactory;

    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public function users() {
        return $this->hasMany('App\Models\User', 'role', 'id');
    }

    public function permissions() {
        return $this->hasOne('App\Models\Permission', 'role', 'id');
    }

    public function scopeNotAdmin($query) {
        return $query->where('id', '!=', 1);
    }

    public function scopeFilterSearch($query, $val) {
        return $query->where('roles.name', 'like', '%'.$val.'%')
            ->orWhere('roles.description', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Role::notAdmin()->orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return Role::notAdmin()
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function roles_for_recipients() {
        return Role::select('roles.id', 'roles.description as name', DB::raw('count(users.id) as total'))
            ->leftJoin('users', 'users.role', '=', 'roles.id')
            ->where('roles.description', '!=', 'Administrator')
            ->groupBy('roles.id')
            ->having('total', '>', 0)
            ->orderBy('name', 'desc')
            ->get();
    }

    public static function by_name($name) {
        return Role::where('description', $name)->first();
    }

}
