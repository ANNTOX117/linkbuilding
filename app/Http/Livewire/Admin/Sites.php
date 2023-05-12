<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuthoritySite;
use App\Models\Category;
use App\Models\Language;
use App\Models\Site;
use App\Models\User;
use App\Models\SiteCategoryChild;
use App\Models\SiteCategoryMain;
use App\Models\AuthorityUser;
use App\Models\SiteExtraSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\WithPagination;

class Sites extends Component {

    use WithFileUploads;
    use WithPagination;

    public $title;
    public $section = 'sites';
    public $column  = 'name';
    public $sort    = 'asc';
    public $confirm;
    public $languages;
    public $categories;
    public $subcategories;
    public $currencies;
    public $site_id;
    public $name;
    public $url;
    public $type;
    public $header;
    public $meta_title;
    public $meta_description;
    public $menu;
    public $links;
    public $permanent;
    public $box;
    public $headerText;
    public $footerText;
    public $blog_header;
    public $blog_footer;
    public $daughter_header;
    public $daughter_footer;
    public $daughter_home_header;
    public $daughter_home_footer;
    public $daughter_blog_header;
    public $daughter_blog_footer;
    public $footer;
    public $footer2;
    public $footer3;
    public $footer4;
    public $contact;
    public $currency;
    public $automatic;
    public $ip;
    public $language;
    public $category = [];
    public $subcategory = [];
    public $categories_id;
    public $subcategories_id;
    public $logo;
    public $slider;
    public $slider_background;
    public $slider_category;
    public $slider_text;
    public $slide_header;
    public $slide_description;
    public $slide_anchor;
    public $slide_link;
    public $slide_inputs = [];
    public $number_slides;
    public $preview;
    public $extras = false;
    public $edit_categories = false;
    public $site;
    public $list;
    public $selections = [];
    public $site_categories;
    public $site_categories_array = [];
    public $extra_settings;
    public $no_index_follow = false;
    public $google_analytics_code;

    public $pagination;
    public $search = '';
    public $users = [];
    public $users_selected = [];
    public $siteImageHeader;
    public $siteImageProvince;
    public $favicon;
    public $previewFavicon;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name'        => 'required',
        'url'         => 'required|url',
        'meta_title'  => 'required|min:6|max:60',
        'meta_description'  => 'required|min:6|max:60',
        'type'        => 'required',
        'header'      => 'nullable|max:160',
        'menu'        => 'nullable|min:6|max:7',
        'links'       => 'nullable|min:6|max:7',
        'box'         => 'nullable|min:6|max:7',
        'headerText'  => 'nullable',
        'footerText'  => 'nullable',
        'blog_header'  => 'nullable',
        'blog_footer'  => 'nullable',
        'daughter_header'  => 'nullable',
        'daughter_footer'  => 'nullable',
        'daughter_home_header'  => 'nullable',
        'daughter_home_footer'  => 'nullable',
        'daughter_blog_header'  => 'nullable',
        'daughter_blog_footer'  => 'nullable',
        'footer'      => 'nullable',
        'footer2'     => 'nullable',
        'footer3'     => 'nullable',
        'footer4'     => 'nullable',
        'contact'     => 'nullable|email',
        'currency'    => 'required',
        'automatic'   => 'nullable',
        'permanent'   => 'nullable',
        'no_index_follow'   => 'nullable',
        'ip'          => 'nullable|ip',
        'language'    => 'required|numeric',
        'logo'        => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
        'favicon'     => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
        'slider'      => 'nullable',
        'slider_background'=> 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
        //'slider_category'=> 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
        'slider_text' => 'nullable',
        'category'    => 'required',
        'subcategory' => 'required'
    ];

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedLogo() {
        $this->preview = '';
        $this->validate([
            'logo' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000'
        ]);
    }

    public function updatedSlider() {
        $this->preview = '';
        $this->validate([
            'slider_background' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000'
        ]);
    }

    public function updatedLanguage($language) {
        if(!is_null($language)) {
            $this->categories    = Category::by_language($language);
            $this->subcategories = Category::by_language($language);
            $this->dispatchBrowserEvent('resetCategories', ['options' =>  $this->categories]);
            $this->dispatchBrowserEvent('resetSubcategories', ['options' =>  $this->subcategories]);
        }
    }

    public function updatedType($type) {
        if($type == 'Link building system') {
            $this->extras = true;
        } else {
            $this->extras = false;
        }
    }

    public function updatedIp($ip) {
        if(!empty($ip) and is_not_valid_domain($ip)) {
            $this->addError('ip', trans('The IP must be a valid IP address'));
            return false;
        }
    }


    public function updatedNumberSlides($number_slides) {
        $this->slide_inputs = [];
        if($number_slides > 0){
            for($i = 0; $i<$number_slides; $i++){
                $this->slide_inputs[] = [
                    'slide_header' => '',
                    'slide_description' => '',
                    'slide_link' => '',
                    'slide_anchor' => ''
                ];
            }
        }
    }

    public function change_selections() {
        $this->edit_categories = true;
    }

    public function mount() {
        if(!permission('sites', 'read')) {
            abort(404);
        }

        $this->title      = trans('Sites');
        $this->languages  = Language::all();
        $this->menu       = '#000000';
        $this->links      = '#000000';
        $this->box        = '#000000';
        $this->pagination = env('APP_PAGINATE');
        $this->users      = User::all_items('name','asc');
        self::loadCurrencies();
    }

    public function render() {
        $sites = Site::with_filter($this->column, $this->sort, $this->pagination, $this->search);

        // Fix for categories
        if($this->edit_categories) {
            $this->list = SiteCategoryMain::list($this->site->id);

            if(!empty($this->list)) {
                foreach($this->list as $item) {
                    $item->visibility = $this->selections[$item->id];
                }
            }

            $this->edit_categories = false;
        }

        return view('livewire.admin.sites', compact('sites'))->layout('layouts.panel');
    }

    public function sort($column) {
        $this->column = $column;
        $this->sort   = ($this->sort == 'asc') ? 'desc' : 'asc';
    }

    public function modalAddSite() {
        self::resetInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddSite');
    }

    public function modalEditSite($id) {
        $site = Site::find($id);

        if(!empty($site)) {
            $this->site_id          = $site->id;
            $this->name             = $site->name;
            $this->url              = $site->url;
            $this->meta_title       = $site->meta_title;
            $this->meta_description = $site->meta_description;
            $this->type             = $site->type;
            $this->currency         = $site->currency;
            $this->automatic        = $site->automatic;
            $this->permanent        = $site->permanent;
            $this->no_index_follow        = $site->no_index_follow;
            $this->ip               = $site->ip;
            $this->language         = $site->language;
            $this->slider_text      = $site->slider_text;
            $this->slider           = $site->slider;
            $this->logo             = '';
            $this->slider_background= '';
            $this->slider_category= '';
            $this->preview          = (!empty($site->logo) and File::exists(public_path($site->logo))) ? asset($site->logo) : null;
            $this->previewFavicon   = (!empty($site->favicon) and File::exists(public_path($site->favicon))) ? asset($site->favicon) : null;
            $this->categories       = Category::category_by_language($site->id, $site->language);
            $this->subcategories    = Category::subcategory_by_language($site->id, $site->language);
            $this->category         = SiteCategoryMain::by_site($site->id);
            $this->subcategory      = SiteCategoryChild::by_site($site->id);
            $this->categories_id    = SiteCategoryMain::by_site_ids($site->id);
            $this->subcategories_id = SiteCategoryChild::by_site_ids($site->id);

            $this->site_categories = SiteCategoryMain::select('categories.url', 'sites_categories_main.*')
            ->leftjoin('categories', 'categories.id', 'sites_categories_main.category')
            ->where('site', $site->id)->get();
            $this->site_categories_array = [];
            foreach($this->site_categories as $one_category){
                $this->site_categories_array[] = [
                    'id' => $one_category->id,
                    'category_id' => $one_category->category,
                    'site_id' => $one_category->site,
                    'category_url' => $one_category->url,
                    'headerText' => $one_category->headerText ? $one_category->headerText : '',
                    'footerText' => $one_category->footerText ? $one_category->footerText : '',
                ];
            }

            $authorities = AuthoritySite::getSite($site->id);
            if (!empty($authorities)) {
                $this->users_selected = AuthorityUser::getAuthority($authorities);
            }

            $this->slide_inputs = [];
            $decode = json_decode($this->slider_text);
            if (!empty($decode)) {
                foreach($decode as $item){
                    $this->slide_inputs[] = [
                        'slide_header' => $item->slide_header,
                        'slide_description' => $item->slide_description,
                        'slide_link' => $item->slide_link,
                        'slide_anchor' => $item->slide_anchor
                    ];
                }
            }

            if($this->type == 'Link building system') {
                $this->extras  = true;
                $this->header  = $site->header;
                $this->menu    = $site->menu;
                $this->links   = $site->links;
                $this->box     = $site->box;
                $this->headerText= $site->headerText;
                $this->footerText= $site->footerText;
                $this->blog_header= $site->blog_header;
                $this->blog_footer= $site->blog_footer;
                $this->daughter_header= $site->daughter_header;
                $this->daughter_footer= $site->daughter_footer;
                $this->daughter_home_header= $site->daughter_home_header;
                $this->daughter_home_footer= $site->daughter_home_footer;
                $this->daughter_blog_header= $site->daughter_blog_header;
                $this->daughter_blog_footer= $site->daughter_blog_footer;
                $this->footer  = $site->footer;
                $this->footer2 = $site->footer2;
                $this->footer3 = $site->footer3;
                $this->footer4 = $site->footer4;
                $this->contact = $site->contact;
            } else {
                $this->extras  = false;
                $this->header  = '';
                $this->menu    = '';
                $this->links   = '';
                $this->box     = '';
                $this->headerText= '';
                $this->footerText= '';
                $this->blog_header = '';
                $this->blog_footer = '';
                $this->daughter_header = '';
                $this->daughter_footer = '';
                $this->daughter_home_header = '';
                $this->daughter_home_footer = '';
                $this->daughter_blog_header = '';
                $this->daughter_blog_footer = '';
                $this->footer  = '';
                $this->footer2 = '';
                $this->footer3 = '';
                $this->footer4 = '';
                $this->contact = '';
            }

            //dd($this->subcategory, $this->subcategories);

            $user = [];

            foreach ($this->users as $user) {

                $selected = (in_array($user->id, $this->users_selected)) ?  1 : 0 ;
                
                $users[] = array(
                    "id" => $user->id,
                    "text" => $user->name." ".$user->lastname,
                    "selected" => $selected,
                );
            }

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('resetEditCategories', ['options' =>  $this->categories]);
            $this->dispatchBrowserEvent('resetEditUsers', ['options' => $users]);
            $this->dispatchBrowserEvent('resetEditSubcategories', ['options' =>  $this->subcategories]);
            $this->dispatchBrowserEvent('showEditSite', ['editor' => $this->footer, 'footer2' => $this->footer2, 'footer3' => $this->footer3, 'footer4' => $this->footer4, 'headerText' => $this->headerText, 'footerText' => $this->footerText, 'blog_header' => $this->blog_header, 'blog_footer' => $this->blog_footer, 'daughter_header' => $this->daughter_header, 'daughter_footer' => $this->daughter_footer,'daughter_home_header' => $this->daughter_home_header,'daughter_home_footer' => $this->daughter_home_footer,'daughter_blog_header' => $this->daughter_blog_header,'daughter_blog_footer' => $this->daughter_blog_footer]);
            $this->dispatchBrowserEvent('resetPicker', ['menu' => $this->menu, 'links' => $this->links, 'box' => $this->box]);
        }
    }

    public function modalEditCategories($id) {
        self::resetCategoriesFields();

        $this->site = Site::find($id);

        if(!empty($this->site)) {
            $this->list = SiteCategoryMain::list($id);

            if(!empty($this->list)) {
                foreach($this->list as $item) {
                    $this->selections[$item->id] = $item->visibility;
                }
            }
        }
        // $this->dispatchBrowserEvent('showEditSite', ['editor' => $this->footer, 'footer2' => $this->footer2, 'footer3' => $this->footer3, 'footer4' => $this->footer4, 'headerText' => $this->headerText, 'footerText' => $this->footerText, 'blog_header' => $this->blog_header, 'blog_footer' => $this->blog_footer, 'daughter_header' => $this->daughter_header, 'daughter_footer' => $this->daughter_footer,'daughter_home_header' => $this->daughter_home_header,'daughter_home_footer' => $this->daughter_home_footer,'daughter_blog_header' => $this->daughter_blog_header,'daughter_blog_footer' => $this->daughter_blog_footer]);
        $this->dispatchBrowserEvent('showEditCategories');
    }

    public function modalEditTextSite($id) {
        self::resetTextsFields();

        $this->site = Site::find($id);

        $this->headerText= $this->site->headerText;
        $this->footerText= $this->site->footerText;
        $this->blog_header= $this->site->blog_header;
        $this->blog_footer= $this->site->blog_footer;
        $this->daughter_header= $this->site->daughter_header;
        $this->daughter_footer= $this->site->daughter_footer;
        $this->daughter_home_header= $this->site->daughter_home_header;
        $this->daughter_home_footer= $this->site->daughter_home_footer;
        $this->daughter_blog_header= $this->site->daughter_blog_header;
        $this->daughter_blog_footer= $this->site->daughter_blog_footer;
        $this->no_index_follow = $this->site->no_index_follow;

        $this->dispatchBrowserEvent('showEditTexts', ['editor' => $this->footer, 'footer2' => $this->footer2, 'footer3' => $this->footer3, 'footer4' => $this->footer4, 'headerText' => $this->headerText, 'footerText' => $this->footerText, 'blog_header' => $this->blog_header, 'blog_footer' => $this->blog_footer, 'daughter_header' => $this->daughter_header, 'daughter_footer' => $this->daughter_footer,'daughter_home_header' => $this->daughter_home_header,'daughter_home_footer' => $this->daughter_home_footer,'daughter_blog_header' => $this->daughter_blog_header,'daughter_blog_footer' => $this->daughter_blog_footer,"no_index_follow"=>$this->no_index_follow]);
    }

    public function modalExtraSeetings($siteId) {
        self::resetTextsFields();
        $this->site_id = $siteId;
        $this->extra_settings = SiteExtraSetting::where("site_id",$siteId)->first();
        if (isset($this->extra_settings) && !empty($this->extra_settings)) {
            $this->google_analytics_code = $this->extra_settings->google_analytics_code;
        }
        $this->dispatchBrowserEvent('showExtraSettings');
    }

    public function addExtraSettings()
    {
        try {
            if (isset($this->extra_settings) && !empty($this->extra_settings)){
                $this->extra_settings->site_id = $this->site_id;
                $this->extra_settings->google_analytics_code = $this->google_analytics_code;
                $this->extra_settings->save();
            }else{
                $extra_settings = new SiteExtraSetting();
                $extra_settings->site_id = $this->site_id;
                $extra_settings->google_analytics_code = $this->google_analytics_code;
                $extra_settings->save();
            }
        } catch (\Throwable $th) {
            
        }
        $this->dispatchBrowserEvent('hideExtraSettings');
    }

    public function addSite() {
        if($this->type == 'Link building system') {
            $data = $this->validate([
                'name'              => 'required',
                'url'               => 'required|url',
                'meta_title'        => 'required|min:6|max:60',
                'meta_description'  => 'required|min:6|max:60',
                'type'              => 'required',
                'header'            => 'required|max:160',
                'menu'              => 'required|min:6|max:7',
                'links'             => 'required|min:6|max:7',
                'box'               => 'required|min:6|max:7',
                'headerText'        => 'nullable',
                'footerText'        => 'nullable',
                'blog_header'       => 'nullable',
                'blog_footer'       => 'nullable',
                'daughter_header'   => 'nullable',
                'daughter_footer'   => 'nullable',
                'daughter_home_header'        => 'nullable',
                'daughter_home_footer'        => 'nullable',
                'daughter_blog_header'        => 'nullable',
                'daughter_blog_footer'        => 'nullable',
                'footer'            => 'nullable',
                'footer2'           => 'nullable',
                'footer3'           => 'nullable',
                'footer4'           => 'nullable',
                'contact'           => 'required|email',
                'currency'          => 'required',
                'automatic'         => 'nullable',
                'ip'                => 'nullable',
                'language'          => 'required|numeric',
                'logo'              => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider'            => 'nullable',
                'slider_background' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                //'slider_category' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_text'       => 'nullable',
                'category'          => 'required',
                'subcategory'       => 'nullable',
                'users_selected'    => 'nullable',
                'permanent'         => 'nullable',
                'no_index_follow'         => 'nullable',
            ]);
        } else {
            $data = $this->validate([
                'name'              => 'required',
                'url'               => 'required|url',
                'meta_title'        => 'required|min:6|max:60',
                'meta_description'  => 'required|min:6|max:60',
                'type'              => 'required',
                'currency'          => 'required',
                'automatic'         => 'nullable',
                'ip'                => 'nullable',
                'language'          => 'required|numeric',
                'logo'              => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider'            => 'nullable',
                'slider_background' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                //'slider_category' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_text'       => 'nullable',
                'category'          => 'required',
                'subcategory'       => 'nullable',
                'users_selected'    => 'nullable',
                'permanent'         => 'nullable',
                'no_index_follow'   => 'nullable'
            ]);

            $this->header  = null;
            $this->menu    = null;
            $this->links   = null;
            $this->box     = null;
            $this->headerText= null;
            $this->footerText= null;
            $this->blog_header = null;
            $this->blog_footer = null;
            $this->daughter_header = null;
            $this->daughter_footer = null;
            $this->daughter_home_header = null;
            $this->daughter_home_footer = null;
            $this->daughter_blog_header = null;
            $this->daughter_blog_footer = null;
            $this->footer  = null;
            $this->footer2 = null;
            $this->footer3 = null;
            $this->footer4 = null;
            $this->contact = null;
            $this->no_index_follow = false;
        }

        // $this->slider_text = json_encode(
        //     array(
        //         [
        //             'header' => $this->slide_header,
        //             'description' => $this->slide_description,
        //             'link' => $this->slide_link,
        //             'anchor' => $this->slide_anchor,
        //         ],
        //         [
        //             'header' => $this->slide_header,
        //             'description' => $this->slide_description,
        //             'link' => $this->slide_link,
        //             'anchor' => $this->slide_anchor,
        //         ]
        //     )
        // );

        $this->slider_text = json_encode($this->slide_inputs);
        $data['slider_text'] = $this->slider_text;

        if(Site::already_exists($data['url'])) {
            $this->addError('url', trans('This URL already exists on the database'));
            return false;
        }

        $site = Site::create([
            'slug'      => get_domain($data['url']),
            'name'      => mysql_null($data['name']),
            'url'       => mysql_null($data['url']),
            'meta_title'=> mysql_null($data['meta_title']),
            'meta_description'=> mysql_null($data['meta_description']),
            'type'      => mysql_null($data['type']),
            'header'    => mysql_null($data['header']),
            'menu'      => mysql_null($data['menu']),
            'links'     => mysql_null($data['links']),
            'box'       => mysql_null($data['box']),
            'headerText'=> mysql_null($data['headerText']),
            'footerText'=> mysql_null($data['footerText']),
            'blog_header' => mysql_null($data['blog_header']),
            'blog_footer' => mysql_null($data['blog_footer']),
            'daughter_header' => mysql_null($data['daughter_header']),
            'daughter_footer' => mysql_null($data['daughter_footer']),
            'daughter_home_header' => mysql_null($data['daughter_home_header']),
            'daughter_home_footer' => mysql_null($data['daughter_home_footer']),
            'daughter_blog_header' => mysql_null($data['daughter_blog_header']),
            'daughter_blog_footer' => mysql_null($data['daughter_blog_footer']),
            'footer'    => mysql_null($data['footer']),
            'footer2'   => mysql_null($data['footer2']),
            'footer3'   => mysql_null($data['footer3']),
            'footer4'   => mysql_null($data['footer4']),
            'contact'   => mysql_null($data['contact']),
            'currency'  => mysql_null($data['currency']),
            'automatic' => mysql_null($data['automatic']),
            'ip'        => mysql_null($data['ip']),
            'language'  => mysql_null($data['language']),
            'permanent'  => mysql_null($data['permanent']),
            'no_index_follow' => mysql_null($data['no_index_follow']),
            //'users'     => json_encode($data['users_selected'])
        ]);



        if(!empty($data['category'])) {
            $categories = array();
            foreach($data['category'] as $i => $item) {
                $categories[$i]['category'] = mysql_null($item);
                $categories[$i]['site']     = mysql_null($site->id);
            }
            DB::table('sites_categories_main')->insert($categories);
        }

        if(!empty($data['subcategory'])) {
            $subcategories = array();
            foreach($data['subcategory'] as $i => $item) {
                $subcategories[$i]['category'] = mysql_null($item);
                $subcategories[$i]['site']     = mysql_null($site->id);
            }
            DB::table('sites_categories_child')->insert($subcategories);
        }

        if(!empty($this->logo)) {
            $name      = $site->id;
            $extension = pathinfo($this->logo->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/logos';
            $storage   = '/storage/logos/'. $filename;
            $image     = public_path($storage);

            $this->logo->storeAs($path, $filename);

            $site->logo = $storage;
            $site->save();

            Image::make($image)->orientate()->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })->encode($extension, 100)->save($image);
        }

        if(!empty($this->slider_background)) {
            $name      = $site->id.'_backslide';
            $extension = pathinfo($this->slider_background->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/slider';
            $storage   = '/storage/slider/'. $filename;
            $image     = public_path($storage);

            $this->slider_background->storeAs($path, $filename);

            $site->slider_background = $storage;
            $site->save();

            Image::make($image)->orientate()->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })->encode($extension, 100)->save($image);
        }

        // if(!empty($this->slider_category)) {
        //     $name      = $site->id.'_backslide';
        //     $extension = pathinfo($this->slider_category->getClientOriginalName(), PATHINFO_EXTENSION);
        //     $filename  = $name .'.'. $extension;
        //     $path      = 'public/slider';
        //     $storage   = 'storage/slider/'. $filename;
        //     $image     = public_path($storage);

        //     $this->slider_category->storeAs($path, $filename);

        //     $site->slider_category = $storage;
        //     $site->save();

        //     Image::make($image)->orientate()->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })->encode($extension, 100)->save($image);
        // }

        $authorities = array();

        $index = 0;
        $authorities[$index]['site'] = mysql_null($site->id);
        $authorities[$index]['url']  = mysql_null($data['url']);
        $authorities[$index]['type'] = 'startingpage';

        if(!empty($data['subcategory'])) {
            foreach($data['subcategory'] as $i => $item) {
                $index++;

                $category = Category::find($item);

                $authorities[$index]['site'] = mysql_null($site->id);
                $authorities[$index]['url']  = get_subdomain($data['url'], $category->url);
                $authorities[$index]['type'] = 'childstartingpage';
            }
        }

        DB::table('authority_sites')->insert($authorities);

        if (!empty($data['users_selected'])) {

            $authorities = AuthoritySite::getSite($site->id);
            
            foreach ($data['users_selected'] as $user) {
                if (!empty($authorities)) {
                    foreach ($authorities as $authority) {
                        $inser_authority_user[] = array(
                            'authority' => $authority, 
                            'user' => $user,
                        );
                    }
                }
            }
            
            DB::table('authority_user')->insert($inser_authority_user);
        }


        self::resetInputFields();

        session()->flash('successSite', trans('Site succesfully created'));
        $this->dispatchBrowserEvent('hideAddSite');
    }

    public function editSite() {
        
        if($this->type == 'Link building system') {
            $data = $this->validate([
                'name'        => 'required',
                'url'         => 'required|url',
                'meta_title'  => 'required|min:6|max:60',
                'meta_description'  => 'required|min:6|max:60',
                'type'        => 'required',
                'header'      => 'required|max:160',
                'menu'        => 'required|min:6|max:7',
                'links'       => 'required|min:6|max:7',
                'box'         => 'required|min:6|max:7',
                'headerText'  => 'nullable',
                'blog_header' => 'nullable',
                'blog_footer' => 'nullable',
                'daughter_header' => 'nullable',
                'daughter_footer' => 'nullable',
                'daughter_home_header' => 'nullable',
                'daughter_home_footer' => 'nullable',
                'daughter_blog_header' => 'nullable',
                'daughter_blog_footer' => 'nullable',
                'footerText'  => 'nullable',
                'footer'      => 'nullable',
                'footer2'     => 'nullable',
                'footer3'     => 'nullable',
                'footer4'     => 'nullable',
                'contact'     => 'required|email',
                'currency'    => 'required',
                'automatic'   => 'nullable',
                'ip'          => 'nullable',
                'language'    => 'required|numeric',
                'logo'        => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'favicon'        => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider'            => 'nullable',
                'slider_background' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_category' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_text'       => 'nullable',
                'category'    => 'required',
                'subcategory' => 'nullable',
                'permanent'   => 'nullable',
                'no_index_follow'   => 'nullable',
                'users_selected' => 'nullable'
            ]);
        } else {
            $data = $this->validate([
                'name'        => 'required',
                'url'         => 'required|url',
                'meta_title'  => 'required|min:6|max:60',
                'meta_description'  => 'required|min:6|max:60',
                'type'        => 'required',
                'currency'    => 'required',
                'automatic'   => 'nullable',
                'ip'          => 'nullable',
                'language'    => 'required|numeric',
                'logo'        => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'favicon'        => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider'            => 'nullable',
                'slider_background' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_category' => 'mimes:jpeg,jpg,png,gif|nullable|max:10000',
                'slider_text'       => 'nullable',
                'category'    => 'required',
                'subcategory' => 'nullable',
                'users_selected' => 'nullable',
                'permanent' => 'nullable',
                'no_index_follow'   => 'no_index_follow',
            ]);

            $this->header  = null;
            $this->menu    = null;
            $this->links   = null;
            $this->box     = null;
            $this->slider_text= null;
            $this->headerText= null;
            $this->footerTetx= null;
            $this->blog_header = null;
            $this->blog_footer = null;
            $this->daughter_header = null;
            $this->daughter_footer = null;
            $this->daughter_home_header = null;
            $this->daughter_home_footer = null;
            $this->daughter_blog_header = null;
            $this->daughter_blog_footer = null;
            $this->footer  = null;
            $this->footer2 = null;
            $this->footer3 = null;
            $this->footer4 = null;
            $this->contact = null;
        }

        if(Site::already_exists($data['url'], $this->site_id)) {
            $this->addError('url', trans('This URL already exists on the database'));
            return false;
        }

        $this->slider_text = json_encode($this->slide_inputs);
        // $otro = json_decode($this->slider_text);
        // dd($this->slider_text, $otro, $otro[0]->slide_header);
        // $this->slider_text = json_encode(
        //     array(
        //         [
        //             'header' => $this->slide_header,
        //             'description' => $this->slide_description,
        //             'link' => $this->slide_link,
        //             'anchor' => $this->slide_anchor,
        //         ],
        //         [
        //             'header' => $this->slide_header,
        //             'description' => $this->slide_description,
        //             'link' => $this->slide_link,
        //             'anchor' => $this->slide_anchor,
        //         ]
        //     )
        // );

        $data['slider_text'] = $this->slider_text;

        $site = Site::find($this->site_id);

        if(!empty($site)) {
            $site->url       = get_domain($data['url']);
            $site->name      = $data['name'];
            $site->url       = $data['url'];
            $site->meta_title= $data['meta_title'];
            $site->meta_description= $data['meta_description'];
            $site->type      = $data['type'];
            $site->header    = $data['header'];
            $site->menu      = $data['menu'];
            $site->links     = $data['links'];
            $site->box       = $data['box'];
            $site->slider_text= $data['slider_text'];
            $site->headerText= $data['headerText'];
            $site->footerText= $data['footerText'];
            $site->blog_header = $data['blog_header'];
            $site->blog_footer = $data['blog_footer'];
            $site->daughter_header = $data['daughter_header'];
            $site->daughter_footer = $data['daughter_footer'];
            $site->daughter_home_header = $data['daughter_home_header'];
            $site->daughter_home_footer = $data['daughter_home_footer'];
            $site->daughter_blog_header = $data['daughter_blog_header'];
            $site->daughter_blog_footer = $data['daughter_blog_footer'];
            $site->footer    = $data['footer'];
            $site->footer2   = $data['footer2'];
            $site->footer3   = $data['footer3'];
            $site->footer4   = $data['footer4'];
            $site->contact   = $data['contact'];
            $site->currency  = $data['currency'];
            $site->automatic = $data['automatic'];
            $site->ip        = $data['ip'];
            $site->language  = $data['language'];
            $site->permanent = $data['permanent'];
            $site->no_index_follow = $data['no_index_follow'];
            $site->save();
        }

        if(!empty($data['category'])) {
            $categories = array();
            foreach($data['category'] as $i => $item) {
                $categories[$i]['category'] = mysql_null($item);
                $categories[$i]['site']     = mysql_null($site->id);
                for($j = 0; $j < count($this->site_categories_array); $j++){
                    if($categories[$i]['site'] == $this->site_categories_array[$j]['site_id'] && $categories[$i]['category'] == $this->site_categories_array[$j]['category_id']){
                        $categories[$i]['headerText'] = $this->site_categories_array[$j]['headerText'];
                        $categories[$i]['footerText'] = $this->site_categories_array[$j]['footerText'];
                    }
                }
            }

            SiteCategoryMain::cleanup($site->id);
            DB::table('sites_categories_main')->insert($categories);

        }

        if(!empty($data['subcategory'])) {
            $subcategories = array();
            foreach($data['subcategory'] as $i => $item) {
                $subcategories[$i]['category'] = mysql_null($item);
                $subcategories[$i]['site']     = mysql_null($site->id);
            }
            SiteCategoryChild::cleanup($site->id);
            DB::table('sites_categories_child')->insert($subcategories);
        }

        if(!empty($this->logo)) {
            $name      = $site->id;
            $extension = pathinfo($this->logo->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/logos';
            $storage   = '/storage/logos/'. $filename;
            $image     = public_path($storage);

            $this->logo->storeAs($path, $filename);

            $site->logo = $storage;
            $site->save();

            Image::make($image)->orientate()->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })->encode($extension, 100)->save($image);
        }
        if(!empty($this->favicon)) {
            $name      = $site->id;
            $extension = pathinfo($this->favicon->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/favicon';
            $storage   = '/storage/favicon/'. $filename;
            $image     = public_path($storage);

            $this->favicon->storeAs($path, $filename);

            $site->favicon = $storage;
            $site->save();

            Image::make($image)->orientate()->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })->encode($extension, 100)->save($image);
        }

        if(!empty($this->slider_background)) {
            $name      = $site->id.'_backslide';
            $extension = pathinfo($this->slider_background->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/slider';
            $storage   = '/storage/slider/'. $filename;
            $image     = public_path($storage);

            $this->slider_background->storeAs($path, $filename);

            $site->slider_background = $storage;
            $site->save();

            Image::make($image)->orientate()/*->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })*/->encode($extension, 100)->save($image);
        }

        if(!empty($this->slider_category)) {
            $name      = $site->id.'_category';
            $extension = pathinfo($this->slider_category->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename  = $name .'.'. $extension;
            $path      = 'public/slider';
            $storage   = '/storage/slider/'. $filename;
            $image     = public_path($storage);

            $this->slider_category->storeAs($path, $filename);

            $site->slider_category = $storage;
            $site->save();

            Image::make($image)->orientate()/*->resize(250, null, function ($constraint){ $constraint->aspectRatio(); })*/->encode($extension, 100)->save($image);
        }

        $subcategories_id  = explode(',', $this->subcategories_id);
        $subcategories     = $data['subcategory'];

        $insert = array_diff($subcategories, $subcategories_id);
        $delete = array_diff($subcategories_id, $subcategories);
        $update = array_diff($subcategories, $insert);

        if(!empty($insert)) {
            $authorities = array();
            foreach($insert as $i => $item) {
                $category = Category::find($item);
                $authorities[$i]['site'] = mysql_null($site->id);
                $authorities[$i]['url']  = get_subdomain($data['url'], $category->url);
                $authorities[$i]['type'] = 'childstartingpage';
            }
            DB::table('authority_sites')->insert($authorities);
        }

        $authorities = AuthoritySite::getSite($site->id);
        AuthorityUser::getAuthority($authorities, true);

        $inser_authority_user = [];
        
        foreach ($data['users_selected'] as $user) {
            if (!empty($authorities)) {
                foreach ($authorities as $authority) {
                    $inser_authority_user[] = array(
                        'authority' => $authority, 
                        'user' => $user,
                    );
                }
            }
        }

        if (!empty($inser_authority_user)) {
            DB::table('authority_user')->insert($inser_authority_user);
        }

        self::resetInputFields();
        session()->flash('successSite', trans('Site succesfully edited'));
        $this->dispatchBrowserEvent('hideEditSite');
    }

    public function editCategories() {
        if(!empty($this->selections)) {
            foreach($this->selections as $i => $item) {
                SiteCategoryMain::set_site_visibility($this->site->id, $i, $item);
            }
        }

        self::resetCategoriesFields();

        session()->flash('successCategories', trans('Categories succesfully edited'));
        $this->dispatchBrowserEvent('hideEditCategories');
    }

    public function editTexts() {

        $site = Site::find($this->site->id);
        
        if(!empty($site)){
            $site->headerText               = $this->headerText;
            $site->footerText               = $this->footerText;
            $site->blog_header              = $this->blog_header;
            $site->blog_footer              = $this->blog_footer;
            $site->daughter_header          = $this->daughter_header;
            $site->daughter_footer          = $this->daughter_footer;
            $site->daughter_home_header     = $this->daughter_home_header;
            $site->daughter_home_footer     = $this->daughter_home_footer;
            $site->daughter_blog_header     = $this->daughter_blog_header;
            $site->daughter_blog_footer     = $this->daughter_blog_footer;
            $site->save();
        }
        
        self::resetTextsFields();

        session()->flash('successCategories', trans('Texts succesfully edited'));
        $this->dispatchBrowserEvent('hideEditTexts');
    }

    public function confirm($id, $links) {
        if($links > 0) {
            $this->dispatchBrowserEvent('warningDelete');
        } else {
            $this->confirm = $id;
            $this->dispatchBrowserEvent('confirmDelete');
        }
    }

    public function delete() {
        $site = Site::find($this->confirm);
        self::deleteLogo($site->logo);
        self::deleteLogo($site->slider_background);
        // self::deleteLogo($site->slider_category);
        SiteCategoryMain::cleanup($this->confirm);
        SiteCategoryChild::cleanup($this->confirm);
        AuthoritySite::cleanup($this->confirm);
        Site::destroy($this->confirm);
        $this->confirm = '';
    }

    private function deleteLogo($file) {
        $path = public_path($file);

        if(File::exists($path)) {
            File::delete($path);
        }
    }

    private function resetInputFields() {
        $this->name             = '';
        $this->url              = '';
        $this->meta_title       = '';
        $this->meta_description = '';
        $this->type             = '';
        $this->header           = '';
        $this->menu             = '';
        $this->links            = '';
        $this->box              = '';
        $this->headerText       = '';
        $this->blog_header       = '';
        $this->blog_footer       = '';
        $this->daughter_header       = '';
        $this->daughter_footer       = '';
        $this->daughter_home_header       = '';
        $this->daughter_home_footer       = '';
        $this->daughter_blog_header       = '';
        $this->daughter_blog_footer       = '';
        $this->footerText       = '';
        $this->footer           = '';
        $this->footer2          = '';
        $this->footer3          = '';
        $this->footer4          = '';
        $this->contact          = '';
        $this->currency         = '';
        $this->automatic        = '';
        $this->permanent        = '';
        $this->no_index_follow        = '';
        $this->ip               = '';
        $this->language         = '';
        $this->logo             = '';
        $this->favicon          = '';
        $this->slider           = '';
        $this->slide_header    = '';
        $this->slider_background= '';
        $this->slider_category  = '';
        $this->slider_text      = '';
        $this->slide_description= '';
        $this->slide_link       = '';
        $this->slide_anchor     = '';
        $this->categories       = '';
        $this->subcategories    = '';
        $this->category         = '';
        $this->subcategory      = '';
        $this->categories_id    = '';
        $this->subcategories_id = '';
        $this->extras           = false;
        $this->users_selected   = [];
    }

    private function resetCategoriesFields() {
        $this->site       = '';
        $this->list       = '';
        $this->selections = [];
    }

    private function resetTextsFields() {
        $this->site       = '';
        $this->headerText       = '';
        $this->blog_header       = '';
        $this->blog_footer       = '';
        $this->daughter_header       = '';
        $this->daughter_footer       = '';
        $this->daughter_home_header       = '';
        $this->daughter_home_footer       = '';
        $this->daughter_blog_header       = '';
        $this->daughter_blog_footer       = '';
    }

    private function loadCurrencies() {
        $this->currencies = array (
            'ALL' => 'Albania Lek',
            'AFN' => 'Afghanistan Afghani',
            'ARS' => 'Argentina Peso',
            'AWG' => 'Aruba Guilder',
            'AUD' => 'Australia Dollar',
            'AZN' => 'Azerbaijan New Manat',
            'BSD' => 'Bahamas Dollar',
            'BBD' => 'Barbados Dollar',
            'BDT' => 'Bangladeshi taka',
            'BYR' => 'Belarus Ruble',
            'BZD' => 'Belize Dollar',
            'BMD' => 'Bermuda Dollar',
            'BOB' => 'Bolivia Boliviano',
            'BAM' => 'Bosnia and Herzegovina Convertible Marka',
            'BWP' => 'Botswana Pula',
            'BGN' => 'Bulgaria Lev',
            'BRL' => 'Brazil Real',
            'BND' => 'Brunei Darussalam Dollar',
            'KHR' => 'Cambodia Riel',
            'CAD' => 'Canada Dollar',
            'KYD' => 'Cayman Islands Dollar',
            'CLP' => 'Chile Peso',
            'CNY' => 'China Yuan Renminbi',
            'COP' => 'Colombia Peso',
            'CRC' => 'Costa Rica Colon',
            'HRK' => 'Croatia Kuna',
            'CUP' => 'Cuba Peso',
            'CZK' => 'Czech Republic Koruna',
            'DKK' => 'Denmark Krone',
            'DOP' => 'Dominican Republic Peso',
            'XCD' => 'East Caribbean Dollar',
            'EGP' => 'Egypt Pound',
            'SVC' => 'El Salvador Colon',
            'EEK' => 'Estonia Kroon',
            'EUR' => 'Euro',
            'FKP' => 'Falkland Islands (Malvinas) Pound',
            'FJD' => 'Fiji Dollar',
            'GHC' => 'Ghana Cedis',
            'GIP' => 'Gibraltar Pound',
            'GTQ' => 'Guatemala Quetzal',
            'GGP' => 'Guernsey Pound',
            'GYD' => 'Guyana Dollar',
            'HNL' => 'Honduras Lempira',
            'HKD' => 'Hong Kong Dollar',
            'HUF' => 'Hungary Forint',
            'ISK' => 'Iceland Krona',
            'INR' => 'India Rupee',
            'IDR' => 'Indonesia Rupiah',
            'IRR' => 'Iran Rial',
            'IMP' => 'Isle of Man Pound',
            'ILS' => 'Israel Shekel',
            'JMD' => 'Jamaica Dollar',
            'JPY' => 'Japan Yen',
            'JEP' => 'Jersey Pound',
            'KZT' => 'Kazakhstan Tenge',
            'KPW' => 'Korea (North) Won',
            'KRW' => 'Korea (South) Won',
            'KGS' => 'Kyrgyzstan Som',
            'LAK' => 'Laos Kip',
            'LVL' => 'Latvia Lat',
            'LBP' => 'Lebanon Pound',
            'LRD' => 'Liberia Dollar',
            'LTL' => 'Lithuania Litas',
            'MKD' => 'Macedonia Denar',
            'MYR' => 'Malaysia Ringgit',
            'MUR' => 'Mauritius Rupee',
            'MXN' => 'Mexico Peso',
            'MNT' => 'Mongolia Tughrik',
            'MZN' => 'Mozambique Metical',
            'NAD' => 'Namibia Dollar',
            'NPR' => 'Nepal Rupee',
            'ANG' => 'Netherlands Antilles Guilder',
            'NZD' => 'New Zealand Dollar',
            'NIO' => 'Nicaragua Cordoba',
            'NGN' => 'Nigeria Naira',
            'NOK' => 'Norway Krone',
            'OMR' => 'Oman Rial',
            'PKR' => 'Pakistan Rupee',
            'PAB' => 'Panama Balboa',
            'PYG' => 'Paraguay Guarani',
            'PEN' => 'Peru Nuevo Sol',
            'PHP' => 'Philippines Peso',
            'PLN' => 'Poland Zloty',
            'QAR' => 'Qatar Riyal',
            'RON' => 'Romania New Leu',
            'RUB' => 'Russia Ruble',
            'SHP' => 'Saint Helena Pound',
            'SAR' => 'Saudi Arabia Riyal',
            'RSD' => 'Serbia Dinar',
            'SCR' => 'Seychelles Rupee',
            'SGD' => 'Singapore Dollar',
            'SBD' => 'Solomon Islands Dollar',
            'SOS' => 'Somalia Shilling',
            'ZAR' => 'South Africa Rand',
            'LKR' => 'Sri Lanka Rupee',
            'SEK' => 'Sweden Krona',
            'CHF' => 'Switzerland Franc',
            'SRD' => 'Suriname Dollar',
            'SYP' => 'Syria Pound',
            'TWD' => 'Taiwan New Dollar',
            'THB' => 'Thailand Baht',
            'TTD' => 'Trinidad and Tobago Dollar',
            'TRY' => 'Turkey Lira',
            'TRL' => 'Turkey Lira',
            'TVD' => 'Tuvalu Dollar',
            'UAH' => 'Ukraine Hryvna',
            'GBP' => 'United Kingdom Pound',
            'USD' => 'United States Dollar',
            'UYU' => 'Uruguay Peso',
            'UZS' => 'Uzbekistan Som',
            'VEF' => 'Venezuela Bolivar',
            'VND' => 'Viet Nam Dong',
            'YER' => 'Yemen Rial',
            'ZWD' => 'Zimbabwe Dollar'
        );
    }

}
