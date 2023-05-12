<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model {

    use HasFactory;

    protected $table = 'groups';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function members() {
        return $this->hasMany('App\Models\Member', 'group', 'id');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->where('groups.name', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'name', $order = 'asc') {
        return Group::select('groups.id', 'groups.name', DB::raw('count(members.id) as total'))
                        ->leftJoin('members', 'members.group', '=', 'groups.id')
                        ->orderBy($sort, $order)
                        ->groupBy('groups.id')
                        ->get();
    }

    public static function groups_for_recipients() {
        return Group::select('groups.id', 'groups.name', DB::raw('count(members.id) as total'))
            ->leftJoin('members', 'members.group', '=', 'groups.id')
            ->groupBy('groups.id')
            ->having('total', '>', 0)
            ->orderBy('name', 'desc')
            ->get();
    }

    public static function with_pagination($sort = 'name', $order = 'asc', $pagination, $search = null) {
        return Group::select('groups.id', 'groups.name', DB::raw('count(members.id) as total'))
            ->leftJoin('members', 'members.group', '=', 'groups.id')
            ->filterSearch($search)
            ->orderBy($sort, $order)
            ->groupBy('groups.id')
            ->paginate($pagination);
    }

    public static function by_name($name) {
        return Group::where('name', $name)->first();
    }

}
