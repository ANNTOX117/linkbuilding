<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RuleDiscount extends Model {

    use HasFactory;

    protected $table = 'rules_discounts';

    public $timestamps = false;

    protected $fillable = [
        'discount',
        'user',
        'group',
        'product',
        'price',
        'percentage',
        'active'
    ];

    public function discounts() {
        return $this->hasOne('App\Models\GroupDiscount', 'id', 'discount');
    }

    public function users() {
        return $this->hasOne('App\Models\User', 'id', 'user');
    }

    public function groups() {
        return $this->hasOne('App\Models\Group', 'id', 'group');
    }

    public static function all_items($sort = 'id', $order = 'asc') {
        return RuleDiscount::orderBy($sort, $order)->get();
    }

    public static function with_pagination($sort = 'id', $order = 'asc') {
        return RuleDiscount::orderBy($sort, $order)->paginate(env('APP_PAGINATE'));
    }

    public static function status($id, $active) {
        return RuleDiscount::where('id', $id)->update(['active' => mysql_null($active)]);
    }

    public static function check_rule_for_user($user) {
        return RuleDiscount::select('discount', 'price', 'percentage')->where('user', $user)->where('active', 1)->first();
    }

    public static function check_rule_for_groups($user) {
        return RuleDiscount::select('rules_discounts.discount', 'rules_discounts.price', 'rules_discounts.percentage')
                            ->join('members', 'members.group', '=', 'rules_discounts.group')
                            ->where('members.user', $user)
                            ->where('active', 1)
                            ->first();
    }

    public static function check_rule_for_product($product) {
        return RuleDiscount::select('discount', 'price', 'percentage')->where('product', $product)->where('active', 1)->first();
    }

}
