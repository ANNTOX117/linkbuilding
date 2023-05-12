<?php

namespace App\Http\Livewire\Account;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Package;
use App\Models\PackageSite;
use App\Models\SiteCategoryChild;
use App\Models\Text;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Mpdf\Mpdf;

class Cart extends Component {

    public $title;
    public $items;
    public $cart;
    public $subtotal;
    public $discount;
    public $total;
    public $vat;
    public $percent;
    public $payment;
    public $sort = 'desc';
    public $order = 'created_at';
    public $confirm;
    public $products;
    public $paginate = 15;
    public $search;
    public $offset = 0;
    public $page = 1;
    public $pages = 1;
    public $show = [];
    public $details;
    public $edit;
    public $sites = [];
    public $categories = [];
    public $json;
    public $json_url = [];
    public $json_date = [];
    public $json_site = [];
    public $json_wordpress = [];
    public $json_title = [];
    public $json_years = [];
    public $json_anchor = [];
    public $json_follow = [];
    public $json_blank = [];
    public $json_category = [];
    public $json_authority = [];
    public $json_section = [];
    public $json_content = [];
    public $json_image = [];
    public $max_fields = 0;
    public $max_categories = 0;
    public $view = 'cart';
    public $notify_title;
    public $notify_description;
    public $verify = false;
    public $file;
    public $pdf;
    public $code;
    public $numbers = [];
    public $status;
    public $option;
    public $requested = false;
    public $coins;
    public $discounted;
    public $is_company = false;
    public $payments;

    public function mount() {
        $this->payments   = (App::getLocale() == 'nl') ? route('pay') : url(App::getLocale() . '/payment');
        $this->title      = trans('My cart');
        $this->products   = \App\Models\Cart::total();
        $this->is_company = user_or_company();
    }

    public function render() {
        self::loadData();
        return view('livewire.account.cart')->layout('layouts.account', ['title' => $this->title]);
    }

    public function confirm($id) {
        $this->confirm = $id;
        $this->dispatchBrowserEvent('doRemove', ['message' => trans('Do you want to delete this item?'), 'confirm' => trans('Yes, delete'), 'cancel' => trans('No')]);
    }

    public function remove() {
        \App\Models\Cart::destroy($this->confirm);
        $this->confirm = '';

        self::loadData();
        $this->emitTo('cart.link', '$refresh');
    }

    public function perpage($param) {
        $this->paginate = $param;
        $this->page     = 1;
    }

    public function updatedSearch() {
        $this->page = 1;
    }

    public function updatedNumbers($value, $key) {
        $this->numbers[$key] = $value;
        if(count_array_values_not_null($this->numbers) == 6) {
            if(implode('', $this->numbers) == $this->code) {
                User::clean_code();
                User::verify_payments();

                $this->status = 'redirect';
                $this->dispatchBrowserEvent('triggerRedirect', ['url' => $this->payments]);
            } else {
                $this->status = 'error';
            }
        }
    }

    public function pagination($param) {
        $this->page = $param;
    }

    public function sort($column) {
        $this->sort  = ($this->sort == 'asc') ? 'desc' : 'asc';
        $this->order = $column;
    }

    public function package($option, $id, $value, $requested) {

        $this->show      = [];
        $this->show[$id] = $value;
        $this->details   = \App\Models\Cart::get_details($id);
        $detalles = \App\Models\Cart::find($id);
        $this->option    = $option;
        $this->requested = $requested;

        // if(count($this->details) == 1) {
        //     $this->details = $this->details[0];
        // }

        if($option == 'packages') {
            foreach($this->details as $i => $item) {
                $site     = AuthoritySite::find($item['authority']);
                $category = Category::find($item['category']);
                $this->details[$i]['site']     = $site->url;
                $this->details[$i]['type']     = $site->type;
                $this->details[$i]['category'] = (!empty($category)) ? $category->name : null;
            }
        } elseif(in_array($option, array('startpage article', 'blog article'))) {
            if(count($this->details) == 1) {
                $this->details = $this->details[0];
            }

            $site     = AuthoritySite::find($this->details['authority']);
            $category = (intval($this->requested) == 1) ? null : Category::find($this->details['category']);
            $this->details['site']     = ''; //$site->url;
            $this->details['type']     = $site->type;
            $this->details['category'] = (!empty($category)) ? $category->name : null;

            if(intval($this->requested) == 1) {
                $anchors = json_decode($this->details['anchor'], true);
                $urls    = json_decode($this->details['url'], true);

                $this->details['anchor'] = $anchors[0];
                $this->details['url']    = $urls[0];
            }

        } elseif($option == 'blog content link') {
            if(count($this->details) == 1) {
                $this->details = $this->details[0];
            }

            $site     = AuthoritySite::find($this->details['authority']);
            $this->details['site']     = $site->url;
            $this->details['type']     = $site->type;
        } else {
            if(count($this->details) == 1) {
                $this->details = $this->details[0];
            }
            
            $site     = AuthoritySite::find($this->details['authority']);
            $category = Category::find($this->details['category']);
            $this->details['site']     = $site->url;
            $this->details['type']     = $site->type;
            $this->details['category'] = (!empty($category)) ? $category->name : null;
        }

        if(!empty($this->edit)) {
            $this->dispatchBrowserEvent('loadDatepicker');
            $this->dispatchBrowserEvent('countCategories');
        }
    }

    public function modifyPackage($id, $option, $requested) {
        self::resetJson();

        $this->json      = [];
        $this->option    = $option;
        $this->edit      = $id;
        $this->json      = \App\Models\Cart::get_details($id);
        $this->requested = $requested;

        if(!empty($this->json)) {

            if($option == 'packages') {
                foreach($this->json as $i => $item) {
                    $authority = AuthoritySite::find($item['authority']);

                    $this->categories[$i]     = (is_numeric($authority->site)) ? SiteCategoryChild::get_categories($authority->site) : null;
                    $this->sites[$i]['url']   = $authority->url;
                    $this->sites[$i]['type']  = $authority->type;
                    $this->json_url[$i]       = $item['url'];
                    $this->json_date[$i]      = $item['date'];
                    $this->json_site[$i]      = $item['site'];
                    $this->json_title[$i]     = $item['title'];
                    $this->json_years[$i]     = $item['years'];
                    $this->json_anchor[$i]    = $item['anchor'];
                    $this->json_follow[$i]    = get_bool_follow($item['follow']);
                    $this->json_blank[$i]     = get_bool($item['blank']);
                    $this->json_category[$i]  = $item['category'];
                    $this->json_authority[$i] = $item['authority'];
                }
            } elseif(in_array($option, array('startpage article', 'blog article'))) {
                $authority = AuthoritySite::find($this->json['authority']);

                if($this->option == 'blog article') {
                    $this->categories[0] = (is_numeric($authority->wordpress)) ? SiteCategoryChild::get_categories_wp($authority->wordpress) : null;
                    $this->json_site[0]  = $this->json['wordpress'];
                } else {
                    $this->categories[0] = (is_numeric($authority->site)) ? SiteCategoryChild::get_categories($authority->site) : null;
                    $this->json_site[0]  = $this->json['site'];
                }

                $this->sites[0]['url']   = $authority->url;
                $this->sites[0]['type']  = $authority->type;
                $this->json_url[0]       = $this->json['url'];
                $this->json_date[0]      = $this->json['date'];
                $this->json_title[0]     = $this->json['title'];
                $this->json_content[0]   = $this->json['content'];
                $this->json_image[0]     = (intval($this->requested) == 1) ? null : $this->json['image'];
                $this->json_years[0]     = $this->json['years'];
                $this->json_category[0]  = (intval($this->requested) == 1) ? null : $this->json['category'];
                $this->json_authority[0] = $this->json['authority'];

                if(intval($this->requested) == 1) {
                    $urls = json_decode($this->json['url'], true);
                    $this->json_url[0] = $urls[0];
                }

            } else {
                if(count($this->json) == 1) {
                    $this->json = $this->json[0];
                }

                $authority = AuthoritySite::find($this->json['authority']);

                if($this->option == 'blog content link') {
                    //
                } elseif($this->option == 'blog sidebar link') {
                    $this->categories[0] = (is_numeric($authority->wordpress)) ? SiteCategoryChild::get_categories_wp($authority->wordpress) : null;
                } else {
                    $this->categories[0] = (is_numeric($authority->site)) ? SiteCategoryChild::get_categories($authority->site) : null;
                }

                $this->sites[0]['url']   = $authority->url;
                $this->sites[0]['type']  = $authority->type;
                $this->json_url[0]       = $this->json['url'];
                $this->json_date[0]      = $this->json['date'];
                $this->json_site[0]      = $this->json['site'];
                $this->json_title[0]     = $this->json['title'];
                $this->json_years[0]     = $this->json['years'];
                $this->json_anchor[0]    = $this->json['anchor'];
                $this->json_follow[0]    = get_bool_follow($this->json['follow']);
                $this->json_blank[0]     = get_bool($this->json['blank']);
                $this->json_authority[0] = $this->json['authority'];

                if($this->option == 'blog content link') {
                    $this->json_section[0] = $this->json['section'];
                } else {
                    $this->json_category[0] = $this->json['category'];
                }
            }

        }

        $this->dispatchBrowserEvent('triggerShow', ['id' => $id]);
    }

    public function modifiedPackage($id, $option) {
        $this->option = $option;

        if($this->option == 'packages') {
            if(count_array_values_not_null($this->json_category) != $this->max_categories) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the categories before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(count_array_values_not_null($this->json_anchor) != $this->max_fields) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the anchors before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(count_array_values_not_null($this->json_title) != $this->max_fields) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the titles before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(count_array_values_not_null($this->json_url) != $this->max_fields) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the URLs before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(count_array_values_not_null($this->json_date) != $this->max_fields) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the publication dates before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            foreach($this->json_url as $i => $url) {
                $link = prefix_http($url);
                if(!is_valid_url($link)) {
                    //$this->dispatchBrowserEvent('doAlert', ['message' => trans('Apparently ":url" is not a valid URL', ['url' => $url]), 'cancel' => trans('OK')]);
                    //return false;
                }
                $this->json_url[$i] = $link;
            }
        }



        if($this->option == 'startpage link' or $this->option == 'blog sidebar link') {
            if(empty($this->json_category[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the categories before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(empty($this->json_anchor[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the anchors before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            /*if(empty($this->json_title[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the titles before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }*/

            if(empty($this->json_url[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the URLs before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(empty($this->json_date[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the publication dates before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            foreach($this->json_url as $i => $url) {
                $link = prefix_http($url);
                if(!is_valid_url($link)) {
                    //$this->dispatchBrowserEvent('doAlert', ['message' => trans('Apparently ":url" is not a valid URL', ['url' => $url]), 'cancel' => trans('Accept')]);
                    //return false;
                }
                $this->json_url[$i] = $link;
            }
        }

        if($this->option == 'blog content link') {
            if(empty($this->json_anchor[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the anchors before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            /*if(empty($this->json_title[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the titles before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }*/

            if(empty($this->json_url[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the URLs before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            if(empty($this->json_date[0])) {
                $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the publication dates before continuing'), 'cancel' => trans('Accept')]);
                return false;
            }

            foreach($this->json_url as $i => $url) {
                $link = prefix_http($url);
                if(!is_valid_url($link)) {
                    //$this->dispatchBrowserEvent('doAlert', ['message' => trans('Apparently ":url" is not a valid URL', ['url' => $url]), 'cancel' => trans('Accept')]);
                    //return false;
                }
                $this->json_url[$i] = $link;
            }
        }

        if($this->option == 'startpage article' or $this->option == 'blog article') {
            if(intval($this->requested) != 1) {
                if(empty($this->json_category[0])) {
                    $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the categories before continuing'), 'cancel' => trans('Accept')]);
                    return false;
                }

                /*if(empty($this->json_title[0])) {
                    $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the titles before continuing'), 'cancel' => trans('Accept')]);
                    return false;
                }*/

                if(empty($this->json_url[0])) {
                    $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please complete all the URLs before continuing'), 'cancel' => trans('Accept')]);
                    return false;
                }

                if(empty($this->json_date[0])) {
                    $this->dispatchBrowserEvent('doAlert', ['message' => trans('Please select all the publication dates before continuing'), 'cancel' => trans('Accept')]);
                    return false;
                }
            }
        }

        $index   = 0;
        $details = [];

        if($this->option == 'packages') {
            foreach($this->json_authority as $i => $row) {
                $details[$index]['authority'] = @$this->json_authority[$i];
                $details[$index]['site']      = @$this->json_site[$i];
                $details[$index]['category']  = @$this->json_category[$i];
                $details[$index]['anchor']    = @$this->json_anchor[$i];
                $details[$index]['title']     = @$this->json_title[$i];
                $details[$index]['url']       = @$this->json_url[$i];
                $details[$index]['follow']    = get_follow(@$this->json_follow[$i]);
                $details[$index]['blank']     = get_bool(@$this->json_blank[$i]);
                $details[$index]['date']      = date_start_with_year(@$this->json_date[$i]) ? @$this->json_date[$i] : fix_date(@$this->json_date[$i]);
                $details[$index]['years']     = 1;

                $index++;
            }
        }

        if($this->option == 'startpage link') {
            $details[0]['authority'] = @$this->json_authority[0];
            $details[0]['site']      = @$this->json_site[0];
            $details[0]['category']  = @$this->json_category[0];
            $details[0]['anchor']    = @$this->json_anchor[0];
            $details[0]['title']     = @$this->json_title[0];
            $details[0]['url']       = @$this->json_url[0];
            $details[0]['follow']    = get_follow(@$this->json_follow[0]);
            $details[0]['blank']     = get_bool(@$this->json_blank[0]);
            $details[0]['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
            $details[0]['years']     = @$this->json_years[0];
        }

        if($this->option == 'blog sidebar link') {
            $details[0]['authority'] = @$this->json_authority[0];
            $details[0]['site']      = @$this->json_site[0];
            $details[0]['category']  = @$this->json_category[0];
            $details[0]['anchor']    = @$this->json_anchor[0];
            $details[0]['title']     = @$this->json_title[0];
            $details[0]['url']       = @$this->json_url[0];
            $details[0]['follow']    = get_follow(@$this->json_follow[0]);
            $details[0]['blank']     = get_bool(@$this->json_blank[0]);
            $details[0]['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
            $details[0]['years']     = @$this->json_years[0];
        }

        if($this->option == 'blog content link') {
            $cart      = \App\Models\Cart::find($id);
            $cart      = json_decode($cart->details, true);
            $authority = AuthoritySite::find(@$this->json_authority[0]);

            if(!empty($authority) and !empty($authority->site)) {
                $details[0]['site']      = @$this->json_site[0];
                $details[0]['wordpress'] = null;
            }

            if(!empty($authority) and !empty($authority->wordpress)) {
                $details[0]['site']      = null;
                $details[0]['wordpress'] = @$this->json_site[0];
            }

            $details[0]['authority'] = @$this->json_authority[0];
            $details[0]['section']   = @$this->json_section[0];
            $details[0]['anchor']    = @$this->json_anchor[0];
            $details[0]['title']     = @$this->json_title[0];
            $details[0]['url']       = @$this->json_url[0];
            $details[0]['follow']    = get_follow(@$this->json_follow[0]);
            $details[0]['blank']     = get_bool(@$this->json_blank[0]);
            $details[0]['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
            $details[0]['years']     = @$this->json_years[0];
            $details[0]['preview']   = @$cart['preview'];
        }

        if($this->option == 'startpage article') {
            if(intval($this->requested) == 1) {
                $cart = \App\Models\Cart::find($id);
                $json = json_decode($cart->details, true);

                $details['authority'] = @$json['authority'];
                $details['wordpress'] = @$json['wordpress'];
                $details['title']     = @$this->json_title[0];
                $details['content']   = @$json['content'];
                $details['anchor']    = @$json['anchor'];
                $details['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
                $details['years']     = @$json['years'];

                if(intval($this->requested) == 1) {
                    $url  = array();
                    $urls = json_decode($this->json['url'], true);
                    if(!empty($urls)) {
                        foreach($urls as $link) {
                            $url[] = $link;
                        }
                    }
                    $url[0] = @$this->json_url[0];
                    $details['url'] = json_encode($url);
                }

            } else {
                $details[0]['authority'] = @$this->json_authority[0];
                $details[0]['site']      = @$this->json_site[0];
                $details[0]['category']  = @$this->json_category[0];
                $details[0]['title']     = @$this->json_title[0];
                $details[0]['content']   = @$this->json_content[0];
                $details[0]['url']       = @$this->json_url[0];
                $details[0]['image']     = @$this->json_image[0];
                $details[0]['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
                $details[0]['years']     = @$this->json_years[0];
            }
        }

        if($this->option == 'blog article') {
            if(intval($this->requested) == 1) {
                $cart = \App\Models\Cart::find($id);
                $json = json_decode($cart->details, true);

                $details['authority'] = @$json['authority'];
                $details['wordpress'] = @$json['wordpress'];
                $details['title']     = @$this->json_title[0];
                $details['content']   = @$json['content'];
                $details['anchor']    = @$json['anchor'];
                $details['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
                $details['years']     = @$json['years'];

                if(intval($this->requested) == 1) {
                    $url  = array();
                    $urls = json_decode($this->json['url'], true);
                    if(!empty($urls)) {
                        foreach($urls as $link) {
                            $url[] = $link;
                        }
                    }
                    $url[0] = @$this->json_url[0];
                    $details['url'] = json_encode($url);
                }
            } else {
                $details[0]['authority'] = @$this->json_authority[0];
                $details[0]['wordpress'] = @$this->json_site[0];
                $details[0]['category']  = @$this->json_category[0];
                $details[0]['title']     = @$this->json_title[0];
                $details[0]['content']   = @$this->json_content[0];
                $details[0]['url']       = @$this->json_url[0];
                $details[0]['image']     = @$this->json_image[0];
                $details[0]['date']      = date_start_with_year(@$this->json_date[0]) ? @$this->json_date[0] : fix_date(@$this->json_date[0]);
                $details[0]['years']     = @$this->json_years[0];
            }
        }

        $details = (isset($details[0])) ? $details[0] : $details;
        $item = \App\Models\Cart::find($id);

        if(!empty($item)) {

            $authority_price = AuthoritySite::find($details['authority']);
            $item->details = json_encode($details);
            $item->price   = $authority_price->price * ($details['years'] == -5 ? 1 : $details['years']);
            $item->save();
        }

        self::resetJson();
        $this->dispatchBrowserEvent('triggerHide', ['id' => $id]);
        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Your configuration has been updated'), 'cancel' => trans('Accept')]);
    }

    public function categoryUpdated($position, $value) {
        if($this->option == 'packages') {
            $this->json_category[$position] = $value;
        } else {
            $this->json_category[0] = $value;
        }
    }

    public function dateUpdated($position, $value) {
        $this->json_date[$position] = $value;
    }

    public function doNotify() {
        if($this->is_company) {
            return redirect($this->payments);
        }

        $this->view   = 'notification';
        $this->verify = false;

        if(empty($this->notify_title)) {
            $this->notify_title = Text::get_info('Shopping terms', 'title', App::getLocale());
        }

        if(empty($this->notify_description)) {
            $this->notify_description = Text::get_info('Shopping terms', 'description', App::getLocale());
        }

        $this->dispatchBrowserEvent('onNotify');
    }

    public function doDisagree() {
        $this->view   = 'cart';
        $this->verify = false;

        $this->dispatchBrowserEvent('onCart');
    }

    public function doVerify() {
        return redirect($this->payments);
        /*if(User::payments_are_validated()) {
            return redirect($this->payments);
        } else {
            $this->pdf  = self::doPdf();
            $this->code = (User::already_have_code()) ? Auth::user()->code : User::set_code();

            self::send_email();

            $this->view    = 'notification';
            $this->verify  = true;
            $this->proceed = false;

            $this->dispatchBrowserEvent('onVerify');
        }*/
    }

    public function doResend() {
        $this->pdf  = self::doPdf();
        $this->code = (User::already_have_code()) ? Auth::user()->code : User::set_code();

        self::send_email();

        $this->view    = 'notification';
        $this->verify  = true;
        $this->proceed = false;

        $this->dispatchBrowserEvent('onResend');
    }

    private function loadData() {
        $discounts      = '0';
        $this->pages    = ceil($this->products / $this->paginate);
        $this->offset   = ($this->page - 1) * $this->paginate;
        $this->cart     = \App\Models\Cart::list($this->offset, $this->paginate, $this->order, $this->sort, $this->search);
        $this->subtotal = \App\Models\Cart::myTotal();
        $this->discount = get_discount($this->subtotal);

        if(count($this->discount) > 0) {
            foreach($this->discount as $discount) {
                $discounts = $discounts + floatval($discount['discount']);
            }
        }

        $this->total      = (floatval($discounts) > 0) ? (floatval($this->subtotal) - floatval($discounts)) : floatval($this->subtotal);
        $this->vat        = get_vat();
        $this->percent    = ((floatval($this->vat) / 100) * floatval($this->total));
        $this->payment    = floatval($this->total) + floatval($this->percent);
        $this->coins      = 0;
        $this->discounted = 0;

        // Discount by coins
        $credits = User::get_credits();
        if(!empty($credits) and floatval($credits) > 0) {
            $diff  = floatval($this->payment) - floatval($credits);
            $coins = floatval($this->payment) - floatval($diff);
            $paid  = ($diff > 0) ? $coins : ($credits - abs($diff));

            $this->coins      = floatval($paid);
            $this->discounted = floatval($this->payment);
            $this->payment    = floatval($this->payment) - floatval($paid);
        }

        self::details();
    }

    private function details() {
        if(!empty($this->cart)) {
            foreach($this->cart as $i => $item) {
                if($item->item == 'packages') {
                    $package = Package::find($item->identifier);
                    $this->cart[$i]['type']  = trans('Package');
                    $this->cart[$i]['name']  = $package->name;
                    $this->cart[$i]['text']  = $package->description;
                    $this->cart[$i]['price'] = $package->price;
                }
            }
        }
    }

    private function doPdf() {
        self::create_downloads();

        $name  = trans('General conditions');
        $file  = $name . '.pdf';
        $path  = public_path('downloads/' . $file);
        $title = $this->notify_title;
        $text  = $this->notify_description;
        $view  = view('pdf.conditions', compact('title', 'text'));
        $view  = $view->render();

        $mpdf  = new Mpdf();
        $mpdf->WriteHTML($view);
        $mpdf->Output($path, 'F');

        $this->file = $file;

        return $path;
    }

    private function create_downloads() {
        if(!File::exists(public_path('downloads'))) {
            File::makeDirectory('downloads');
        }
    }

    private function send_email() {
        $name    = Auth::user()->name;
        $email   = Auth::user()->email;
        $code    = $this->code;
        $pdf     = $this->pdf;
        $file    = $this->file;
        $subject = trans('Verification code');

        Mail::send('mails.template',
            array(
                'code' => $code,
            ), function($message) use ($email, $name, $subject, $pdf, $file) {
                if(!empty($pdf)) {
                    $message->attach($pdf, ['as' => $file, 'mime' => 'application/pdf']);
                }
                $message->to($email, $name)->subject($subject);
            });
    }

    private function resetJson() {
        $this->edit           = '';
        $this->json           = '';
        $this->sites          = [];
        $this->categories     = [];
        $this->json_url       = [];
        $this->json_date      = [];
        $this->json_site      = [];
        $this->json_title     = [];
        $this->json_years     = [];
        $this->json_anchor    = [];
        $this->json_follow    = [];
        $this->json_blank     = [];
        $this->json_category  = [];
        $this->json_authority = [];
        $this->json_section   = [];
    }

}
