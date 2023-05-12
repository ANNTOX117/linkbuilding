<?php

namespace App\Http\Livewire\Account;

use App\Models\General;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use App\Models\SiteCategoryChild;
use App\Models\AuthoritySite;
use App\Models\Wordpress;
use App\Models\ArticleRule;
use App\Models\ArticleImage;
use App\Models\Article;
use App\Models\User;
use App\Models\Externallink;
use App\Models\Category;
use App\Models\Link;
use App\Models\Cart;

use Carbon\Carbon;
use File;

class Buylinks extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $sortBy = 'url';
	public $sortDirection = 'asc';
	public $perPage = 10;
	public $language = 0;
	public $search = '';

	private $client;
	private $headers = [];
	private $headers_media = '';
	private $site_url = '';
	private $api_key;
	//Article
	public $categories_wordpress = [];
	public $get_page;
	public $authority_site = '';
	public $categories;
	public $categories_article = [];
	public $image;
	public $article_url = '';
	public $articletitle;
	public $description;
	public $expired_article = '';
	public $yearsarticle = '';
	public $permanent_link_article = 0;
	public $img_outstanding = [];
	public $categories_input = '';
	public $article_image = [];
	public $list_ul = '';

	//Blog
	//Esta seccion fue la que se oculto del front
	public $blog_site = '';
	public $blog_default = '';
	public $blog_categories = [];
	public $blog_url;
	public $blog_title;
	public $blog_url_preview;
	public $blog_follow = 1;
	public $blog_follow_string = '';
    public $blog_blank;
    public $blog_blank_string = '';
	public $blog_url_string = '';
	public $blog_title_string = '';
	public $blog_anchor = '';
	public $blog_anchor_string = '';
	public $blog_expired = '';
	public $blog_years;
	public $permanent_link_blog = 0;
	public $status_form_blog = '';
	public $message_status_blog = '';
	public $blogactive = 'noshow';
	public $blog_section = 0;
	public $blog_section_num = [];

	public $blog_section_selected = '';
	public $blog_status = '';
	public $blog_anchor_message = '';
	public $blog_section_first = '';
	//////////////////////////////////////////

	//Link
	public $linkactive = 'noshow';
	public $site_links;
	public $linktitle;
	public $link_url;
	public $expired_link = '';
	public $yearslink;
	public $permanent_link_link = 0;
	public $categories_links = '';
	public $section;
	public $url_preview;
	public $follow = 1;
	public $follow_string = 'rel="follow"';
    public $blank;
    public $blank_string;
	public $url_string = '';
	public $title_string = '';
	public $anchor = '';
	public $anchor_string = '';

	//StartingArticle
	public $startingarticleactive = 'noshow';
	public $status_starting_article = '';
	public $message_starting_article = '';
	public $article_starting_default = '';
	public $article_starting_site = '';
	public $article_starting_url = '';
	public $article_starting_title = '';
	public $article_starting_expired = '';
	public $article_starting_years = '';
	public $permanent_link_starting_article = '';
	public $article_starting_description = '';
	public $article_starting_content = '';
	public $article_starting_sections = [];
	public $article_starting_selected = '';
	public $article_starting_categories_input = '';
	public $img_outstanding_article = [];
	public $list_ul_article = '';
	public $starting_article_image = [];

	//startingpage
	public $startingpageactive = 'noshow';
	public $site_startingpage;
	public $permanent_link_starting = 0;
	public $categories_startingpage = [];
	public $section_startingpage;
	public $starting_url;
	public $starting_follow = 'rel="follow"';
    public $starting_blank;
	public $starting_anchor = '';
	public $starting_title;
	public $starting_description;
	public $starting_url_preview;
	public $expired_startingpage;
	public $yearstartingpage;
	public $url_starting_string = '';
	public $title_starting_string = '';
	public $anchor_starting_string = '';
	public $follow_starting_string = 'rel="follow"';
    public $blank_starting_string;
	public $publication_date;

	/* Livewire */
	public $title;
	public $tab = 'startpage';
	public $menu;

	//public $sites_authority = '';
	public $sites_wordpress = '';
	public $formactive = 'noshow';
    public $formrequeststartpage = 'noshow';
	public $formrequestblog = 'noshow';

	/* erros */
	public $custom_error;
	public $domain_error;

	/*Message*/
	public $message = '';
	public $domain_message;
	public $article_default;
	public $link_default;
	public $startingpage_default;
	public $status_form_sidebar = '';
	public $message_status_sidebar = '';
	public $status_form_article = '';
	public $message_status_article = '';
	public $status_form_startingpage = '';
	public $message_status_startingpage = '';

	public $startpage_link_list		= [];
	public $blog_sidebar_list 		= [];
	public $blog_content_list		= [];
	public $startpage_article_list	= [];
	public $blog_article_list 		= [];

    public $request_title;
    public $request_description;
    public $request_texts = [];
    public $request_urls  = [];

    public $invoce;

    public $blog_preview;
    public $max_links;

    public $requested_articles;

	protected $listeners = [
		'changeimg', 'changeimgarticle',
		'datelink','datearticle','dateStartingpage','datestartingarticle', 'dateblog',
		'changestartingpage', 'changelink' ,'changeblog' , 'changearticle' , 'changesectionblog'
	];
	//public $wordpress_sidebar;
	//public $wordpress_article;

    protected $paginationTheme = 'bootstrap';

	public function __construct() {
		parent::__construct();
		$this->client = new \GuzzleHttp\Client();
	}

	private function autenticate($wordpress) {
		$validate_domain = is_valid_url($wordpress->url);
		if ($validate_domain) {
			$response = json_decode($this->client->post($wordpress->url . '/wp-json/api-bearer-auth/v1/login', [
				\GuzzleHttp\RequestOptions::JSON => [
					'username' => $wordpress->username,
					'password' => do_decrypt($wordpress->password),
				],
			])->getBody());
			if (isset($response->access_token)) {
				$headers = [
					'Authorization' => 'Bearer ' . $response->access_token,
					'Accept'        => 'application/json',
				];
				$this->headers = $headers;
				$this->headers_media = $response->access_token;
			}
			else{

				$this->headers = '';
				$this->headers_media = '';
			}
		}
		else{
			$this->headers = '';
			$this->headers_media = '';
			$this->domain_message = 'error_domain';
			$this->domain_error = trans('The domain does not exist');
			$this->dispatchBrowserEvent('validateDomain');
		}
	}

	private function get_page($url, $slug){
		$page = json_decode($this->client->get($url . '/wp-json/wp/v2/posts?slug='.$slug, [ 'headers' => $this->headers, 'form_params' ])->getBody());
		return $page;
	}

	private function format_post($data):array {
		$post_format = [
			'title'      => $data['articletitle'],
			'content'    => $data['description'],
			'status'     => 'publish',
			'date'       => date("Y-m-d H:i:s"),
			'categories' => $data['categories'],
			'link' => $data['slug'],
		];
		return $post_format;
	}

	public function table($table) {
		$this->tab = $table;
		$this->sortBy = 'url';
		$this->sortDirection = 'asc';
		$this->perPage = 10;
		$this->search = '';

        $this->resetPage();
	}

	public function transition(){
		$this->dispatchBrowserEvent('trans');
	}

	public function showLink($site_id, $authority){
	    $this->authority_site = $authority;
		$this->link_default = $site_id;
		$this->site_links = $site_id;
		$this->linkactive = 'yesshow';
		$this->status_form_sidebar   = '';
		$this->message_status_sidebar= '';
		$this->dispatchBrowserEvent('showFormcontent', ['tab' => 'sidebar']);
	}

	public function hideLink(){
		$this->linkactive = 'noshow';
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function hidestartingarticle($value=''){
		$this->startingarticleactive = 'noshow';
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function showForm($site_id){
		$this->authority_site = $site_id;
		$this->article_default = $site_id;
		$this->formactive = 'yesshow';
		$this->dispatchBrowserEvent('showFormcontent', ['tab' => 'blog']);
	}

    public function showRequestStartpageForm($site) {
        $this->articleStartingCategories();
        $this->article_starting_site = $site;
        $this->article_starting_default = $site;
        $this->formrequeststartpage = 'yesshow';
        $this->dispatchBrowserEvent('showRequestStartpageFormcontent', ['tab' => 'article']);
    }

    public function showRequestBlogForm($site) {
	    $this->authority_site  = $site;
        $this->article_default = $site;
        $this->formrequestblog = 'yesshow';
        $this->dispatchBrowserEvent('showRequestBlogFormcontent', ['tab' => 'blog']);
    }

	public function showStartingarticle($site_id){
        $this->articleStartingCategories();
		$this->article_starting_site = $site_id;
		$this->article_starting_default = $site_id;
		$this->startingarticleactive = 'yesshow';
		$this->dispatchBrowserEvent('showFormcontent', ['tab' => 'article']);

	}

	public function hideForm(){
		$this->formactive = 'noshow';
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function showstartingpage($startingpage_id){
		$this->startingpage_default = $startingpage_id;
		$this->site_startingpage = $startingpage_id;
		$this->startingpageactive = 'yesshow';

		$this->startingPageCategories();

		// dd($this->permanent_link_starting);
		$this->status_form_startingpage = '';
		$this->message_status_startingpage = '';
		$this->dispatchBrowserEvent('showFormcontent', ['tab' => 'startpage']);
	}

	public function hidestartingpage(){
		$this->startingpageactive = 'noshow';
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function showblog($blog_id){
		$this->blog_default = $blog_id;
		$this->blog_site = $blog_id;
		$doc = new \DOMDocument();
		@$doc->loadHTML(AuthoritySite::findOrFail($blog_id)->preview);
		$num_article = $doc->getElementsByTagName("article");
		$this->blog_section_num = $num_article->length;
		$this->clean_section_blog();
		$this->blogactive = 'yesshow';
		$this->dispatchBrowserEvent('showFormcontent', ['tab' => 'content']);
	}

	public function hideblog(){
		$this->startingpageactive = 'noshow';
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function addStartingarticle(){

        $data = $this->validate([
			'article_starting_site'    		=> 'required|numeric',
			'article_starting_selected' 	=> 'required',
			'article_starting_url'       	=> 'required',
			'article_starting_title'      	=> 'required|max:160',
			'article_starting_description'	=> 'required',
			'article_starting_years'		=> 'required',
			'article_starting_expired'		=> 'nullable|date_format:d/m/Y',
		]);

        $details   = array();
        $authority = AuthoritySite::find($data['article_starting_site']);
        $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

        $details['authority'] = mysql_null($data['article_starting_site']);
        $details['site']      = (!empty($authority)) ? $authority->site : null;
        $details['category']  = mysql_null($data['article_starting_selected']);
        $details['title']     = mysql_null($data['article_starting_title']);
        $details['content']   = mysql_null($data['article_starting_description']);
        $details['url']       = mysql_null($data['article_starting_url']);
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = mysql_null($data['article_starting_years']);
        $details['image']     = $this->img_outstanding_article['img'];

        // $price_for_year = $price * $details['years'];
        $price_for_year = $price * ($details['years'] == -5 ? 1 : $details['years']);

        Cart::create(['item' => 'startpage article', 'identifier' => $data['article_starting_site'], 'details' => json_encode($details), 'price' => $price_for_year, 'user' => Auth::user()->id]);

		$this->startingarticleactive = 'noshow';
		self::resetStartingArticlesInput();
		$this->dispatchBrowserEvent('hideFormcontent');

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
	}

	public function addStartingpage(){
	    $data = $this->validate([
			'site_startingpage'     => 'required|numeric',
			'section_startingpage'  => 'required|numeric',
			'starting_url'          => 'required',
			'starting_follow'       => 'required',
            'starting_blank'        => 'nullable',
			'starting_anchor'       => 'required|max:255',
			'starting_title'        => 'nullable',
			'starting_description'  => 'nullable|max:255',
			'expired_startingpage'  => 'nullable|date_format:d/m/Y',
			'yearstartingpage'      => 'required|numeric',
		]);

	    $details   = array();
        $authority = AuthoritySite::find($data['site_startingpage']);
        $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

        $details['authority'] = mysql_null($data['site_startingpage']);
        $details['site']      = (!empty($authority)) ? $authority->site : null;
        $details['category']  = mysql_null($data['section_startingpage']);
        $details['anchor']    = mysql_null($data['starting_anchor']);
        $details['title']     = mysql_null($data['starting_title']);
        $details['description']= mysql_null($data['starting_description']);
        $details['url']       = mysql_null(prefix_http($data['starting_url']));
        $details['follow']    = ($data['starting_follow'] == 'rel="follow"' or $data['starting_follow'] == 1) ? 'follow' : 'nofollow';
        $details['blank']     = ($data['starting_blank'] == '_blank') ? 1 : null;
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = mysql_null($data['yearstartingpage']);

        $price_for_year = $price * ($details['years'] == -5 ? 1 : $details['years']);

        Cart::create(['item' => 'startpage link', 'identifier' => $data['site_startingpage'], 'details' => json_encode($details), 'price' => $price_for_year, 'user' => Auth::user()->id]);

        $this->startingpageactive = 'noshow';
		self::resetStartingpageInput();

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
	}

	public function addLink(){
		$data = $this->validate([
			'site_links'    => 'required',
			'section'		=> 'nullable|numeric',
			'link_url'      => 'required',
			'follow'		=> 'required',
            'blank'		    => 'nullable',
			'anchor'		=> 'required|max:255',
			'linktitle'		=> 'nullable',
			'expired_link'  => 'nullable|date_format:d/m/Y',
			'yearslink'     => 'required|numeric',
		]);

		$details   = array();
        $wordpress = Wordpress::find($data['site_links']);
        $authority = AuthoritySite::find($this->authority_site);
        $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

        $details['authority'] = $this->authority_site;
        $details['site']      = (!empty($wordpress)) ? $wordpress->id : null;
        $details['category']  = mysql_null($data['section']);
        $details['anchor']    = mysql_null($data['anchor']);
        $details['title']     = mysql_null($data['linktitle']);
        $details['url']       = mysql_null(prefix_http($data['link_url']));
        $details['follow']    = ($this->follow == 1) ? 'follow' : 'nofollow';
        $details['blank']     = ($this->blank == '_blank') ? 1 : null;
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = mysql_null($data['yearslink']);

        $price_for_year = $price * ($details['years'] == -5 ? 1 : $details['years']);

        Cart::create(['item' => 'blog sidebar link', 'identifier' => $this->authority_site, 'details' => json_encode($details), 'price' => $price_for_year, 'user' => Auth::user()->id]);

        $this->linkactive = 'noshow';
		self::resetLinkInputs();

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
	}

	public function addBlog(){

		$data = $this->validate([
			'blog_site'    => 'required|numeric',
			'blog_section' => 'required',
			'blog_section_selected' => 'required',
			'blog_url'       => 'required',
			'blog_title'      => 'required|max:160',
			'blog_follow'       => 'required',
            'blog_blank'       => 'nullable',
			'blog_anchor'       => 'required',
			'blog_years'      => 'required',
			'blog_expired'   => 'nullable|date_format:d/m/Y',
		]);

        $details    = array();
        $authority  = AuthoritySite::find($data['blog_site']);
        $identifier = (is_numeric($authority->wordpress)) ? $authority->wordpress : $authority->site;
        $price     = (floatval($authority->price_special) > 0 and (floatval($authority->price_special) < floatval($authority->price))) ? $authority->price_special : $authority->price;

        $details['authority'] = mysql_null($data['blog_site']);
        $details['site']      = $authority->site;
        $details['wordpress'] = $authority->wordpress;
        $details['anchor']    = mysql_null($data['blog_anchor']);
        $details['title']     = mysql_null($data['blog_title']);
        $details['url']       = mysql_null(prefix_http($data['blog_url']));
        $details['follow']    = ($data['blog_follow'] == 1) ? 'follow' : 'nofollow';
        $details['blank']     = ($data['blog_blank'] == '_blank') ? 1 : null;
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = mysql_null($data['blog_years']);
        $details['section']   = $data['blog_section'];


        $folllow = ($this->blog_follow == 2) ? 'nofollow"': "follow";
		$string_2 = '<a title="'.$this->blog_title.'" href="'.prefix_http($this->blog_url).'" rel="'.$folllow.'" >'.$this->blog_anchor.'</a>';
		$this->blog_preview = $this->str_replace_first($this->blog_anchor, $string_2, $this->blog_section_first);

        $details['preview']   = mysql_null($this->blog_preview);

        $price_for_year = $price * $details['years'];

        Cart::create(['item' => 'blog content link', 'identifier' => $identifier, 'details' => json_encode($details), 'price' => $price_for_year, 'user' => Auth::user()->id]);

        $this->formactive = 'noshow';
		self::resetArticlesInputFields();
		$this->dispatchBrowserEvent('hideFormcontent');

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
	}

	public function addPost() {

		$data = $this->validate([
			'authority_site'    => 'required',
			'categories'        => 'nullable',
			'img_outstanding'	=> 'required',
			'article_url'       => 'required',
			'articletitle'      => 'required|max:160',
			'description'       => 'required',
			'yearsarticle'      => 'required',
			'expired_article'   => 'nullable|date_format:d/m/Y',
		]);

		preg_match_all('!(https?:)?//\S+\.(?:jpe?g|png|gif)!Ui' , $data['img_outstanding']['img'], $image_url);

		$rules = ArticleRule::all_items();

		if(!empty($rules)) {
			$this->message = '';
            if(count_iframe($data['description']) > 0) {
                $this->message = 'error_description';
                $this->custom_error = trans('Iframe tag is not allowed in articles');
            }
            if(count_script($data['description']) > 0) {
                $this->message = 'error_description';
                $this->custom_error = trans('Script tag is not allowed in articles');
            }
            if(count_words($data['description']) < $rules->min_words) {
                $this->message = 'error_description';
                $this->custom_error = trans('Minimum of words allowed by article: ') . $rules->min_words;
            }
			if(count_words($data['description']) > $rules->max_words) {
				$this->message = 'error_description';
				$this->custom_error = trans('Maximum of words allowed by article: ') . $rules->max_words;
			}
			if(count_links($data['description']) > $rules->max_links) {
				$this->message = 'error_description';
				$this->custom_error = trans('Maximum of links allowed by article: ') . $rules->max_links;
			}
			if (bad_words($data['description'], 'include')) {
				$this->message = 'error_description';
				$this->custom_error = trans('The description has bad words');
			}
			if ($this->message != '') {
				$this->dispatchBrowserEvent('messageFilters');
				return false;
			}
			else{
				$this->message = '';
			}
		}

        $details   = array();
        $wordpress = AuthoritySite::get_authority_by_wp($data['authority_site']);
        $price     = (floatval($wordpress->price) > floatval($wordpress->price_special)) ? $wordpress->price : $wordpress->price_special;

        $details['authority'] = $wordpress->id;
        $details['wordpress'] = mysql_null($data['authority_site']);
        $details['category']  = mysql_null($data['categories']);
        $details['title']     = mysql_null($data['articletitle']);
        $details['content']   = mysql_null($data['description']);
        $details['url']       = mysql_null($data['article_url']);
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = mysql_null($data['yearsarticle']);
        $details['image']     = $data['img_outstanding']['img'];

        $price_for_year = $price * ($details['years'] == -5 ? 1 : $details['years']);

        Cart::create(['item' => 'blog article', 'identifier' => $data['authority_site'], 'details' => json_encode($details), 'price' => $price_for_year, 'user' => Auth::user()->id]);

		$this->formactive = 'noshow';
        self::resetArticlesInput();
        $this->dispatchBrowserEvent('hideFormcontent');

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
	}

    public function addRequestBlog() {
        $data = $this->validate([
            'authority_site'      => 'required',
            'request_title'       => 'nullable|max:160',
            'request_description' => 'required',
            'request_texts.*'     => 'nullable|max:160',
            'request_urls.*'      => 'nullable|url',
            'publication_date'    => 'nullable|date_format:d/m/Y'
        ]);

        $empty_request_texts = true;
        $empty_request_urls  = true;

        if(count($this->request_texts) > 0) {
            foreach($this->request_texts as $texts) {
                if(!empty($texts)) {
                    $empty_request_texts = false;
                    break;
                }
            }
        }

        if(count($this->request_urls) > 0) {
            foreach($this->request_urls as $urls) {
                if(!empty($urls)) {
                    $empty_request_urls = false;
                    break;
                }
            }
        }

        if($empty_request_texts or $empty_request_urls) {
            $this->addError('request_texts.0', trans('You must add at least one text for your links'));
            $this->addError('request_urls.0', trans('You must add at least one URL for your links'));
            return false;
        }

        $rules = ArticleRule::all_items();

        if(!empty($rules)) {
            $this->message = '';
            if(count_iframe($data['request_description']) > 0) {
                $this->message = 'error_description';
                $this->custom_error = trans('Iframe tag is not allowed in articles');
            }

            if(count_script($data['request_description']) > 0) {
                $this->message = 'error_description';
                $this->custom_error = trans('Script tag is not allowed in articles');
            }

            if(count_words($data['request_description']) > $rules->max_words) {
                $this->message = 'error_description';
                $this->custom_error = trans('Maximum of words allowed by article: ') . $rules->max_words;
            }
            if(count_links($data['request_description']) > $rules->max_links) {
                $this->message = 'error_description';
                $this->custom_error = trans('Maximum of links allowed by article: ') . $rules->max_links;
            }
            if (bad_words($data['request_description'], 'include')) {
                $this->message = 'error_description';
                $this->custom_error = trans('The description has bad words');
            }
            if ($this->message != '') {
                $this->dispatchBrowserEvent('messageFilters');
                return false;
            }
            else{
                $this->message = '';
            }
        }

        $details   = array();
        $wordpress = AuthoritySite::get_authority_by_wp($data['authority_site']);
        $price     = price_per_article();

        $details['authority'] = $wordpress->id;
        $details['wordpress'] = mysql_null($data['authority_site']);
        $details['title']     = mysql_null($data['request_title']);
        $details['content']   = mysql_null($data['request_description']);
        $details['anchor']   = json_encode($this->request_texts);
        $details['url']      = json_encode($this->request_urls);
        $details['date']      = (!empty($this->publication_date)) ? fix_date_on_request($this->publication_date) : Carbon::now();
        $details['years']     = 1;

        Cart::create(['item' => 'blog article', 'identifier' => $data['authority_site'], 'details' => json_encode($details), 'requested' => 1, 'price' => $price, 'user' => Auth::user()->id]);

        $this->formrequestblog      = 'noshow';
        $this->formrequeststartpage = 'noshow';

        self::resetArticlesInput();
        self::resetRequestedArticlesInput();
        $this->dispatchBrowserEvent('hideFormcontent');

        $this->dispatchBrowserEvent('doComplete', ['message' => trans('Do you want to continue shopping?'), 'confirm' => trans('No, go to checkout'), 'cancel' => trans('Yes'), 'redirect' => (App::getLocale() == 'nl') ? route('customer_cart') : url(App::getLocale() . '/cart')]);
        $this->emitTo('cart.link', '$refresh');
    }

	public function updatedArticletitle(){
		if (!empty($this->articletitle)) {
			$this->article_url = get_slug($this->articletitle);
			$this->dispatchBrowserEvent('generateSlug');
		}
	}

	public function updatedCategories($option){
		$this->categories = $option;
	}

	public function updatedArticle_starting_selected($option){
		$this->article_starting_selected = $option;
	}

	public function updatedArticlestartingtitle(){
		if (!empty($this->article_starting_title)) {
			$this->article_starting_url = get_slug($this->article_starting_title);
			$this->dispatchBrowserEvent('generateSlug');
		}
	}

	public function changeimg($img_id){
		$this->list_ul = 'active';
		if (!empty($img_id)) {
			foreach ($this->article_image as $item) {
				if ($item['id'] == $img_id) {
					$this->img_outstanding['img'] = $item['src']['small'];
					$this->img_outstanding['photographer'] = $item['photographer'];
				}
			}
			$this->list_ul = '';
		}
	}

	public function changeimgarticle($img_id){
		$this->list_ul_article = 'active';
		if (!empty($img_id)) {
			foreach ($this->starting_article_image as $item) {
				if ($item['id'] == $img_id) {
					$this->img_outstanding_article['img'] = $item['src']['small'];
					$this->img_outstanding_article['photographer'] = $item['photographer'];
				}
			}
			$this->list_ul_article = '';
		}
	}
	public function updatedblogsection(){
		$preview = AuthoritySite::findOrFail($this->blog_site)->preview;
		preg_match_all('/<article[^>]*?>(.*?)<\/article>/is', AuthoritySite::findOrFail($this->blog_site)->preview , $matches);
		$this->blog_section_selected = $matches[1][$this->blog_section];
		$this->blog_section_first = '';
		$this->clean_section_blog();
	}

	public function clean_section_blog(){

		if (AuthoritySite::findOrFail($this->blog_site)->preview != '') {

			if ($this->blog_section_selected == '') {
				preg_match_all('/<article[^>]*?>(.*?)<\/article>/is', AuthoritySite::findOrFail($this->blog_site)->preview , $matches);
				$section_selected = $matches[1][$this->blog_section];
			}
			else{
				$section_selected = $this->blog_section_selected;
			}

			if ($this->blog_section_first == '') {
				$this->blog_section_first = $section_selected;
			}

			$tags = ['p','h1','h2','h3','h4','h5','h6','ul','li','lo'];

			foreach ($tags as $tag) {

				$section_selected = preg_replace("/<".$tag."[^>]*?>/", "<span class='text-h'><</span><span class='text-h'>".$tag."</span><span class='text-h'>></span>", $section_selected);

				$section_selected = preg_replace("/<\/".$tag.">/", "<span class='text-h'><</span><span class='text-h'>/".$tag."</span><span class='text-h'>></span>", $section_selected);

			}

			preg_match_all('/<a\s[^>]*href=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU', $section_selected, $matches1);
			preg_match_all('/<a\s[^>]*title=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU', $section_selected, $matches2);
			preg_match_all('/<a\s[^>]*rel=\"([^\"]*)\"[^>]*>(.*)<\/a>/siU', $section_selected, $matches3);

			for ($i=0; $i < count($matches1); $i++) {

				$a_full 	= (isset($matches1[0][$i])) ? $matches1[0][$i] : '';
				$a_href 	= (isset($matches1[1][$i])) ? ' <span class="text-href">href</span><span class="text-h">=</span><span class="text-string">"'.$matches1[1][$i].'"</span>' : '';
				$a_title 	= (isset($matches2[1][$i])) ? ' <span class="text-href">title</span><span class="text-h">=</span><span class="text-string">"'.$matches2[1][$i].'"</span>' : '';
				$a_rel 		= (isset($matches3[1][$i])) ? ' <span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"'.$matches3[1][$i].'"</span>' : '';
				$a_anchor 	= (isset($matches1[2][$i])) ? $matches1[2][$i] : '';

				$section_selected = str_replace($a_full, '<span class="text-h"><</span><span class="text-h">a</span>'.$a_href.' '.$a_title.' '.$a_title.'<span class="text-h">></span>'.$a_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>', $section_selected);
			}

			$this->blog_section_selected = $section_selected;
			$this->dispatchBrowserEvent('updatePreviewblog');

		}
	}

	public function updatedBlogurl(){

		if (!empty($this->blog_anchor) && !empty($this->blog_url)) {
			$isset = strpos($this->blog_section_selected, $this->blog_anchor);
			if (!$isset) {
				$this->blog_status = trans('fail');
				$this->blog_anchor_message = trans('The given anchor text does not found inside the content.');
			}
			else{
				$this->blog_status = "";
				$this->blog_anchor_message = "";
				$this->blog_url_string = ' <span class="text-href">href</span><span class="text-h">=</span><span class="text-string">"'.prefix_http($this->blog_url).'"</span>';
			}
		}
		else{
			$this->blog_url_string = '';
		}

		($this->blog_follow == 2) ? 'nofollow"': "follow";

        //$this->blog_blank_string = ($this->blog_blank == '_blank') ? '<span class="text-href">target</span><span class="text-h">=</span><span class="text-string">"_blank"</span>' : '';

		$string = '<span class="text-h"><</span><span class="text-h">a</span>'.$this->blog_url_string.$this->blog_follow_string.$this->blog_title_string.$this->blog_blank_string.'>'.$this->blog_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>';

		//$folllow = ($this->blog_follow == 2) ? 'nofollow"': "follow";

		//$string_2 = '<a title="'.$this->blog_title.'" href="'.prefix_http($this->blog_url).'" rel="'.$folllow.'" >'.$this->blog_anchor.'</a>';

		$this->blog_section_selected = $this->str_replace_first($this->blog_anchor, $string, $this->blog_section_first);
		//$this->blog_preview = $this->str_replace_first($this->blog_anchor, $string_2, $this->blog_section_first);

		$this->clean_section_blog();
	}

	public function updatedBlogfollow(){

		if (!empty($this->blog_anchor)) {
			$isset = strpos($this->blog_section_selected, $this->blog_anchor);
			if (!$isset) {
				$this->blog_status = "fail";
				$this->blog_anchor_message = "The given anchor text does not found inside the content.";
				$this->blog_section_selected = $this->blog_section_first;
			}
			else{
				$this->blog_status = "";
				$this->blog_anchor_message = "";
				$this->blog_follow_string = ($this->blog_follow == 2) ? ' <span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"nofollow"</span>' : '<span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"follow"</span>';

				$string = '<span class="text-h"><</span><span class="text-h">a</span>'.$this->blog_url_string.$this->blog_follow_string.$this->blog_title_string.$this->blog_blank_string.'>'.$this->blog_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>';
				$this->blog_section_selected = $this->str_replace_first($this->blog_anchor, $string, $this->blog_section_first);
			}
		}

		$this->clean_section_blog();
	}

    public function updatedBlogBlank(){
        if (!empty($this->blog_anchor)) {
            $isset = strpos($this->blog_section_selected, $this->blog_anchor);
            if (!$isset) {
                $this->blog_status = "fail";
                $this->blog_anchor_message = "The given anchor text does not found inside the content.";
                $this->blog_section_selected = $this->blog_section_first;
            }
            else{
                $this->blog_status = "";
                $this->blog_anchor_message = "";
                $this->blog_blank_string = ($this->blog_blank == '_blank') ? ' <span class="text-href">target</span><span class="text-h">=</span><span class="text-string">"_blank"</span>' : '';

                $string = '<span class="text-h"><</span><span class="text-h">a</span>'.$this->blog_url_string.$this->blog_follow_string.$this->blog_title_string.$this->blog_blank_string.'>'.$this->blog_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>';
                $this->blog_section_selected = $this->str_replace_first($this->blog_anchor, $string, $this->blog_section_first);
            }
        }

        $this->clean_section_blog();
    }

	public function updatedBloganchor(){
		if (!empty($this->blog_anchor)) {
			$isset = strpos($this->blog_section_selected, $this->blog_anchor);
			if (!$isset) {
				$this->blog_status = "fail";
				$this->blog_anchor_message = "The given anchor text does not found inside the content.";
				$this->blog_section_selected = $this->blog_section_first;
			}
			else{
				$this->blog_status = "";
				$this->blog_anchor_message = '';
				$this->blog_follow_string = ($this->blog_follow == 2) ? ' <span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"nofollow"</span>' : ' <span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"follow"</span>';

				$string = '<span class="text-h"><</span><span class="text-h">a</span>'.$this->blog_url_string.$this->blog_follow_string.$this->blog_title_string.$this->blog_blank_string.'>'.$this->blog_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>';
				$this->blog_section_selected = $this->str_replace_first($this->blog_anchor, $string, $this->blog_section_first);
			}
		}
		else{
			$this->blog_status = "";
			$this->blog_anchor_message = "";
			$this->blog_section_selected = $this->blog_section_first;
		}
		$this->clean_section_blog();
	}

	private function str_replace_first($from, $to, $content){
		$from = '/'.preg_quote($from, '/').'/';
		return preg_replace($from, $to, $content, 1);
	}

	public function updatedBlogtitle(){
		if (!empty($this->blog_anchor)) {
			$isset = strpos($this->blog_section_selected, $this->blog_anchor);
			if (!$isset) {
				$this->blog_status = "fail";
				$this->blog_anchor_message = "The given anchor text does not found inside the content.";
			}
			else{
				$this->blog_status = "";
				$this->blog_anchor_message = "";
				$this->blog_title_string = ' <span class="text-href">title</span><span class="text-h">=</span><span class="text-string">"'.$this->blog_title.'"</span>';
			}
		}
		else{
			$this->blog_title_string = '';
		}
		$string = '<span class="text-h"><</span><span class="text-h">a</span>'.$this->blog_url_string.$this->blog_follow_string.$this->blog_title_string.$this->blog_blank_string.'>'.$this->blog_anchor.'<span class="text-h"><</span><span class="text-h">/a</span><span class="text-h">></span>';
		$this->blog_section_selected = $this->str_replace_first($this->blog_anchor, $string, $this->blog_section_first);
		$this->clean_section_blog();
	}

	public function updatedStartingurl(){
		$this->url_starting_string = (empty($this->starting_url)) ? '' : 'href="'.prefix_http($this->starting_url).'"';
		$this->starting_url_preview = "<a ".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string." ".$this->blank_starting_string.">".$this->anchor_starting_string."</a>";
	}

	public function updatedStartingfollow(){
		$this->follow_starting_string = ($this->starting_follow == 2) ? 'rel="nofollow"' : 'rel="follow"';
		$this->starting_url_preview = "<a ".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string." ".$this->blank_starting_string.">".$this->anchor_starting_string."</a>";
	}

    public function updatedStartingBlank(){
        $this->blank_starting_string = ($this->starting_blank == '_blank') ? 'target="_blank"' : '';
        $this->starting_url_preview = "<a ".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string." ".$this->blank_starting_string.">".$this->anchor_starting_string."</a>";
    }

	public function updatedStartinganchor(){
		$this->anchor_starting_string = (empty($this->starting_anchor)) ? '' : $this->starting_anchor;
		$this->starting_url_preview = "<a ".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string." ".$this->blank_starting_string.">".$this->anchor_starting_string."</a>";
	}

	public function updatedStartingtitle(){
		$this->title_starting_string = (empty($this->starting_title)) ? '' : 'title="'.$this->starting_title.'"';
		$this->starting_url_preview = "<a ".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string." ".$this->blank_starting_string.">".$this->anchor_starting_string."</a>";
	}

	public function updatedFollow(){
		$this->follow_string = ($this->follow == 2) ? 'rel="nofollow"' : 'rel="follow"';
		$this->url_preview = "<a ".$this->url_string." ".$this->follow_string." ".$this->title_string." ".$this->blank_string.">".$this->anchor_string."</a>";
	}

    public function updatedBlank(){
        $this->blank_string = ($this->blank == '_blank') ? 'target="_blank"' : '';
        $this->url_preview = "<a ".$this->url_string." ".$this->follow_string." ".$this->title_string." ".$this->blank_string.">".$this->anchor_string."</a>";
    }

	public function updatedLinkurl(){
		$this->url_string = (empty($this->link_url)) ? '' : 'href="'.prefix_http($this->link_url).'"';
		$this->url_preview = "<a ".$this->url_string." ".$this->follow_string." ".$this->title_string." ".$this->blank_string.">".$this->anchor_string."</a>";
	}

	public function updatedLinktitle(){
		$this->title_string = (empty($this->linktitle)) ? '' : 'title="'.$this->linktitle.'"';
		$this->url_preview = "<a ".$this->url_string." ".$this->follow_string." ".$this->title_string." ".$this->blank_string.">".$this->anchor_string."</a>";
	}

	public function updatedAnchor(){
		$this->anchor_string = (empty($this->anchor)) ? '' : $this->anchor;
		$this->url_preview = "<a ".$this->url_string." ".$this->follow_string." ".$this->title_string." ".$this->blank_string.">".$this->anchor_string."</a>";
	}

	public function cancelStartingpage(){
		$this->startingpageactive = 'noshow';
		self::resetStartingpageInput();
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function cancelLink(){
		$this->linkactive = 'noshow';
		self::resetLinkInputs();
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function cancelBlog(){
		$this->blogactive = 'noshow';
		self::resetBlogInputs();
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function cancelStartingArticle(){
		$this->startingarticleactive = 'noshow';
		self::resetStartingArticlesInput();
		$this->dispatchBrowserEvent('hideFormcontent');
	}

	public function cancelPost(){
		$this->formactive = 'noshow';
		$this->list_ul = '';
		self::resetArticlesInput();
		$this->dispatchBrowserEvent('hideFormcontent');
	}

    public function cancelRequestStartpage(){
        $this->formrequeststartpage = 'noshow';
        self::resetStartingArticlesInput();
        $this->dispatchBrowserEvent('hideFormcontent');
    }

    public function cancelRequestBlog(){
        $this->formrequestblog = 'noshow';
        $this->list_ul = '';
        self::resetArticlesInput();
        $this->dispatchBrowserEvent('hideFormcontent');
    }

	private function resetStartingpageInput(){
		$this->site_startingpage = '';
		$this->categories_startingpage = [];
		$this->starting_url = '';
		$this->permanent_link_starting = 0;
		$this->starting_follow = 'rel="follow"';
        $this->starting_blank = '';
		$this->starting_anchor = '';
		$this->starting_title = '';
		$this->starting_description = '';
		$this->starting_url_preview = '';
		$this->expired_startingpage = '';
		$this->yearstartingpage = '';
		$this->section_startingpage = '';
	}

	private function resetLinkInputs(){
		$this->site_links    = '';
		$this->linktitle     = '';
		$this->link_url      = '';
		$this->expired_link  = '';
		$this->yearslink     = '';
		$this->follow        = '';
        $this->blank         = '';
		$this->anchor        = '';
		$this->section       = '';
		$this->url_preview   = '';
		$this->url_string    = '';
		$this->follow_string = 'rel="follow"';
        $this->blank_string  = '';
		$this->title_string  = '';
		$this->anchor_string = '';
		$this->permanent_link_link = 0;
	}

	private function resetBlogInputs(){
		$this->blog_section 			= 0;
		$this->blog_section_selected 	= '';
		$this->blog_url    	 			= '';
		$this->blog_title     			= '';
		$this->blog_follow      		= 1;
        $this->blog_blank      		    = '';
		$this->blog_anchor     			= '';
		$this->blog_years    			= '';
		$this->blog_expired  			= '';
		$this->blog_section_selected 	= '';
		$this->blog_section_first 		= '';
		$this->blog_url_string 			= '';
		$this->blog_title_string 		= '';
		$this->blog_anchor_string 		= '';
		$this->blog_follow_string = ($this->blog_follow == 2) ? '<span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"nofollow"</span>' : '<span class="text-href">rel</span><span class="text-h">=</span><span class="text-string">"follow"</span>';
        $this->blog_blank_string        = '';
	}

	private function resetStartingArticlesInput(){
		$this->startingarticleactive = 'noshow';
		$this->status_starting_article = '';
		$this->message_starting_article = '';
		$this->article_starting_default = '';
		$this->article_starting_site = '';
		$this->article_starting_url = '';
		$this->article_starting_title = '';
		$this->article_starting_expired = '';
		$this->article_starting_years = '';
		$this->article_starting_description = '';
		$this->article_starting_content = '';
		$this->article_starting_sections = [];
		$this->permanent_link_starting_article = 0;
		$this->article_starting_selected = '';
		$this->article_starting_categories_input = '';
		$this->img_outstanding_article = [];
		$this->list_ul_article = '';
		$this->starting_article_image = [];
	}

	private function resetArticlesInput(){
		$this->authority_site   = '';
		$this->categories       = [];
		$this->image            = '';
		$this->article_url      = '';
		$this->articletitle     = '';
		$this->description      = '';
		$this->expired_article  = '';
		$this->article_image 	= [];
		$this->img_outstanding 	= [];
	}

	private function resetRequestedArticlesInput() {
        $this->request_title       = '';
        $this->request_description = '';
        $this->request_texts       = [];
        $this->request_urls        = [];
	}

	private function resetArticlesInputFields() {
        $this->blog_site             = '';
        $this->blog_section          = '';
        $this->blog_section_selected = '';
        $this->blog_url              = '';
        $this->blog_title            = '';
        $this->blog_follow           = '';
        $this->blog_anchor           = '';
        $this->blog_years            = '';
        $this->blog_expired          = '';
    }

	public function changelink($link){
		if (!is_null($link)) {
			$this->site_links = $link;
			$this->section = '';
			$this->siteLinkCategories();
		}
	}

	public function changestartingpage($startingpage_id){
		if (!is_null($startingpage_id)) {
			$this->site_startingpage = $startingpage_id;
			$this->section_startingpage = '';
			$this->startingPageCategories();
		}
	}

	public function changeblog($blog_id){
		$this->blog_site = $blog_id;
		self::resetBlogInputs();
		$this->blog_default = $blog_id;
		$doc = new \DOMDocument();
		@$doc->loadHTML(AuthoritySite::findOrFail($blog_id)->preview);
		$num_article = $doc->getElementsByTagName("article");
		$this->blog_section_num = $num_article->length;
		$this->clean_section_blog();
	}

	public function changesectionblog($string, $section_id){
		$this->blog_section = $section_id;
		$this->blog_section_selected = $string;
	}

	public function changearticle($article_id){
		if (!empty($article_id)) {
			$this->authority_site = $article_id;
			$this->article_default = $article_id;
			$this->article_starting_site = $article_id;
			$this->img_outstanding = [];
			$this->categories_input = '';
			$this->article_image = '';
			$this->articletitle = '';
			$this->description = '';
			$this->article_url = '';
			$this->yearsarticle = '';
			$this->categories_article = '';
			$this->permanent_link_article = 0;
			$this->articleStartingCategories();
			$this->articleCategories();
			$this->dispatchBrowserEvent('hideFormcontent');
		}
	}

	public function datelink($date) {
        $this->publication_date = $date;
		if (empty($date)) {
			$this->message = '';
			$this->expired_link = '';
		}
		elseif ($date < date("d/m/Y")) {
			$this->message = 'error_date_link';
			$this->custom_error = trans('It cannot be a previous date');
		}
		else{
			$this->message = '';
			$this->expired_link = $date;
		}
	}

	public function datearticle($date) {
        $this->publication_date = $date;
		if (empty($date)) {
			$this->message = '';
			$this->expired_article = '';
		}
		elseif ($date < date("d/m/Y")) {
			$this->message = 'error_date_article';
			$this->custom_error = trans('It cannot be a previous date');
		}
		else{
			$this->message = '';
			$this->expired_article = $date;
		}
	}

	public function changeEvent($value){
        $this->dispatchBrowserEvent('changeYearsStartingPage');
    }

	public function dateStartingpage($date){
	    $this->publication_date = $date;
		if (empty($date)) {
			$this->message = '';
			$this->expired_startingpage = '';
		}
		elseif ($date < date("d/m/Y")) {
			$this->message = 'error_date_article';
			$this->custom_error = trans('It cannot be a previous date');
		}
		else{
			$this->message = '';
			$this->expired_startingpage = $date;
		}
	}

	public function datestartingarticle($date){
        $this->publication_date = $date;
		if (empty($date)) {
			$this->message = '';
			$this->article_starting_expired = '';
		}
		elseif ($date < date("d/m/Y")) {
			$this->message = 'error_date_article';
			$this->custom_error = trans('It cannot be a previous date');
		}
		else{
			$this->message = '';
			$this->article_starting_expired = $date;
		}
	}

	public function dateblog($date){
        $this->publication_date = $date;
		if (empty($date)) {
			$this->message = '';
			$this->blog_expired = '';
		}
		elseif ($date < date("d/m/Y")) {
			$this->message = 'error_date_article';
			$this->custom_error = trans('It cannot be a previous date');
		}
		else{
			$this->message = '';
			$this->blog_expired = $date;
		}
	}

	public function mount() {
		$this->title = trans('Buylinks');
		$this->tab = 'startpage';
		$this->url_preview = "<a href=".$this->url_string." ".$this->follow_string." ".$this->linktitle.">".$this->link_url."</a>";
		$this->starting_url_preview = "<a href=".$this->url_starting_string." ".$this->follow_starting_string." ".$this->title_starting_string.">".$this->anchor_starting_string."</a>";
		$this->menu = trans('Buy links');
		$this->startpage_link_list		= AuthoritySite::selectType('all')->get();
		$this->blog_sidebar_list 		= AuthoritySite::selectTypeWordpress('sidebar')->get();
		$this->blog_content_list		= AuthoritySite::selectType('onlyhomepage')->get();
		$this->startpage_article_list	= AuthoritySite::selectType('all')->get();
		$this->blog_article_list 		= AuthoritySite::selectTypeWordpress('article')->get();
        $this->max_links                = ArticleRule::max_links();
        $this->requested_articles       = General::requested_articles();
	}

	public function startingPageCategories(){
		$this->categories_startingpage = AuthoritySite::category_by_authority_site($this->site_startingpage);
		$this->permanent_link_starting = !empty($this->categories_startingpage) && count($this->categories_startingpage) >= 1 ? ($this->categories_startingpage[0]->permanent != null ? $this->categories_startingpage[0]->permanent : 0 ) : 0;
		if($this->permanent_link_starting == 1)
			$this->yearstartingpage = -5;
	}

	public function siteLinkCategories(){
		$this->categories_links = Wordpress::category_by_wordpress($this->site_links);
		$this->permanent_link_link = !empty($this->categories_links) && count($this->categories_links) >= 1 ? ($this->categories_links[0]->permanent !=  null ? $this->categories_links[0]->permanent : 0) : 0;
		if($this->permanent_link_link == 1)
			$this->yearslink = -5;
	}

	public function articleStartingCategories(){
		$this->article_starting_sections = AuthoritySite::category_by_authority_site($this->article_starting_site);
		$this->permanent_link_starting_article = !empty($this->article_starting_sections) && count($this->article_starting_sections) >= 1 ? ($this->article_starting_sections[0]->permanent != null ? $this->article_starting_sections[0]->permanent : 0) : 0;
		if($this->permanent_link_starting_article == 1)
			$this->article_starting_years = -5;
	}

	public function articleCategories(){
		$this->categories_article = Wordpress::category_by_wordpress($this->authority_site);
		$this->permanent_link_article = !empty($this->categories_article) && count($this->categories_article) >= 1 ? ($this->categories_article[0]->permanent != null ? $this->categories_article[0]->permanent : 0) : 0;
		if($this->permanent_link_article == 1)
			$this->yearsarticle = -5;
	}

	public function render(){
		$this->error_flag = false;
	    if (!empty($this->site_startingpage)) {
			$this->startingPageCategories();
		}
		if (!empty($this->blog_site)) {
			$this->categories_blog = AuthoritySite::category_by_authority_site($this->blog_site);
			$this->permanent_link_blog = !empty($this->categories_blog) && count($this->categories_blog) >= 1 ? $this->categories_blog[0]->permanent : 0;
			if($this->permanent_link_blog == 1) //Opcion que se escondio del front
				$this->blog_years = -5;
		}
		if (!empty($this->site_links)) {
			$this->siteLinkCategories();
		}

		if (!empty($this->article_starting_site)) {

			$this->articleStartingCategories();

			$search = '';

			/*if ($this->article_starting_selected != '') {
				$search = Category::category_by_id($this->article_starting_selected);
			}*/
			if($this->article_starting_categories_input != ''){
				$search = $this->article_starting_categories_input;
			}
			/*elseif(!empty($this->article_starting_sections) && isset($this->article_starting_sections[0]) && $this->article_starting_selected == '') {
				$this->article_starting_selected = $this->article_starting_sections[0]->id;
				$search = $this->article_starting_sections[0]->name;
			}*/

			if ($search != '') {
				$headers = [
					'Authorization' => "563492ad6f91700001000001588b579fcdc1486e88b06b5fd3ce8062",
				];
				$list_img = json_decode($this->client->get(
					'https://api.pexels.com/v1/search?query='.$search.'&per_page=20',
					['headers' => $headers ]
				)->getBody());
				$this->starting_article_image = $list_img->photos;
			}
			else{
				$this->starting_article_image = [];
			}


		}

		if (!empty($this->authority_site)) {

			$search = '';
			$this->articleCategories();

			/*if ($this->categories != '') {
				$search = Category::category_by_id($this->categories);
			}*/
			if($this->categories_input != ''){
				$search = $this->categories_input;
			}
			/*elseif(!empty($this->categories_article) && isset($this->categories_article[0]) && $this->categories == '') {
				$this->categories = $this->categories_article[0]->id;
				$search = $this->categories_article[0]->name;
			}*/


			if ($search != '') {

				$list_img = [];

				$headers = [
					'Authorization' => "563492ad6f91700001000001588b579fcdc1486e88b06b5fd3ce8062",
				];

				$list_img = json_decode($this->client->get(
					'https://api.pexels.com/v1/search?query='.$search.'&per_page=20',
					['headers' => $headers ]
				)->getBody());

				$this->article_image = $list_img->photos;
			}
			else{
				$this->article_image = [];
			}
		}

		$table_startpage_link    = [];
        $table_blog_sidebar_link = [];
        $table_blog_content_link = [];
        $table_startpage_article = [];
        $table_blog_article      = [];
		$this->language = $this->language == 0 ? null : $this->language;

        switch ($this->tab) {
            case 'startpage':
                $table_startpage_link = AuthoritySite::selectType('all', $this->language, $this->search)
                    // ->filter($this->search)
                    ->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate($this->perPage);
                break;
            case 'article':
                $table_startpage_article = AuthoritySite::selectType('all', $this->language, $this->search)
                    // ->filter($this->search)
                    ->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate($this->perPage);
                break;
            case 'sidebar':
                $table_blog_sidebar_link = AuthoritySite::selectTypeWordpress('sidebar',$this->language)
                    ->filterwordpress($this->search)
                    ->orderBy($this->sortBy, $this->sortDirection)
                    ->groupBy('wordpress.id')
                    ->paginate($this->perPage);
                break;
			//Se oculto de las opciones
            case 'content':
                $table_blog_content_link = AuthoritySite::selectType('blog-content', $this->language, $this->search)
                    // ->filter($this->search)
                    ->orderBy($this->sortBy, $this->sortDirection)
                    ->paginate($this->perPage);
                break;
			///////////////////////////////
            case 'blog':
                $table_blog_article = AuthoritySite::selectTypeWordpress('article', $this->language)
                    ->filterwordpress($this->search)
                    ->orderBy($this->sortBy, $this->sortDirection)
                    ->groupBy('wordpress.id')
                    ->paginate($this->perPage);

                	//dd($table_blog_article);
                break;
        }

        $this->invoce = User::invoice();

		return view('livewire.account.buylinks', [
            'table_startpage_link'     => $table_startpage_link,
            'table_blog_sidebar_link'  => $table_blog_sidebar_link,
            'table_blog_content_link'  => $table_blog_content_link,
            'table_startpage_article'  => $table_startpage_article,
            'table_blog_article'       => $table_blog_article,
		])->layout('layouts.account', ['title' => $this->title, 'menu' => $this->menu]);
	}

	public function sortBy($field){
		if ($this->sortDirection == 'asc') {
			$this->sortDirection = 'desc';
		} else {
			$this->sortDirection = 'asc';
		}
		return $this->sortBy = $field;
	}

	public function updatingSearch(){
		$this->resetPage();
	}
}
