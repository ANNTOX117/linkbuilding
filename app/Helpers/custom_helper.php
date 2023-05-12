<?php
use Srmklive\PayPal\Services\PayPal as PayPalClient;

if(!function_exists('array_boolean')) {
    function array_boolean($array) {
        $array = str_split($array);
        foreach($array as $i => $val) {
            $array[$i] = ($val == '1');
        }
        return $array;
    }
}

if(!function_exists('slugify')) {
    function slugify($text){
        $text = mb_strtolower($text, 'UTF-8');
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $pattern = array (
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',
            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',
            '/&acirc;/' => 'a',
            '/&ecirc;/' => 'e',
            '/&icirc;/' => 'i',
            '/&ocirc;/' => 'o',
            '/&ucirc;/' => 'u',
            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
            '/&auml;/' => 'a',
            '/&euml;/' => 'e',
            '/&iuml;/' => 'i',
            '/&ouml;/' => 'o',
            '/&uuml;/' => 'u',
            '/&aring;/' => 'a',
            '/&ntilde;/' => 'n',
            '/&ldquo/' => '',
            '/&rdquo/' => '',
            '/&lsquo/' => '',
            '/&rsquo/' => '',
            '/&iquest/' => '',
            '/&iexcl/' => '',
            '/&apos;/' => '',
            '/&amp;/' => '',
            '/&#039;/' => ''
        );
        $text = preg_replace(array_keys($pattern), array_values($pattern), $text);
        $text = preg_replace('/[¿!¡;,:\.\?*=+#@%()"]/', '', trim($text));
        $text = str_replace(' - ', '-', $text);
        $text = str_replace(' ', '-', $text);
        $text = str_replace ( '/', '-', $text ); 
        $text = str_replace('--', '-', $text);
        return strtolower($text);
    }
}

if(!function_exists('substring_text')) {
    function substring_text(string $text,int $size):string {
        return substr($text, 0, $size);
    }
}

if(!function_exists('array_boolean_revert')) {
    function array_boolean_revert($array) {
        foreach($array as $i => $val) {
            $array[$i] = ($val) ? '1' : '0';
        }
        return implode('', $array);
    }
}

if(!function_exists('bad_words')){
    function bad_words($string, $filters){
        $bad = App\Models\BadWordFilter::all_items();
        $bad_array = [];
        foreach ($bad as $key => $value) {
            $bad_array[] = $value->badword;
        }
        if ($filters == 'include') {
            return (preg_match('/'. implode("|", $bad_array).'/', $string)) ? true : false ;
        }
        if ($filters == 'string') {
            $remplace   = " ****** ";
            $newPhrase = str_replace($bad_array, $remplace, $string);
            return $newPhrase;
        }
    }
}

if(!function_exists('clean_db')){
    function clean_db($value, $allow = false){
        if($allow == 'zero') {
            return ($value === null or trim($value) === '' or !isset($value) or trim($value) === '-') ? null : trim($value);
        }
        return ($value === null or trim($value) === '' or !isset($value) or trim($value) === '-' or trim($value) === '0' or trim($value) === '0.00') ? null : trim($value);
    }
}

if(!function_exists('clean_html')) {
    function clean_html($value) {
        return strip_tags($value);
    }
}

if(!function_exists('coins_on_register')){
    function coins_on_register() {
        return App\Models\General::coins_on_register();
    }
}

if(!function_exists('count_array_values_not_null')) {
    function count_array_values_not_null($array) {
        return count(array_filter($array, function($x) { return !empty($x); }));
    }
}

if(!function_exists('count_iframe')) {
    function count_iframe($value) {
        return preg_match_all('<[ \n\t]*/[ \n\t]*iframe[ \n\t]*>', $value, $matches);
    }
}

if(!function_exists('count_links')) {
    function count_links($value) {
        return preg_match_all('<[ \n\t]*/[ \n\t]*a[ \n\t]*>', $value, $matches);
    }
}

if(!function_exists('count_script')) {
    function count_script($value) {
        return preg_match_all('<[ \n\t]*/[ \n\t]*script[ \n\t]*>', $value, $matches);
    }
}

if(!function_exists('count_words')) {
    function count_words($value) {
        $text = strip_tags($value);
        return count(str_word_count($text, 1));
    }
}

if(!function_exists('csv_header_site')) {
    function csv_header_site($array){
        return (is_array($array) and $array[0] == 'URL' and $array[1] == 'Subnet' and $array[2] == 'PA' and $array[3] == 'DA' and $array[4] == 'TF' and $array[5] == 'CF' and $array[6] == 'DRE' and $array[7] == 'Backlinks' and $array[8] == 'Refering domains' and $array[9] == 'Price' and $array[10] == 'Special price');
    }
}

if(!function_exists('csv_header_category')) {
    function csv_header_category($array){
        return (is_array($array) and $array[0] == 'Category' and $array[1] == 'Language');
    }
}

if(!function_exists('csv_header_validate')) {
    function csv_header_validate($array){
        return (is_array($array) and $array[0] == 'ID' and $array[1] == 'TO' and $array[2] == 'FROM');
    }
}

if(!function_exists('csv_header_bulk')) {
    function csv_header_bulk($array){      
        return (is_array($array) and 
                $array[0] == 'Link' and 
                $array[1] == 'Anchor' and
                $array[2] == 'Follow' and
                $array[3] == 'Title' and
                $array[4] == 'Target'
            );
    }
}

if(!function_exists('currency')){
    function currency(){
        return App\Models\General::currency();
    }
}

if(!function_exists('date_start_with_year')) {
    function date_start_with_year($value) {
        return strlen(explode('-', $value)[0]) === 4;
    }
}

if(!function_exists('datepicker_date')) {
    function datepicker_date($value){
        if(!empty($value)) {
            return date( 'Y/m/d', strtotime($value));
        }
        return null;
    }
}

if(!function_exists('do_decrypt')){
    function do_decrypt($value){
        return (!empty($value)) ? str_replace(env('APP_SALTKEY'), '', \Illuminate\Support\Facades\Crypt::decrypt($value)) : null;
    }
}

if(!function_exists('do_encrypt')){
    function do_encrypt($value){
        return (!empty($value)) ? \Illuminate\Support\Facades\Crypt::encrypt(env('APP_SALTKEY') . $value) : null;
    }
}

if(!function_exists('domain')){
    function domain(){
        if(!empty(@$_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $domain = @$_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            if(!empty(@$_SERVER['HTTP_HOST'])) {
                $domain = $_SERVER['HTTP_HOST'];
            } else {
                return '';
            }
        }

        $domain = str_ireplace('www.', '', $domain);

        if(substr_count($domain, '.') > 1) {
            $domain = explode('.', $domain);
            return $domain[count($domain)-2] . '.' . end($domain);
        }

        return $domain;
    }
}

if(!function_exists('extract_domain')) {
    function extract_domain($value){
        return (preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $value, $matches)) ? $matches['domain'] : $value;
    }
}

if(!function_exists('extract_subdomains')) {
    function extract_subdomains($value){
        $subdomains = $value;
        $domain     = extract_domain($subdomains);
        $subdomains = rtrim(strstr($subdomains, $domain, true), '.');

        return $subdomains;
    }
}

if(!function_exists('fix_date')) {
    function fix_date($value) {
        if(!empty($value)) {
            $a = explode('-', $value);
            return $a[2].'-'.$a[1].'-'.$a[0];
        }
        return false;
    }
}

if(!function_exists('fix_date_on_buy')) {
    function fix_date_on_buy($value) {
        if(!empty($value)) {
            $a = explode('/', $value);
            return $a[2].'-'.$a[0].'-'.$a[1];
        }
        return false;
    }
}

if(!function_exists('fix_date_on_request')) {
    function fix_date_on_request($value) {
        if(!empty($value)) {
            $a = explode('/', $value);
            return $a[2].'-'.$a[1].'-'.$a[0];
        }
        return false;
    }
}

// if(!function_exists('forbidden_section')){
//     function forbidden_section($section) {
//         if($section === 'dashboard' and is_editor() and !editor_allow($section)) {
//             session(['site' => explode(',', auth()->user()->role_editors->sites)[0]]);
//             $redirect = explode(',', auth()->user()->role_editors->sections)[0];
//             return redirect('/' . $redirect);
//         }

//         if(!is_admin() and !is_manager() and !editor_allow($section)) {
//             abort(404);
//         }
//     }
// }

if(!function_exists('get_blank')) {
    function get_blank($value) {
        return ($value == '_blank') ? 1 : null;
    }
}

if(!function_exists('get_bool')) {
    function get_bool($value) {
        return !empty($value) ? 1 : null;
    }
}

if(!function_exists('get_bool_follow')) {
    function get_bool_follow($value) {
        return $value == 'follow';
    }
}

if(!function_exists('get_code')) {
    function get_code($length = 6) {
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('get_currency')) {
    function get_currency() {
        $currency = \App\Models\General::currency();

        if($currency == '€') {
            return 'EUR';
        }
        if($currency == '$') {
            return 'USD';
        }
        if($currency == '£') {
            return 'GBP';
        }
        return 'EUR';
    }
}

if(!function_exists('get_date')) {
    function get_date($value){
        if(!empty($value)) {
            $value = explode('-', $value);
            switch($value[1]) {
                case '01': $month = trans('January'); break;
                case '02': $month = trans('February'); break;
                case '03': $month = trans('March'); break;
                case '04': $month = trans('April'); break;
                case '05': $month = trans('May'); break;
                case '06': $month = trans('June'); break;
                case '07': $month = trans('July'); break;
                case '08': $month = trans('August'); break;
                case '09': $month = trans('September'); break;
                case '10': $month = trans('October'); break;
                case '11': $month = trans('November'); break;
                case '12': $month = trans('December'); break;
                default: $month = null; break;
            }

            if(strpos($value[2], ':') !== false) {
                $value[2] = explode(' ', $value[2])[0];
            }

            return ltrim($value[2], '0') . ' ' . $month . ' ' . $value[0];
        } else {
            return false;
        }
    }
}

if(!function_exists('get_daughter')) {
    function get_daughter($value, $category) {
        $value  = explode('://', $value);
        $domain = $value[0] . '://' . $category . '.' . $value[1];
        return str_ireplace('www.', '', $domain);
    }
}

/*
 * Priority for discounts:
 *
 * 1.- Discount by year
 * 2.- Discount by volume
 * 3.- Discount by user
 * 4.- Discount by group
 * 5.- Discount by product
 * 6.- Discount by minimum price
 */
if(!function_exists('get_discount')) {
    function get_discount($price, $user = null) {
        if(empty($user)) {
            $user = \Illuminate\Support\Facades\Auth::user();
        } else {
            $user = \App\Models\User::find($user);
        }

        $cart = \App\Models\Cart::items();

        $quantity   = $cart->count();
        $response   = array();
        $by_user    = array('type' => 'Discount by user', 'percentage' => '0%');
        $by_group   = array('type' => 'Discount by group', 'percentage' => '0%');
        $by_product = array('type' => 'Discount by product', 'percentage' => '0%');
        $by_price   = array('type' => 'Discount by price', 'percentage' => '0%');
        $by_year    = array('type' => 'Discount by year', 'percentage' => '0%');
        $by_volume  = array('type' => 'Discount by volume', 'percentage' => '0%');

        // Discount by user
        $discount = \App\Models\RuleDiscount::check_rule_for_user($user->id);
        if(!empty($discount)) {
            if(floatval($price) >= floatval($discount->price)) {
                $subtotal = ((floatval($price) / 100) * floatval($discount->percentage));
                $by_user  = array('type' => 'Discount by user', 'percentage' => integer_or_float($discount->percentage) . '%');
            }
        }

        // Discount by group
        $discount = \App\Models\RuleDiscount::check_rule_for_groups($user->id);
        if(!empty($discount)) {
            if(floatval($price) >= floatval($discount->price)) {
                $subtotal = ((floatval($price) / 100) * floatval($discount->percentage));
                $by_group = array('type' => 'Discount by group', 'percentage' => integer_or_float($discount->percentage) . '%');
            }
        }

        // Discount by product
        foreach($cart as $c) {
            $discount = \App\Models\RuleDiscount::check_rule_for_product($c->item);

            if(!empty($discount)) {
                if(floatval($price) >= floatval($discount->price)) {
                    $subtotal   = ((floatval($price) / 100) * floatval($discount->percentage));
                    $by_product = array('type' => 'Discount by product', 'percentage' => integer_or_float($discount->percentage) . '%');
                    break;
                }
            }
        }

        // Discount by minimum price
        if(floatval($price) > 0) {
            $discount = \App\Models\DiscountPrice::by_price($price);
            if(!empty($discount)) {
                $subtotal = ((floatval($price) / 100) * floatval($discount->percentage));
                $by_price = array('type' => 'Discount by price', 'percentage' => integer_or_float($discount->percentage) . '%');
            }
        }

        // Discount by year
        if(!empty($cart)) {
            $years = 1;
            foreach($cart as $i => $item) {
                $json = json_decode($item->details, true);
                if(!empty($json)) {
                    foreach($json as $j => $year) {
                        if($j == 'years') {
                            if(intval($year) > intval($years)) {
                                $years = $year;
                            }
                        }
                    }
                }
            }

            if($years > 0) {
                $discount = \App\Models\DiscountDefault::by_years($years);
                if(!empty($discount)) {
                    $subtotal = ((floatval($price) / 100) * floatval($discount->percentage));
                    $by_year  = array('type' => 'Discount by year', 'percentage' => integer_or_float($discount->percentage) . '%');
                }
            }
        }

        // Discount by volume
        $default = \App\Models\Discount::per_volume($quantity);
        if(!empty($default)) {
            $subtotal  = ((floatval($price) / 100) * floatval($default->percentage));
            $by_volume = array('type' => 'Discount by volume', 'percentage' => integer_or_float($default->percentage) . '%');
        }

        $discounts    = array('by_user' => floatval($by_user['percentage']), 'by_group' => floatval($by_group['percentage']), 'by_product' => floatval($by_product['percentage']), 'by_price' => floatval($by_price['percentage']), 'by_volume' => floatval($by_volume['percentage']));
        $max_discount = max($discounts);

        // If exists, include the discount per year
        if(floatval($by_year['percentage']) > 0) {
            $response[] = $by_year;
        }

        // Return the maximum discount
        if(floatval($max_discount) > 0) {
            $max_array  = array_search($max_discount, $discounts);
            $response[] = $$max_array;
        }

        // Recalculate subtotals
        if(count($response) > 0) {
            foreach($response as $i => $item) {
                $response[$i]['discount'] = ((floatval($price) / 100) * floatval($item['percentage']));
                $response[$i]['subtotal'] = floatval($price) - floatval($response[$i]['discount']);
                $price = $response[$i]['subtotal'];
            }
        }

        return $response;
    }
}

if(!function_exists('get_domain')) {
    function get_domain($value) {
        $domain = str_ireplace('www.', '', parse_url($value, PHP_URL_HOST));
        return explode('.', $domain)[0];
    }
}

if(!function_exists('get_excerpt')) {
    function get_excerpt($value, $limit = 140) {
        return strlen(clean_html($value)) > $limit ? trim(strip_tags(html_entity_decode(htmlspecialchars_decode(substr(clean_html($value), 0, $limit).'…'), ENT_QUOTES, 'UTF-8'))) : clean_html($value);
    }
}

if(!function_exists('get_follow')) {
    function get_follow($value) {
        return !empty($value) ? 'follow' : 'nofollow';
    }
}

if(!function_exists('get_follow_or_not')) {
    function get_follow_or_not($value) {
        return $value == 1 ? 'follow' : 'nofollow';
    }
}

if(!function_exists('get_image')) {
    function get_image($value) {
        return strtok($value, '?');
    }
}

if(!function_exists('get_invoice')) {
    function get_invoice($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('get_money')) {
    function get_money($value) {
        return number_format(round_price($value), 2, '.', '');
    }
}

if(!function_exists('get_password')) {
    function get_password($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('get_paypal_credentials')) {
    function get_paypal_credentials() {
        $client_id_paypal = \App\Models\General::where('key', 'PAYPAL_SANDBOX_CLIENT_ID')->first();
        $client_secret_paypal = \App\Models\General::where('key', 'PAYPAL_SANDBOX_CLIENT_SECRET')->first();

        config(['paypal.sandbox.client_id' => $client_id_paypal->value]);
        config(['paypal.sandbox.client_secret' => $client_secret_paypal->value]);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        
        return $provider;
    }
}

if(!function_exists('site_theme')) {
    function site_theme(){
        return (!empty(session('domain'))) ? @session('domain')->theme : 'demo';
    }
}

if(!function_exists('get_price')) {
    function get_price($value){
        return number_format($value, 2, '.', ',');
    }
}

if(!function_exists('replace_expire')) {
    function replace_expire($item , $mailing_text_id){

        if (!empty($item ) && !empty($mailing_text_id)) {
            $mail_text = \App\Models\MailingText::find($mailing_text_id)->description;

            if (strpos($mail_text, '[name]') !== false) {

                $mail_text = str_replace('[name]', $item[0]['name']." ".$item[0]['lastname'] , $mail_text);
            }

            if (strpos($mail_text, '[table]') !== false) {

                $for_expired = '';

                $for_expired .= "<table style='width:100%; text-align: center;'><thead><tr bgcolor='#0063fb'><th style='color: #fff;'>".trans('Expiration Date')."</th><th style='color: #fff;'>".trans('Homepage')."</th><th style='color: #fff;'>".trans('Anchor')."</th><th style='color: #fff;'>".trans('Renew')."</th></tr></thead><tbody style='background-color:white;'>";

                foreach ($item as $key) {

                    $for_expired  .= "<tr style='border-bottom: 1px solid #e1dada;'><td style='text-align: center; color : black;'>".date('d-m-Y', strtotime($key['ends_at']))."</td><td style='text-align: center; color : black;'>".$key['url']."</td><td style='text-align: center; color : black;'>".$key['anchor']."</td><td style='text-align: center;'><a href='". route('customer_renewal', ['p' => base64_encode($key['link_id'])]) ."' style='color: #fff;background-color: #0063fb;border-color: #0063fb; display: inline-block; font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;padding: .375rem .75rem;font-size: 1rem;line-height: 1.5;border-radius: .25rem;margin: 10px 0;'>". trans('Renew immediately') ."</a></td><tr>";
                }
                $for_expired .= "</tbody></table>";

                $mail_text = str_replace('[table]', $for_expired , $mail_text);

                $month_ago = \App\Models\Link::my_liks_expired_mail_by_id($item[0]['id']);

                if (!empty($month_ago)) {

                    $expired = '';

                    $expired .= "<table style='width:100%'><thead><tr bgcolor='#0063fb'><th style='color: #fff;'>".trans('Expiration Date')."</th><th style='color: #fff;'>".trans('Homepage')."</th><th style='color: #fff;'>".trans('Anchor')."</th><th style='color: #fff;'>".trans('Renew')."</th></tr></thead><tbody style='background-color:white'>";

                    foreach ($month_ago as $key) {

                        $expired  .= "<tr style='border-bottom: 1px solid #e1dada;'><td style='text-align: center; color : black;'>".date('d-m-Y', strtotime($key['ends_at']))."</td><td style='text-align: center; color : black;'>".parse_url($key['url'], PHP_URL_HOST)."</td><td style='text-align: center; color : black;'>".$key['anchor']."</td><td style='text-align: center;'><a href='". route('customer_renewal', ['p' => base64_encode($key['link_id'])]) ."' style='color: #fff;background-color: #0063fb;border-color: #0063fb; display: inline-block; font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;padding: .375rem .75rem;font-size: 1rem;line-height: 1.5;border-radius: .25rem;margin: 10px 0;'>". trans('Renew immediately') ."</a></td><tr>";
                    }
                    $expired .= "</tbody></table>";
                }

                $mail_text = str_replace('[expired]', $expired , $mail_text);
            }

            if(strpos($mail_text, '[expired]') !== false) {
                $links = \App\Models\Link::expired_link($item[0]['id']);

                if(!empty($links)) {
                    $expired  = '';
                    $expired .= "<table style='width:100%'><thead><tr bgcolor='#0063fb'><th style='color: #fff;'>".trans('Expiration Date')."</th><th style='color: #fff;'>".trans('Homepage')."</th><th style='color: #fff;'>".trans('Anchor')."</th><th style='color: #fff;'>".trans('Renew')."</th></tr></thead><tbody style='background-color:white'>";

                    foreach ($links as $key) {
                        $expired  .= "<tr style='border-bottom: 1px solid #e1dada;'><td style='color : black;'>".date('d-m-Y', strtotime($key['ends_at']))."</td><td style='color : black;'>".parse_url($key['url'], PHP_URL_HOST)."</td><td style='color : black;'>".$key['anchor']."</td><td><a href='". route('customer_renewal', ['p' => base64_encode($key['link_id'])]) ."' style='color: #fff;background-color: #0063fb;border-color: #0063fb; display: inline-block; font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;padding: .375rem .75rem;font-size: 1rem;line-height: 1.5;border-radius: .25rem;margin: 10px 0;'>". trans('Renew immediately') ."</a></td><tr>";
                    }
                    $expired .= "</tbody></table>";
                }

                $mail_text = str_replace('[expired]', $expired , $mail_text);
            }
        }
        return $mail_text;
    }
}

if(!function_exists('get_protocol')) {
    function get_protocol() {
        return $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
    }
}

if(!function_exists('get_root')) {
    function get_root() {
        return env('APP_URL');
    }
}

if(!function_exists('get_rule')) {
    function get_rule($rule) {
        $text = '';

        if(!empty($rule->user)) {
            $text .= trans('For user') .' '. $rule->users->name .' '. $rule->users->lastname;
        }
        if(!empty($rule->group)) {
            $text .= trans('For group') .' '. $rule->groups->name;
        }
        if(!empty($rule->product)) {
            if($rule->product == 'startingpage') {
                $text .= trans('For product') .' '. trans('Starting page');
            }
            if($rule->product == 'homepagelink') {
                $text .= trans('For product') .' '. trans('Homepage');
            }
            if($rule->product == 'childstartingpage') {
                $text .= trans('For product') .' '. trans('Daughter page');
            }
            if($rule->product == 'intext') {
                $text .= trans('For product') .' '. trans('Intext');
            }
            if($rule->product == 'blogs') {
                $text .= trans('For product') .' '. trans('Blogs');
            }
            if($rule->product == 'packages') {
                $text .= trans('For packages');
            }
        }

        return $text;
    }
}

if(!function_exists('get_slug')) {
    function get_slug($value) {
        $value = mb_strtolower($value, 'UTF-8');
        $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
        $pattern = array (
            '/&agrave;/' => 'a',
            '/&egrave;/' => 'e',
            '/&igrave;/' => 'i',
            '/&ograve;/' => 'o',
            '/&ugrave;/' => 'u',
            '/&aacute;/' => 'a',
            '/&eacute;/' => 'e',
            '/&iacute;/' => 'i',
            '/&oacute;/' => 'o',
            '/&uacute;/' => 'u',
            '/&acirc;/'  => 'a',
            '/&ecirc;/'  => 'e',
            '/&icirc;/'  => 'i',
            '/&ocirc;/'  => 'o',
            '/&ucirc;/'  => 'u',
            '/&atilde;/' => 'a',
            '/&etilde;/' => 'e',
            '/&itilde;/' => 'i',
            '/&otilde;/' => 'o',
            '/&utilde;/' => 'u',
            '/&auml;/'   => 'a',
            '/&euml;/'   => 'e',
            '/&iuml;/'   => 'i',
            '/&ouml;/'   => 'o',
            '/&uuml;/'   => 'u',
            '/&auml;/'   => 'a',
            '/&euml;/'   => 'e',
            '/&iuml;/'   => 'i',
            '/&ouml;/'   => 'o',
            '/&uuml;/'   => 'u',
            '/&aring;/'  => 'a',
            '/&ntilde;/' => 'n',
            '/&ldquo/'   => '',
            '/&rdquo/'   => '',
            '/&lsquo/'   => '',
            '/&rsquo/'   => '',
            '/&iquest/'  => '',
            '/&iexcl/'   => '',
            '/&apos;/'   => '',
            '/&#039;/'   => '',
            '/&quot;/'   => ''
        );
        $value = preg_replace(array_keys($pattern), array_values($pattern), $value);
        $value = trim(strip_tags(html_entity_decode(htmlspecialchars_decode($value), ENT_QUOTES, 'UTF-8')));
        $value = preg_replace("/[^a-zA-Z0-9 ]+/", '', trim($value));
        $value = str_replace('  ', ' ', $value);
        $value = str_replace(' - ', '-', $value);
        $value = str_replace(' ', '-', $value);
        return strtolower($value);
    }
}

if(!function_exists('get_string_bulk')) {
    //function get_string_bulk($array) {
    function get_string_bulk($url, $title, $anchor, $follow, $blank) {
        $url    = (!empty($url) AND $url != '') ? 'href="'.prefix_http($url).'"' : '';
        $title  = (!empty($title)  AND $title != '' ) ? 'title="'.$title.'"' : '';
        $anchor = (!empty($anchor)) ? $anchor : '';
        $follow = ($follow == 2) ? 'rel="nofollow"' : 'rel="follow"';
        $blank  = ($blank == '_blank') ? 'target="_blank"' : '';        
        return "<a ".$url." ".$follow." ".$title." ".$blank.">".$anchor."</a>";
    }
}

if(!function_exists('get_subdomain')) {
    function get_subdomain($url, $subdomain) {
        strstr($url,'www.') ?
            $url_parts = explode('://www.', $url):
            $url_parts = explode('://', $url);

        return $url_parts[0].'://'.$subdomain.'.'.$url_parts[1];
    }
}

if(!function_exists('get_vat')) {
    function get_vat() {
        if(Auth::check()) {
            return !empty(\Illuminate\Support\Facades\Auth::user()->country) ? \App\Models\Tax::by_country(\Illuminate\Support\Facades\Auth::user()->country) : 0;
        }
        return 0;
    }
}

if(!function_exists('get_wordpress_or_site')) {
    function get_wordpress_or_site($value){
        return (!empty($value) and !empty($value->wordpress)) ? 'wordpress' : 'site';
    }
}

if(!function_exists('getHttpResponseCode_using_curl')) {
    function getHttpResponseCode_using_curl($url, $followredirects = true) {
        if(!$url || !is_string($url)){
            return false;
        }
        $ch = @curl_init($url);
        if($ch === false) {
            return false;
        }
        @curl_setopt($ch, CURLOPT_HEADER,true);
        @curl_setopt($ch, CURLOPT_NOBODY,true);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        if($followredirects){
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            @curl_setopt($ch, CURLOPT_MAXREDIRS,10);
        }else{
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);
        }
        @curl_exec($ch);
        if(@curl_errno($ch)){
            @curl_close($ch);
            return false;
        }
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        @curl_close($ch);
        return $code;
    }
}

if(!function_exists('getHttpResponseCode_using_getheaders')) {
    function getHttpResponseCode_using_getheaders($url, $followredirects = true) {
        if(!$url || !is_string($url)){
            return false;
        }
        $headers = @get_headers($url);
        if($headers && is_array($headers)){
            if($followredirects){
                $headers = array_reverse($headers);
            }
            foreach($headers as $hline){
                if(preg_match('/^HTTP\/\S+\s+([1-9][0-9][0-9])\s+.*/', $hline, $matches) ){
                    $code = $matches[1];
                    return $code;
                }
            }
            return false;
        }
        return false;
    }
}


if(!function_exists('initials')) {
    function initials($name) {
        $words    = explode(' ', strtoupper(transliterateString($name)));
        $initials = '';
        foreach($words as $word) {
            $initials .= $word[0];
        }
        return (count($words) > 2) ? substr($initials, 0, 2) : $initials;
    }
}

if(!function_exists('integer_or_float')) {
    function integer_or_float($value) {
        return (strpos($value, '.00') !== false) ? intval($value) : floatval($value);
    }
}

if(!function_exists('is_subdomain')) {
    function is_subdomain($domain){
        $url = extract_subdomains($domain);
        $url = preg_replace("(^https?://)", "", $url);
        if($url == 'www'){ $url = ''; }
        return (!empty($url)) ? 'childstartingpage' : 'startingpage';
    }
}

if(!function_exists('is_not_valid_domain')) {
    function is_not_valid_domain($value){
        return !filter_var($value, FILTER_VALIDATE_IP);
    }
}

if(!function_exists('is_valid_url')) {
    function is_valid_url($value){
        if(!$value || !is_string($value)){
            return false;
        }
        if(!preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $value)){
            return false;
        }
        if(getHttpResponseCode_using_curl($value) != 200){
            return false;
        }
        return true;
    }
}

if(!function_exists('is_builder')) {
    function is_builder() {
        return \App\Models\Site::is_admin_section();
    }
}

if(!function_exists('is_wordpress_or_site')) {
    function is_wordpress_or_site($value){
        $site = \App\Models\Site::is_normal_site($value);
        if(!empty($site)) {
            return $site->id;
        }
        $wordpress = \App\Models\Wordpress::is_wordpress_site($value);
        if(!empty($wordpress)) {
            return $wordpress->id;
        }
        return null;
    }
}

if(!function_exists('mollie_key')){
    function mollie_key(){
        return App\Models\General::mollie_key();
    }
}

if(!function_exists('mysql_null')){
    function mysql_null($value){
        return ($value === null or trim($value) === '' or !isset($value) or trim($value) === '-' or trim($value) === '0' or trim($value) === '0.00') ? null : trim($value);
    }
}

if(!function_exists('mysql_null_allow_zero')){
    function mysql_null_allow_zero($value){
        return ($value === null or trim($value) === '' or !isset($value) or trim($value) === '-') ? null : trim($value);
    }
}

if(!function_exists('permission')){
    function permission($name, $action) {
        if(Illuminate\Support\Facades\Auth::check() && Illuminate\Support\Facades\Auth::user()->roles->name == 'admin'){ return true; }
        if(in_array($name, array('dashboard', 'languages', 'categories', 'sites', 'wordpress', 'authorities', 'articles', 'packages', 'links', 'approvals', 'users', 'mailing', 'taxes', 'texts', 'pages', 'payments', 'discounts', 'general','profiles','templates','seo-pages'))){
            if(in_array($action, array('create', 'read', 'update', 'delete'))) {
                if(!empty(Illuminate\Support\Facades\Auth::user()->roles->permissions->$name)) {
                    if($action == 'create'){ if(intval(Illuminate\Support\Facades\Auth::user()->roles->permissions->$name[0]) == 1){ return true; }}
                    if($action == 'read'){   if(intval(Illuminate\Support\Facades\Auth::user()->roles->permissions->$name[1]) == 1){ return true; }}
                    if($action == 'update'){ if(intval(Illuminate\Support\Facades\Auth::user()->roles->permissions->$name[2]) == 1){ return true; }}
                    if($action == 'delete'){ if(intval(Illuminate\Support\Facades\Auth::user()->roles->permissions->$name[3]) == 1){ return true; }}
                }
            }
        }
        return false;
    }
}

if(!function_exists('plural_or_singular')) {
    function plural_or_singular($word, $total) {
        if($word == 'item') {
            return (intval($total) == 1) ? trans('item') : trans('items');
        }
        if($word == 'day') {
            return (intval($total) == 1) ? trans('day') : trans('days');
        }
        if($word == 'year') {
            return (intval($total) == 1) ? trans('year') : trans('years');
        }
        return $word;
    }
}

if(!function_exists('prefix_http')){
    function prefix_http($value, $scheme = 'https'){
        return (strpos($value, '://') !== false) ? $value : $scheme . '://' . $value;
    }
}

if (!function_exists('prep_slash')) {
    function prep_slash($url) {
        return (substr($url, 0, 1) === '/') ? $url : '/'.$url;
    }
}

if(!function_exists('price_per_article')){
    function price_per_article(){
        return App\Models\General::price_per_article();
    }
}

if(!function_exists('read_permission')) {
    function read_permission($permissions, $what) {
        if(!empty($permissions)) {
            if($what == 'create') {
                return (string) $permissions[0];
            }
            if($what == 'read') {
                return $permissions[1];
            }
            if($what == 'update') {
                return $permissions[2];
            }
            if($what == 'delete') {
                return $permissions[3];
            }
        }

        return false;
    }
}

if(!function_exists('remove_http')) {
    function remove_http($value){
        return preg_replace("(^https?://)", "", $value);
    }
}

if(!function_exists('round_price')) {
    function round_price($value, $decimals = 2){
        return round($value, $decimals);
    }
}

if(!function_exists('replace_comma_with_br')) {
    function replace_comma_with_br($value){
        return !empty($value) ? str_replace( ',', '<br />', $value) : '-';
    }
}

if(!function_exists('replace_variables')) {
    function replace_variables($text, $user, $extras = null) {
        //User
        $user = \App\Models\User::find($user);

        //[name]
        if(!empty($user)) {
            $text = str_replace('[name]', $user->name, $text);
        }

        //[lastname]
        if(!empty($user)) {
            $text = str_replace('[lastname]', $user->lastname, $text);
        }

        //[email]
        if(!empty($user)) {
            $text = str_replace('[email]', $user->email, $text);
        }

        //[order]
        if(strpos($text, '[order]') !== false) {
            if(!empty($extras)){
                if(array_key_exists('order', $extras)) {
                    $text = str_replace('[order]', $extras['order'], $text);
                }
            }
        }

        return $text;
    }
}

if(!function_exists('search_array')) {
    function search_array($id, $array) {
        foreach(array_values($array) as $key => $val) {
            if($val === @$id) {
                return $key;
            }
        }
        return null;
    }
}

if(!function_exists('search_errors')) {
    function search_errors($index, $array) { 
        if (!empty(@$array)) {
            if (!empty(@$array[$index])) {
                return '<span class="error w-100 pr-3 text-right">'.$array[$index][0].'</span>';
            }
        }
    }
}

if(!function_exists('short_date')) {
    function short_date($value, $separator = '/'){
        if(!empty($value)) {
            $format = 'd' . $separator . 'm' . $separator . 'Y';
            return date($format, strtotime($value));
        }
        return null;
    }
}

if(!function_exists('subdomain')){
    function subdomain(){
        if(!empty(@$_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $domain = @$_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            if(!empty(@$_SERVER['HTTP_HOST'])) {
                $domain = $_SERVER['HTTP_HOST'];
            } else {
                return '';
            }
        }

        $domain = str_ireplace('www.', '', $domain);

        if(substr_count($domain, '.') > 1) {
            $domain = explode('.', $domain);
            return $domain[0];
        }

        return '';
    }
}

if(!function_exists('this_route')) {
    function this_route() {
        return Illuminate\Support\Facades\Request::route()->getName();
    }
}

if(!function_exists('transliterateString')) {
    function transliterateString($value) {
        $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'E', 'ё' => 'e', 'Ё' => 'E', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
        return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $value);
    }
}

if(!function_exists('type_page')) {
    function type_page($value){
        if($value == 'startingpage') {
            return trans('Homepage');
        }
        if($value == 'childstartingpage') {
            return trans('Daughter page');
        }
        if($value == 'wordpress') {
            return trans('Wordpress');
        }
    }
}

if(!function_exists('user_is_admin')){
    function user_is_admin() {
        return (Illuminate\Support\Facades\Auth::check() and Illuminate\Support\Facades\Auth::user()->roles->name == 'admin');
    }
}

if(!function_exists('user_is_moderator')){
    function user_is_moderator() {
        return (Illuminate\Support\Facades\Auth::check() and Illuminate\Support\Facades\Auth::user()->roles->name == 'moderator');
    }
}

if(!function_exists('user_is_writer')){
    function user_is_writer() {
        return (Illuminate\Support\Facades\Auth::check() and Illuminate\Support\Facades\Auth::user()->roles->name == 'writer');
    }
}

if(!function_exists('user_or_company')) {
    function user_or_company() {
        if(Auth::check()) {
            return (\Illuminate\Support\Facades\Auth::user()->type == 'company');
        }
        return false;
    }
}

if(!function_exists('wordpress_type')){
    function wordpress_type($value){
        if($value == 'article') {
            return trans('Article link');
        } elseif($value == 'sidebar') {
            return trans('Sidebar link');
        } elseif($value == 'both') {
            return trans('Article + Sidebar link');
        } else {
            return '-';
        }
    }
}
if(!function_exists('rgb_best_contrast')){
    function rgb_best_contrast($r, $g, $b) {

        $rPrime = $r/255;
        $gPrime = $g/255;
        $bPrime = $b/255;
        $cMax = max($rPrime, $gPrime, $bPrime);
        $cMin = min($rPrime, $gPrime, $bPrime);
        $lightness = ($cMax + $cMin)/2;
        // return $lightness;
        return $lightness >= 0.5 ? 'black' : 'white';



        // return (($r < 12) ? '255' : '000') .','. (($g < 12) ? '255' : '000').','.(($b < 12) ? '255' : '000');
        // return array(
        //     'r' => ($r < 128) ? 255 : 0,
        //     'g' => ($g < 128) ? 255 : 0,
        //     'b' => ($b < 128) ? 255 : 0
        // );
    }
}

if(!function_exists('color_inverse')){
    function color_inverse($color){
        $color = str_replace('#', '', $color);
        if (strlen($color) != 6){ return '000000'; }
        $rgb = '';
        for ($x=0;$x<3;$x++){
            $c = 255 - hexdec(substr($color,(2*$x),2));
            $c = ($c < 0) ? 0 : dechex($c);
            $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
        }
        return '#'.$rgb;
    }
}

if(!function_exists('hexInvert')){
    function hexInvert(string $color):string {
        $color = trim($color);
        $prependHash = false;
        if (strpos($color, '#') !==false){
            $prependHash=true;
            $color = str_replace('#', '', $color);
        }
        $len = strlen($color);
        if($len==3 || $len==6){
            if($len==3)
                $color = preg_replace('/(.)(.)(.)/', "\\1\\1\\2\\2\\3\\3", $color);
        } else {
            throw new \Exception("Invalid hex length ($len). Length must be 3 or 6 characters");
        }
        if (!preg_match('/^[a-f0-9]{6}$/i', $color)) {
            throw new \Exception(sprintf('Invalid hex string #%s', htmlspecialchars($color, ENT_QUOTES)));
        }
        
        $r = dechex(255 - hexdec(substr($color, 0, 2)));
        $r = (strlen($r) > 1) ? $r : '0' . $r;
        $g = dechex(255 - hexdec(substr($color, 2, 2)));
        $g = (strlen($g) > 1) ? $g : '0' . $g;
        $b = dechex(255 - hexdec(substr($color, 4, 2)));
        $b = (strlen($b) > 1) ? $b : '0' . $b;
        
        return ($prependHash ? '#' : '') . $r . $g . $b;
        }
}

if(!function_exists('sanitize_string')) {
    function sanitize_string($string) {
        return $string;
    }
}

if(!function_exists('returnTextForId')) {
    function returnTextForId($string) {
        $regex = "/\.\'\-\s\"/";
        return strtolower(preg_replace($regex,"_",$string));
    }
}

if(!function_exists('replace_text')) {
    function replace_text($string,$replacement) {
        $newString = str_replace("[city]", $replacement, $string);
        $newString = str_replace("[province]", $replacement, $newString);
        return trim($newString);
    }
}

?>
