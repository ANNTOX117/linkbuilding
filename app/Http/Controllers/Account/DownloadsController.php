<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\General;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SettingUser;
use Theme;

class DownloadsController extends Controller {

    public function invoice(Request $request) {

        $settings = SettingUser::invoice_details();
        $order = Order::my_order($request->name);

        if(!empty($order)) {
            $code    = $order[0]->invoice;
            $logo    = Theme::get().'/images/logo.jpg';
            $payment = $order[0]->payment;
            $date    = $order[0]->created_at;
            $header  = General::invoice_header();
            $user    = auth()->user();
            $view    = view('pdf.invoice', compact('code', 'logo', 'payment', 'date', 'header', 'user', 'order', 'settings'));
            $view    = $view->render();
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($view);
            $mpdf->Output($code.'.pdf', 'I');
        } else {
            abort(404);
        }
    }

}
