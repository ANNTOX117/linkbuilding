<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cart extends Model {

    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'item',
        'identifier',
        'details',
        'requested',
        'price',
        'user'
    ];

    public static function myTotal() {
        return Cart::select(DB::raw('sum(price) as total'))->where('user', Auth::user()->id)->first()->total;
    }

    public static function items() {
        return Cart::where('user', Auth::user()->id)->get();
    }

    public static function total() {
        return Cart::where('user', Auth::user()->id)->get()->count();
    }

    public static function list($offset, $limit, $sort = 'created_at', $order = 'desc', $term = null) {
        if(!empty($term)) {
            return Cart::select('cart.*')
                        ->leftJoin('packages', 'packages.id', '=', 'cart.identifier')
                        ->where('cart.user', Auth::user()->id)
                        ->where(function($where) use ($term) {
                            $where->where('cart.details', 'like', "%$term%")
                                ->orWhere('packages.name', 'like', "%$term%")
                                ->orWhere('packages.description', 'like', "%$term%")
                                ->orWhere('packages.price', 'like', "%$term%");
                        })
                        ->offset($offset)
                        ->limit($limit)
                        ->orderBy('cart.' . $sort, $order)
                        ->get();
        }
        else {
            return Cart::where('user', Auth::user()->id)->offset($offset)->limit($limit)->orderBy($sort, $order)->get();
        }
    }

    public static function get_details($item) {
        $details = Cart::find($item);
        return (!empty($details)) ? json_decode($details->details, true) : null;
    }

    public static function cleanup() {
        return Cart::where('user', Auth::user()->id)->delete();
    }

}
