<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuthoritySite;
use App\Models\Cart;
use App\Models\Link;
use App\Models\Article;
use App\Models\Order;
use App\Models\User;
use App\Models\MailingText;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class PaymentsController extends Controller {

    public function __construct() {
        Mollie::api()->setApiKey(mollie_key());
    }

    public function pay(Request $request) {

        $order       = get_invoice();
        $description = "Order " . $order;
        $cart        = Cart::items();
        $subtotal    = Cart::myTotal();
        $discounts   = '0';
        $discount    = get_discount($subtotal);
        $json        = array();

        if(count($discount) > 0) {
            foreach($discount as $item) {
                $json[$item['type']] = $item['discount'];
                $discounts = $discounts + floatval($item['discount']);
            }
        }

        $total       = (floatval($discounts) > 0) ? (floatval($subtotal) - floatval($discounts)) : floatval($subtotal);
        $vat         = get_vat();
        $percent     = ((floatval($vat) / 100) * floatval($total));
        $payment     = floatval($total) + floatval($percent);
        $coins       = 0;
        $discounted  = 0;

        $credits = User::get_credits();
        if(!empty($credits) and floatval($credits) > 0) {
            $diff  = floatval($payment) - floatval($credits);
            $coins = floatval($payment) - floatval($diff);
            $paid  = ($diff > 0) ? $coins : ($credits - abs($diff));

            $coins      = get_money($paid);
            $discounted = get_money($payment);
            $payment    = floatval($payment) - floatval($paid);

            if(get_money($payment) <= 0) {

                User::deduct_credits($coins);
                if(!empty($cart)) {
                    foreach($cart as $c) {
                        $detail = json_decode($c->details, true);
                        if(array_key_exists('renewal', $detail)){
                            $c->item == 'blog article' ? $this->renew_articles($detail['renewal'], $c->details) : $this->renew_links($detail['renewal'], $c->details);
                        }
                            Order::create([
                                'order'      => $order,
                                'item'       => $c->item,
                                'identifier' => array_key_exists('renewal', $detail) ? $detail['renewal'] : $c->identifier,
                                'details'    => $c->details,
                                'requested'  => $c->requested,
                                'price'      => $c->price,
                                'subtotal'   => $subtotal,
                                'discounts'  => json_encode($json),
                                'partial'    => $total,
                                'credits'    => $credits,
                                'tax'        => $percent,
                                'total'      => $payment,
                                'payment'    => 1,
                                'status'     => 'open',
                                'user'       => Auth::user()->id
                            ]);
                    }
        
                    Cart::cleanup();
                }
                return redirect()->route('transaction', ['order' => $order]);
            }
        }
        
        $provider = get_paypal_credentials();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => round_price($payment)
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->route('cart')->with('error', 'Something went wrong.');
        } else {
            return redirect()->route('cart')->with('error', 'Something went wrong.');
        }

        // $mollie_id = 0;
        // $status    = 'paid';

        // Mollie payment
        // if(get_money($payment) > 0) {
        //     $mollie = Mollie::api()->payments->create([
        //         "amount" => [
        //             "currency" => get_currency(),
        //             "value"    => get_money($payment),
        //         ],
        //         "description"  => $description,
        //         "redirectUrl"  => (App::getLocale() == 'nl') ? route('transaction', ['order' => $order]) : url(App::getLocale() . '/payment/order/' . $order),
        //         "webhookUrl"   => route('webhook'),
        //         "metadata"     => ["order_id" => $order]
        //     ]);

        //     $mollie_id = $mollie->id;
        //     $status    = 'open';
        // }

        // if(get_money($payment) > 0) {
        //     return redirect($mollie->getCheckoutUrl(), 303);
        // } else {
        //     return redirect()->route('transaction', ['order' => $order]);
        // }
    }

    public function renewTransaction(Request $request, $order){

        $provider = get_paypal_credentials();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()->route('renewed', ['order' => $order]);
        } else {
            return redirect()->route('renewed', ['order' => $order]);
        }
    }

    public function successTransaction(Request $request){
        
        $provider = get_paypal_credentials();
        $response = $provider->capturePaymentOrder($request['token']);

        $order       = get_invoice();
        $description = "Order " . $order;
        $cart        = Cart::items();
        $subtotal    = Cart::myTotal();
        $discounts   = '0';
        $discount    = get_discount($subtotal);
        $json        = array();

        if(count($discount) > 0) {
            foreach($discount as $item) {
                $json[$item['type']] = $item['discount'];
                $discounts = $discounts + floatval($item['discount']);
            }
        }

        $total       = (floatval($discounts) > 0) ? (floatval($subtotal) - floatval($discounts)) : floatval($subtotal);
        $vat         = get_vat();
        $percent     = ((floatval($vat) / 100) * floatval($total));
        $payment     = floatval($total) + floatval($percent);
        $coins       = 0;
        $discounted  = 0;

        $credits = User::get_credits();
        if(!empty($credits) and floatval($credits) > 0) {
            $diff  = floatval($payment) - floatval($credits);
            $coins = floatval($payment) - floatval($diff);
            $paid  = ($diff > 0) ? $coins : ($credits - abs($diff));

            $coins      = get_money($paid);
            $discounted = get_money($payment);
            $payment    = floatval($payment) - floatval($paid);

            User::deduct_credits($coins);
        }

        if(!empty($cart)) {
            foreach($cart as $c) {
                $detail = json_decode($c->details, true);
                if(array_key_exists('renewal', $detail)){
                    $c->item == 'blog article' ? $this->renew_articles($detail['renewal'], $c->details) : $this->renew_links($detail['renewal'], $c->details);
                }
                    Order::create([
                        'order'      => $order,
                        'item'       => $c->item,
                        'identifier' => array_key_exists('renewal', $detail) ? $detail['renewal'] : $c->identifier,
                        'details'    => $c->details,
                        'requested'  => $c->requested,
                        'price'      => $c->price,
                        'subtotal'   => $subtotal,
                        'discounts'  => json_encode($json),
                        'partial'    => $total,
                        'credits'    => $credits,
                        'tax'        => $percent,
                        'total'      => $payment,
                        'payment'    => 1,
                        'status'     => 'open',
                        'user'       => Auth::user()->id
                    ]);
            }

            Cart::cleanup();
        }

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()->route('transaction', ['order' => $order]);
        } else {
            return redirect()->route('transaction', ['order' => $order]);
            // return redirect()->route('cart')->with('error', 'Something went wrong.');
        }
    }

    public function cancelTransaction(Request $request){
        return redirect()->route('customer_cart')->with('error', 'Transaction cancelled');
    }

    public function cancelRenew(Request $request, $order){
        $orders    = Order::ids($order);
        foreach($orders as $o) {
            $order = Order::find($o);
            $order->delete();
        }
        return redirect()->route('customer_links')->with('error', 'Transaction cancelled');
    }

    public function transaction(Request $request, $order) {
        $title     = trans('Your payment');
        $orders    = Order::ids($order);
        $paymentId = Order::payment($order);
        $its_free  = false;
        $payment   = null;

        if(is_numeric($paymentId) and $paymentId == 0) {
            $its_free = true;
        }

        if(strpos($paymentId, 'tr_') !== false) {
            $payment = Mollie::api()->payments->get($paymentId);
        }

        if(!empty($orders)) {
            foreach($orders as $o) {
                $order = Order::find($o);

                if(($order->status != 'paid') or $its_free) { //and @$payment->isPaid()
                    self::copy_links($order->details, $order->id, $order->user, $order->item, $order->requested);
                    // self::send_email($order);
                }

                if($its_free) {
                    $order->payment = 1;
                } else {
                    $order->status  = 'paid';
                    if(!empty(@$payment->status)) {
                        // $order->status  = strtolower(@$payment->status);
                    }
                }

                $order->save();

                self::invoice_number($order->order);
            }
        }

        return view('payments.transactions', compact('title', 'order', 'payment', 'its_free'));
    }

    public function renewal(Request $request) {
        $order_id    = request()->segment(3);
        $order       = Order::get_details($order_id)[0];
        $description = "Order " . $order_id;
        $subtotal    = Order::subtotal($order_id);
        $discount    = (!empty($order['details'])) ? get_discount($order->$subtotal) : 0;
        $discounts   = '0';
        
        if(!empty($discount) and count($discount) > 0) {
            foreach($discount as $item) {
                $json[$item['type']] = $item['discount'];
                $discounts = $discounts + floatval($item['discount']);
            }
        }

        $total       = (floatval($discounts) > 0) ? (floatval($subtotal) - floatval($discounts)) : floatval($subtotal);
        // $total       = (!empty($order['details'])) ? (floatval($subtotal) - floatval($discount[0]['percentage'])) : floatval($subtotal);
        $vat         = get_vat();
        $percent     = ((floatval($vat) / 100) * floatval($total));
        $payment     = floatval($total) + floatval($percent);

        $order->subtotal = $subtotal;
        $order->discounts = $discount;
        $order->tax = $percent;
        $order->total = $payment;
        $order->save();

        $provider = get_paypal_credentials();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('renewTransaction', ['order' => $order->order]),
                "cancel_url" => route('cancelRenew', ['order' => $order->order]),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => round_price($payment)
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->route('cart')->with('error', 'Something went wrong.');
        } else {
            return redirect()->route('cart')->with('error', 'Something went wrong.');
        }

        // $payment = Mollie::api()->payments->create([
        //     "amount" => [
        //         "currency" => get_currency(),
        //         "value"    => get_money($payment),
        //     ],
        //     "description"  => $description,
        //     "redirectUrl"  => (App::getLocale() == 'nl') ? route('renewed', ['order' => $order_id]) : url(App::getLocale() . '/payment/renewed/' . $order),
        //     "webhookUrl"   => route('webhook_renewed'),
        //     "metadata"     => ["order_id" => $order_id]
        // ]);

        // Order::update_payment($order_id, $payment->id);

        // return redirect($payment->getCheckoutUrl(), 303);
    }

    public function renewed(Request $request, $order) {
        $title     = trans('Your payment');
        $orders    = Order::ids($order);
        $paymentId = Order::payment($order);
        $payment   = null;
        // $payment   = Mollie::api()->payments->get($paymentId);

        if(!empty($orders)) {
            foreach($orders as $o) {
                $order = Order::find($o);

                if($order->status != 'paid') { //and $payment->isPaid()
                    self::renew_links($order->identifier, $order->details);
                    // self::send_email($order);
                }

                $order->status = strtolower('paid');
                $order->save();
                Order::update_payment($o, 2);

                self::invoice_number($order->order);
            }
        }

        return view('payments.transactions', compact('title', 'order', 'payment'));
    }

    public function webhook_renewed(Request $request) {
        try{
            if(!$request->has('id')) {
                return trans('Order not updated');
            }

            $payment = Mollie::api()->payments()->get($request->id);
            $orders  = Order::for_webhook($request->id);

            if(!empty($orders)) {
                foreach($orders as $o) {
                    $order = Order::find($o);

                    if($order->status != 'paid' and $payment->isPaid()) {
                        self::renew_links($order->identifier, $order->details);
                        self::send_email($order);
                    }

                    $order->status = strtolower($payment->status);
                    $order->save();

                    self::invoice_number($order->order);
                }
            }

            return trans('Updated order');
        } catch(\Exception $e){
            \Log::debug($e->getMessage());
            return trans('Order not updated');
        }
    }

    public function webhook(Request $request) {
        try{
            if(!$request->has('id')) {
                return trans('Order not updated');
            }

            $payment = Mollie::api()->payments()->get($request->id);
            $orders  = Order::for_webhook($request->id);

            if(!empty($orders)) {
                foreach($orders as $o) {
                    $order = Order::find($o);

                    if($order->status != 'paid' and $payment->isPaid()) {
                        self::copy_links($order->details, $order->id, $order->user, $order->item, $order->requested);
                        self::send_email($order);
                    }

                    $order->status = strtolower($payment->status);
                    $order->save();

                    self::invoice_number($order->order);
                }
            }

            return trans('Updated order');
        } catch(\Exception $e){
            \Log::debug($e->getMessage());
            return trans('Order not updated');
        }
    }

    private function copy_links($details, $order, $owner, $item, $requested) {
        if(!empty($details)) {
            $links    = [];
            $articles = [];
            $user     = User::find($owner);
            $details  = json_decode($details, true);

            if($item == 'packages') {
                foreach($details as $i => $detail) {
                    if(!empty($detail)) {
                        $permanent    = $detail['years'] == -5 ? true : false;
                        $authority    = null;
                        $active       = null;
                        $language     = null;
                        $ends_at      = Carbon::parse($detail['date'])->addYears($permanent ? 100 : $detail['years'])->format('Y-m-d');
                        $approved_at  = null;
                        $published_at = null;

                        if(!empty($detail['authority'])) {
                            $authority = AuthoritySite::find($detail['authority']);
                            $language  = (!empty($authority->wordpress)) ? @$authority->wordpresses->language : @$authority->sites->language;

                            if(!empty($authority->wordpress)) {
                                if(@$authority->wordpresses->automatic == 1) {
                                    $active      = 1;
                                    $approved_at = Carbon::now()->format('Y-m-d H:i:s');
                                } else {
                                    $active = 2;
                                }
                            } else {
                                if(@$authority->sites->automatic == 1) {
                                    $active       = 1;
                                    $approved_at  = Carbon::now()->format('Y-m-d H:i:s');
                                    $published_at = Carbon::now()->format('Y-m-d H:i:s');
                                } else {
                                    $active = 2;
                                }
                            }
                        }

                        $links[] = array('order'         => $order,
                                        'url'            => $detail['url'],
                                        'anchor'         => $detail['anchor'],
                                        'follow'         => get_bool(get_bool_follow($detail['follow'])),
                                        'blank'          => get_bool($detail['blank']),
                                        'alt'            => $detail['title'],
                                        'active'         => $active,
                                        'authority_site' => $detail['authority'],
                                        'language'       => $language,
                                        'category'       => $detail['category'],
                                        'ends_at'        => $ends_at,
                                        'visible_at'     => $detail['date'],
                                        'approved_at'    => $approved_at,
                                        'published_at'   => $published_at,
                                        'permanent'      => $permanent ? 1 : null,
                                        'client'         => $user->id);
                    }
                }
            }

            if($item == 'startpage link' or $item == 'blog sidebar link') {
                if(count($details) == 1) {
                    $details = $details[0];
                }

                if(!empty($details)) {
                    if(array_key_exists('renewal', $details)){
                        return;
                    }
                    $permanent    = $details['years'] == -5 ? true : false;
                    $authority    = null;
                    $active       = null;
                    $language     = null;
                    $ends_at      = Carbon::parse($details['date'])->addYears($permanent ? 100 : $details['years'])->format('Y-m-d');
                    $approved_at  = null;
                    $published_at = null;

                    if(!empty($details['authority'])) {
                        $authority = AuthoritySite::find($details['authority']);
                        $language  = (!empty($authority->wordpress)) ? @$authority->wordpresses->language : @$authority->sites->language;

                        if(!empty($authority->wordpress)) {
                            if(@$authority->wordpresses->automatic == 1) {
                                $active      = 1;
                                $approved_at = Carbon::now()->format('Y-m-d H:i:s');
                                $active = null; //When the command publish:links runs, search for links who has active field on null
                            } else {
                                $active = 2;
                            }
                        } else {
                            if(@$authority->sites->automatic == 1) {
                                $active       = 1;
                                $approved_at  = Carbon::now()->format('Y-m-d H:i:s');
                                $published_at = Carbon::now()->format('Y-m-d H:i:s');
                            } else {
                                $active = 2;
                            }
                        }
                    }

                    $links[] = array('order'         => $order,
                                    'url'            => $details['url'],
                                    'anchor'         => $details['anchor'],
                                    'follow'         => get_bool(get_bool_follow($details['follow'])),
                                    'blank'          => get_bool($details['blank']),
                                    'alt'            => $details['title'],
                                    'description'    => array_key_exists('description', $details) ? $details['description'] : '',
                                    'active'         => $active,
                                    'authority_site' => $details['authority'],
                                    'language'       => $language,
                                    'category'       => $details['category'],
                                    'ends_at'        => $ends_at,
                                    'visible_at'     => $details['date'],
                                    'approved_at'    => $approved_at,
                                    'published_at'   => $published_at,
                                    'permanent'      => $permanent ? 1 : 0,
                                    'client'         => $user->id);
                }
            }

            if($item == 'blog content link') {
                if(count($details) == 1) {
                    $details = $details[0];
                }

                if(!empty($details)) {
                    $permanent    = $details['years'] == -5 ? true : false;
                    $authority    = null;
                    $active       = null;
                    $language     = null;
                    $ends_at      = Carbon::parse($details['date'])->addYears($permanent ? 100 : $details['years'])->format('Y-m-d');
                    $approved_at  = null;
                    $published_at = null;

                    if(!empty($details['authority'])) {
                        $authority = AuthoritySite::find($details['authority']);
                        $language  = (!empty($authority->wordpress)) ? @$authority->wordpresses->language : @$authority->sites->language;

                        if(!empty($authority->wordpress)) {
                            if(@$authority->wordpresses->automatic == 1) {
                                $active      = 1;
                                $approved_at = Carbon::now()->format('Y-m-d H:i:s');
                            } else {
                                $active = 2;
                            }
                        } else {
                            if(@$authority->sites->automatic == 1) {
                                $active       = 1;
                                $approved_at  = Carbon::now()->format('Y-m-d H:i:s');
                                $published_at = Carbon::now()->format('Y-m-d H:i:s');
                            } else {
                                $active = 2;
                            }
                        }
                    }

                    $links[] = array('order'         => $order,
                                    'url'            => $details['url'],
                                    'anchor'         => $details['anchor'],
                                    'follow'         => get_bool(get_bool_follow($details['follow'])),
                                    'blank'          => get_bool($details['blank']),
                                    'alt'            => $details['title'],
                                    'active'         => $active,
                                    'authority_site' => $details['authority'],
                                    'language'       => $language,
                                    'category'       => null,
                                    'section'        => $details['section'],
                                    'ends_at'        => $ends_at,
                                    'visible_at'     => $details['date'],
                                    'approved_at'    => $approved_at,
                                    'published_at'   => $published_at,
                                    'permanent'      => $permanent ? 1 : 0,
                                    'client'         => $user->id);
                }
            }

            if($item == 'startpage article' or $item == 'blog article') {
                if(count($details) == 1) {
                    $details = $details[0];
                }

                if(!empty($details)) {
                    if(array_key_exists('renewal', $details)){
                        return;
                    }
                    $permanent    = $details['years'] == -5 ? true : false;
                    $authority    = null;
                    $active       = null;
                    $language     = null;
                    $ends_at      = Carbon::parse($details['date'])->addYears($permanent ? 100 : $details['years'])->format('Y-m-d');
                    $approved_at  = null;
                    $published_at = null;

                    if(!empty($details['authority'])) {
                        $authority = AuthoritySite::find($details['authority']);
                        $language  = (!empty($authority->wordpress)) ? @$authority->wordpresses->language : @$authority->sites->language;

                        if(!empty($authority->wordpress)) {
                            if(@$authority->wordpresses->automatic == 1) {
                                $approved_at = Carbon::now()->format('Y-m-d H:i:s');
                            } else {
                                $active = 2;
                            }
                        } else {
                            if(@$authority->sites->automatic == 1) {
                                $approved_at  = Carbon::now()->format('Y-m-d H:i:s');
                                $published_at = Carbon::now()->format('Y-m-d H:i:s');
                            } else {
                                $active = 2;
                            }
                        }
                    }

                    if(intval($requested) == 1) {
                        $articles[] = array('customer'         => $user->id,
                                            'writer'           => null,
                                            'article'          => null,
                                            'site'             => null,
                                            'paid'             => 1,
                                            'status'           => 'pending',
                                            'order'            => $order,
                                            'url'              => null,
                                            'title'            => $details['title'],
                                            'description'      => $details['content'],
                                            'meta_title'       => null,
                                            'meta_description' => null,
                                            'keywords'         => null,
                                            'image'            => null,
                                            'visible_at'       => $details['date'],
                                            'expired_at'       => $ends_at,
                                            'authority_site'   => $details['authority'],
                                            'language'         => null,
                                            'category'         => null,
                                            'suggested_url'    => @$details['url'],
                                            'suggested_anchor' => @$details['anchor'],
                                            'permanent'      => $permanent ? 1 : 0,
                                            'approved_at'      => null);
                    } else {
                        $articles[] = array('order'            => $order,
                                            'url'              => $details['url'],
                                            'title'            => $details['title'],
                                            'description'      => $details['content'],
                                            'meta_title'       => null,
                                            'meta_description' => null,
                                            'keywords'         => null,
                                            'image'            => $details['image'],
                                            'active'           => $active,
                                            'expired_at'       => $ends_at,
                                            'visible_at'       => $details['date'],
                                            'authority_site'   => $details['authority'],
                                            'language'         => $language,
                                            'category'         => $details['category'],
                                            'approved_at'      => $approved_at,
                                            'published_at'     => $published_at,
                                            'permanent'      => $permanent ? 1 : 0,
                                            'client'           => $user->id);
                    }
                }
            }

            if(!empty($links)) {
                DB::table('links')->insert($links);
            }

            if(!empty($articles)) {
                if(intval($requested) == 1) {
                    DB::table('articles_requested')->insert($articles);
                } else {
                    DB::table('articles')->insert($articles);
                }
            }
        }
    }

    private function renew_links($id, $details) {
        $json    = json_decode($details, true);
        $years   = $json[0]['years'] ?? 1;
        $link    = Link::find($id);
        $ends_at = Carbon::parse($link->ends_at)->addYears($years)->format('Y-m-d');

        if(!empty($link)) {
            $link->active       = 1;
            $link->ends_at      = $ends_at;
            $link->published_at = Carbon::now();
            $link->save();
        }
    }

    private function renew_articles($id, $details) {
        $json    = json_decode($details, true);
        $years   = $json[0]['years'] ?? 1;
        $article = Article::find($id);
        $expired_at = Carbon::parse($article->expired_at)->addYears($years)->format('Y-m-d');

        if(!empty($article)) {
            $article->active       = 1;
            $article->expired_at   = $expired_at;
            $article->published_at = Carbon::now();
            $article->save();
        }
    }

    private function send_email($order) {
        $template = MailingText::template('Payments', App::getLocale());

        if(!empty($template)) {
            $user    = User::find($order->user);
            $subject = replace_variables($template->name, $user->id);
            $content = replace_variables($template->description, $user->id, array('order' => $order->order));

            $name    = $user->name . ' ' . $user->lastname;
            $email   = $user->email;

            Mail::send('mails.template', ['content' => $content, 'align' => 'center', 'icon' => 'icon'], function ($mail) use ($email, $name, $subject) {
                $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                $mail->to($email, $name)->subject($subject);
            });
        }
    }

    private function invoice_number($order) {
        $total   = Order::paid();
        $number  = intval($total) + 1;
        $invoice = date('Y') . '-' . str_pad($number, 10, '0', STR_PAD_LEFT);
        Order::set_invoice($order, $invoice);
    }

}
