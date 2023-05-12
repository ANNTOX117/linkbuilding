<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model {

    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order',
        'invoice',
        'item',
        'identifier',
        'details',
        'requested',
        'price',
        'subtotal',
        'discounts',
        'credits',
        'partial',
        'tax',
        'total',
        'payment',
        'status',
        'user'
    ];

    public function users() {
        return $this->hasOne('App\Models\User', 'id', 'user');
    }

    public function link(){
        return $this->hasOne('App\Models\Link', 'order');
    }

    public function article(){
        return $this->hasOne('App\Models\Article', 'order');
    }

    public function scopeFilterSearch($query, $val) {
        return $query->leftjoin('users', 'users.id', '=', 'orders.user')
            ->where('users.name', 'like', '%'.$val.'%')
            ->orWhere('users.lastname', 'like', '%'.$val.'%')
            ->orWhere('orders.order', 'like', '%'.$val.'%')
            ->orWhere('orders.item', 'like', '%'.$val.'%')
            ->orWhere('orders.status', 'like', '%'.$val.'%');
    }

    public static function all_items($sort = 'created_at', $order = 'desc') {
        return Order::select('id', 'order', 'invoice', DB::raw('COUNT(id) as amount'), DB::raw('GROUP_CONCAT(DISTINCT(orders.item) SEPARATOR ", ") as products'), DB::raw('GROUP_CONCAT(item) as item'), DB::raw('GROUP_CONCAT(identifier) as identifier'), DB::raw('SUM(price) as price'), 'status', 'created_at' )
            ->where('user', Auth::user()->id)
            ->groupBy('order')
            ->orderBy($sort, $order)
            ->get();
    }

    public static function with_pagination($sort = 'orders.created_at', $order = 'orders.desc') {
        return Order::select('orders.id', 'orders.order', 'orders.invoice', DB::raw('COUNT(orders.id) as amount'), DB::raw('GROUP_CONCAT(DISTINCT(orders.item) SEPARATOR ", ") as products'), DB::raw('GROUP_CONCAT(orders.item) as item'), DB::raw('GROUP_CONCAT(orders.identifier) as identifier'), DB::raw('orders.total as price'), 'orders.status', 'orders.created_at', 'users.id as ready')
            ->where('orders.user', Auth::user()->id)
            ->where('orders.status', 'paid')
            ->whereNull('orders.requested')
            ->where('orders.item', '!=', 'renewal')
            ->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'orders.user')
                    ->whereNotNull('users.company')
                    ->whereNotNull('users.name')
                    ->whereNotNull('users.lastname')
                    ->whereNotNull('users.address')
                    ->whereNotNull('users.city')
                    ->whereNotNull('users.country')
                    ->whereNotNull('users.postal_code');
            })
            ->groupBy('orders.order')
            ->orderBy($sort, $order)
            ->paginate(env('APP_PAGINATE'));
    }

    public static function list($sort = 'created_at', $order = 'desc', $pagination, $search = null) {
        return Order::select('orders.id', 'orders.order', 'orders.invoice', 'orders.user', DB::raw('COUNT(orders.id) as amount'), DB::raw('GROUP_CONCAT(DISTINCT(orders.item) SEPARATOR ", ") as products'), DB::raw('GROUP_CONCAT(item) as item'), DB::raw('GROUP_CONCAT(identifier) as identifier'), DB::raw('total as price'), 'status', 'orders.created_at' )
            ->filterSearch($search)
            ->groupBy('order')
            ->orderBy($sort, $order)
            ->paginate($pagination);
    }

    public static function payment($order) {
        return Order::where('order', $order)->first()->payment ?? null;
    }

    public static function ids($order) {
        return Order::where('order', $order)->get()->pluck('id');
    }

    public static function for_webhook($payment) {
        return Order::where('payment', $payment)->get()->pluck('id');
    }

    public static function get_details($order) {
        return Order::where('order', $order)->whereNull('requested')->get();
    }

    public static function my_order($order) {
        return Order::select('id', 'order', 'invoice', 'details', DB::raw('COUNT(id) as amount'), DB::raw('GROUP_CONCAT(DISTINCT(orders.item) SEPARATOR ", ") as products'), DB::raw('GROUP_CONCAT(item) as item'), DB::raw('GROUP_CONCAT(identifier) as identifier'), 'price', 'subtotal', 'discounts', 'partial', 'tax', 'total', 'payment', 'status', 'created_at' )->where('user', Auth::user()->id)
            ->where('order', $order)
            ->where('item', '!=', 'renewal')
            ->where('status', 'paid')
            ->groupBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function approve($order) {
        return Order::where('order', $order)->update(['status' => 'paid']);
    }

    public static function subtotal($order) {
        return Order::where('order', $order)->sum('price');
    }

    public static function update_payment($order, $payment) {
        return Order::where('order', $order)->update(['payment' => $payment]);
    }

    public static function total_per_years($user, $years) {
        $year = date('Y') - $years;
        return Order::where('user', $user)->whereYear('created_at', '>', $year)->sum('price');
    }

    public static function orders_by_range($user) {
        return Order::select(DB::raw('min(orders.created_at) as min_date'), DB::raw('max(orders.created_at) max_date'))
            ->where('user', $user)
            ->where('status', 'paid')
            ->groupBy('user')
            ->first();
    }

    public static function order_by_user($user){
        return Order::where('user', $user)->orderBy('id', 'desc')->first();
    }

    public static function earnings_today() {
        return Order::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->groupBy('order')
            ->get()
            ->sum('total');
    }

    public static function earnings_this_month() {
        return Order::where('status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('order')
            ->get()
            ->sum('total');
    }

    public static function earnings_total() {
        return Order::where('status', 'paid')
            ->groupBy('order')
            ->get()
            ->sum('total');
    }

    public static function is_empty() {
        return Order::where('user', auth()->id())->doesntExist();
    }

    public static function latest_invoices($total = 5) {
        return Order::select('orders.*')
            ->join('users', 'users.id', '=', 'orders.user')
            ->where('orders.user', auth()->id())
            ->where('orders.status', 'paid')
            ->whereNotNull('users.company')
            ->whereNotNull('users.name')
            ->whereNotNull('users.lastname')
            ->whereNotNull('users.address')
            ->whereNotNull('users.city')
            ->whereNotNull('users.country')
            ->whereNotNull('users.postal_code')
            ->groupBy('orders.order')
            ->orderBy('orders.created_at', 'desc')
            ->limit($total)
            ->get();
    }

    public static function paid() {
        return Order::where('status', 'paid')->whereNotNull('invoice')->groupBy('order')->get()->count();
    }

    public static function set_invoice($order, $invoice) {
        return Order::where('order', $order)->where('status', 'paid')->whereNull('invoice')->update(['invoice' => $invoice]);
    }

}
