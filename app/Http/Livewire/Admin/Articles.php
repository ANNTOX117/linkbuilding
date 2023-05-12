<?php

namespace App\Http\Livewire\Admin;

use App\Models\Article;
use App\Models\ArticleRequested;
use App\Models\ArticleRule;
use App\Models\AuthoritySite;
use App\Models\BadWordFilter;
use App\Models\Category;
use App\Models\Language;
use App\Models\MailingText;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Articles extends Component {

    use WithPagination;
    use WithFileUploads;
    public $title;
    public $section = 'articles';
    public $column_articles = 'created_at';
    public $column_requests = 'created_at';
    public $column_filters  = 'badword';
    public $sort    = 'asc';
    public $confirm;
    public $confirmRequested;
    public $confirmApprove;
    public $confirmAssign;
    public $tab = 'articles';
    public $table;
    public $edit_article = false;
    public $edit_rule = false;
    public $edit_id;
    public $articles_rules;
    public $article_id;
    public $name;
    public $description;
    public $content;
    public $active;
    public $authority_site;
    public $sites;
    public $rule_id;
    public $min_words;
    public $max_words;
    public $max_links;
    public $custom_error;
    public $word_id;
    public $word;
    public $edit = false;
    public $item;
    public $writers;
    public $assigned;
    public $is_automatic = false;
    public $suggestions;
    public $suggestion_url = [];
    public $suggestion_anchor = [];
    public $languages;
    public $categories;
    public $image;
    public $url;
    public $language;
    public $category;
    public $visible_at;
    public $ends_at;
    public $list_ul = '';
    public $article_starting_categories_input;
    public $meta_title;
    public $meta_description;
    public $keywords;

    public $image_array = [];
    public $image_article;
    public $image_search;

    public $photos = [];
    private $client;
    public $requested;

    public $pagination;
    public $search = '';

    public $request_article;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'clicked_image', 'changeimg'
    ];

    protected $rules = [
        'name'             => 'required|max:160',
        'description'      => 'required',
        'active'           => 'nullable',
        'authority_site'   => 'required|numeric',
        'min_words'        => 'required|numeric',
        'max_words'        => 'required|numeric',
        'max_links'        => 'required|numeric'
    ];

    public function __construct() {
        parent::__construct();
        $this->client = new \GuzzleHttp\Client();
    }

    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function updatedLanguage($language) {
        if(!is_null($language)) {
            $this->language   = $language;
            $this->categories = Category::by_language($language);
        }
    }

    public function updatedImagesearch($text) {

        if($text != '') {
            $headers = ['Authorization' => "563492ad6f91700001000001588b579fcdc1486e88b06b5fd3ce8062"];
            $list_img = json_decode($this->client->get(
                'https://api.pexels.com/v1/search?query='.$text.'&per_page=20',
                ['headers' => $headers ]
            )->getBody());

            $this->image_array = $list_img->photos;
        } else {
            $this->image_array = [];
        }
    }

    // public function clicked_image($image){
    //     $this->list_ul = 'active';
    //     if(!empty($image) and !empty($this->image_array)) {
    //         foreach($this->image_array as $item) {
    //             if(!empty($item)) {
    //                 if($item['id'] == $image) {
    //                     $this->image_article = $item['src']['small'];
    //                 }
    //             }
    //         }

    //         $this->list_ul = '';
    //     }

    //     $this->image_array = '';
    // }

    public function updatedVisibleAt($date) {
        $start_at = str_replace('/', '-', $date);
        $ends_at  = str_replace('/', '-', $this->ends_at);

        if(!empty($start_at) and !empty($ends_at) and ($start_at > $ends_at)) {
            $this->addError('visible_at', trans('The start date must be less than the end date'));
            return false;
        }
    }

    public function updatedEndsAt($date) {
        $start_at = str_replace('/', '-', $this->visible_at);
        $ends_at  = str_replace('/', '-', $date);

        if(!empty($start_at) and !empty($ends_at) and ($start_at > $ends_at)) {
            $this->addError('visible_at', trans('The start date must be less than the end date'));
            return false;
        }
    }

    public function mount() {
        if(!permission('articles', 'read')) {
            abort(404);
        }

        $this->title      = trans('Articles');
        $this->sites      = AuthoritySite::all_items();
        $this->languages  = Language::all();
        $this->pagination = env('APP_PAGINATE');
    }

    public function render() {
        $articles = Article::with_filter($this->column_articles, $this->sort, $this->pagination, $this->search);

        if(!empty($this->language)) {
            $this->categories = Category::by_language($this->language);
        }

        if(user_is_admin() or user_is_moderator()) {
            $requests = ArticleRequested::with_filter($this->column_requests, $this->sort, $this->pagination, $this->search);
        } else {
            $requests = ArticleRequested::assigned_with_filter($this->column_requests, $this->sort, $this->pagination, $this->search);
        }

        if(!empty($requests)) {
            foreach($requests as $i => $request) {
                $requests[$i]['editable'] = (!empty($request->id));
                //$requests[$i]['ready']    = (!empty($request->writer) and !empty($request->id) and !empty($request->authority_site) and !empty($request->url) and !empty($request->title) and !empty($request->description) and !empty($request->image) and !empty($request->visible_at) and !empty($request->expired_at) and !empty($request->language) and !empty($request->category));
                $requests[$i]['ready']    = (!empty($request->writer) and !empty($request->id) and !empty($request->authority_site) and !empty($request->url) and !empty($request->title) and !empty($request->description) and !empty($request->visible_at) and !empty($request->expired_at) and !empty($request->language) and !empty($request->category));
                $requests[$i]['type']     = get_wordpress_or_site($request->authority_sites);
                $requests[$i]['automatic']= ($requests[$i]['type'] == 'wordpress') ? @$request->authority_sites->wordpresses->automatic : @$request->authority_sites->sites->automatic;
            }
        }

        $filters = BadWordFilter::with_filter($this->column_filters, $this->sort, $this->pagination, $this->search);

        self::loadWriters();
        self::loadRules();

        return view('livewire.admin.articles', compact('articles', 'requests', 'filters'))->layout('layouts.panel');
    }

    public function table($table) {
        $this->tab = $table;

        $this->pagination = env('APP_PAGINATE');
        $this->search = '';

        if($this->tab == 'articles') {
            $this->column_articles = 'created_at';
        }

        if($this->tab == 'requests') {
            $this->column_requests = 'created_at';
        }

        if($this->tab == 'filters') {
            $this->column_filters = 'badword';
        }

        $this->resetPage();
    }

    public function sort($table, $column) {
        $this->sort = ($this->sort == 'asc') ? 'desc' : 'asc';

        if($table == 'articles') {
            $this->column_articles = $column;
        }

        if($table == 'requests') {
            $this->column_requests = $column;
        }

        if($table == 'filters') {
            $this->column_filters = $column;
        }
    }

    public function closeimage()
    {
        $this->list_ul = '';
    }

    public function modalAddArticle() {
        $this->edit = false;

        $this->image_array = [];
        $this->image_article = '';
        $this->image_search = '';

        self::resetArticlesInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddArticle');
    }

    public function modalAddWord() {
        $this->edit = false;
        self::resetFiltersInputFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showAddWord');
    }

    public function modalEditArticle($id) {
        $article = Article::find($id);
        $this->image_array = [];
        $this->image_article = '';
        $this->image_search = '';

        if(!empty($article)) {
            $this->suggestions      = false;
            $this->article_id       = $article->id;
            $this->url              = slugify($article->url);
            $this->meta_title       = $article->meta_title;
            $this->meta_description = $article->meta_description;
            $this->keywords         = $article->keywords;
            $this->name             = $article->title;
            $this->description      = $article->description;
            $this->content          = $article->description;
            $this->language         = $article->language;
            $this->category         = $article->category;
            $this->visible_at       = $article->visible_at;
            $this->ends_at          = $article->expired_at;
            $this->active           = $article->active;
            $this->authority_site   = $article->authority_site;
            $this->image_article    = $article->image;
            $this->resetErrorBag();
            $this->dispatchBrowserEvent('editDates', ['start' => datepicker_date($article->visible_at), 'end' => datepicker_date($article->expired_at)]);
            $this->dispatchBrowserEvent('showEditArticle', ['editor' => $this->content]);
        }

        $this->requested = false;
    }

    public function modalEditRequestedArticle($id, $editable = true) {
        if($editable) {
            $article = ArticleRequested::find($id);

            if($this->tab == 'requests') {
                $this->edit = true;
            }

            if(!empty($article)) {

                $this->suggestions      = true;
                $this->article_id       = $article->id;
                $this->name             = $article->title;
                $this->description      = $article->description;
                $this->content          = $article->description;
                $this->active           = $article->active;
                $this->authority_site   = $article->authority_site;
                $this->url              = slugify($article->url);
                $this->language         = $article->language;
                $this->category         = $article->category;
                $this->visible_at       = $article->visible_at;
                $this->ends_at          = $article->expired_at;
                $this->image_article    = $article->image;

                $this->request_article   = $article;
                $this->suggestion_url    = [];
                $this->suggestion_anchor = [];

                if(!empty($this->request_article)) {
                    $suggestion_url  = json_decode($this->request_article->suggested_url, true);
                    $suggestion_text = json_decode($this->request_article->suggested_anchor, true);

                    foreach($suggestion_url as $i => $a) {
                        $this->suggestion_url[]    = $a;
                        $this->suggestion_anchor[] = $suggestion_text[$i];
                    }
                }

                $this->resetErrorBag();
                $this->dispatchBrowserEvent('editDates', ['start' => datepicker_date($article->visible_at), 'end' => datepicker_date($article->expired_at)]);
                $this->dispatchBrowserEvent('showEditArticle', ['editor' => $this->content]);
            }
        } else {
            $this->custom_error = trans('The writer has not yet added the article');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }

        $this->requested = true;
    }

    public function modalEditWord($id) {
        $word = BadWordFilter::find($id);

        if(!empty($word)) {
            $this->word_id = $word->id;
            $this->word    = $word->badword;

            $this->resetErrorBag();
            $this->dispatchBrowserEvent('showEditWord');
        }
    }

    public function editRuleRow($id) {
        $this->edit_id   = $id;
        $this->edit_rule = true;

        $rule = ArticleRule::find($id);

        if(!empty($rule)) {
            $this->rule_id   = $rule->id;
            $this->min_words = $rule->min_words;
            $this->max_words = $rule->max_words;
            $this->max_links = $rule->max_links;
        }
    }

    public function saveRuleRow($id) {
        $data = $this->validate([
            'min_words' => 'required|numeric',
            'max_words' => 'required|numeric',
            'max_links' => 'required|numeric',
        ]);

        $rule = ArticleRule::find($id);

        if(!empty($rule)) {
            $rule->min_words = mysql_null($data['min_words']);
            $rule->max_words = mysql_null($data['max_words']);
            $rule->max_links = mysql_null($data['max_links']);
            $rule->save();
        }

        self::resetRulesInputFields();
        self::loadRules();
    }

    public function cancelRuleRow() {
        self::resetRulesInputFields();
    }

    public function addArticle() {
        $data = $this->validate([
            'url' => [
                'required',
                'max:255',
            ],
            'name'             => 'required|max:160',
            'meta_title'       => 'required',
            'meta_description' => 'required',
            'keywords'         => 'required',
            'description'      => 'required',
            'language'         => 'required|numeric',
            'category'         => 'required|numeric',
            'visible_at'       => 'required|date',
            'ends_at'          => 'required|date',
            'active'           => 'nullable',
            'authority_site'   => 'required|numeric',
            'image_article'    => 'required',
            "image_article"    => "mimes:jpeg,jpg,png,gif"
        ]);

        if(Article::checkIfUrlArticleExist(slugify($data['url']))) {
            return $this->addError('url', 'The URL already exists in the database.');
        }

        preg_match_all('!(https?:)?//\S+\.(?:jpe?g|png|gif)!Ui' , $data['image_article'], $image_url);

        $rules = ArticleRule::all_items();

        if(!empty($rules)) {
            if(count_iframe($data['description']) > 0) {
                $this->custom_error = trans('Iframe tag is not allowed in articles');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_script($data['description']) > 0) {
                $this->custom_error = trans('Script tag is not allowed in articles');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_words($data['description']) < $rules->min_words) {
                $this->custom_error = trans('Minimum of words allowed by article: ') . $rules->min_words;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_words($data['description']) > $rules->max_words) {
                $this->custom_error = trans('Maximum of words allowed by article: ') . $rules->max_words;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_links($data['description']) > $rules->max_links) {
                $this->custom_error = trans('Maximum of links allowed by article: ') . $rules->max_links;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(bad_words($data['description'], 'include')) {
                $this->custom_error = trans('The article contains bad words');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }
        }

        $active       = 2;
        $approved_at  = null;
        $published_at = null;

        if(get_bool($data['active'])) {
            $active       = 1;
            $approved_at  = Carbon::now();
            $published_at = Carbon::now();
        }


        $path = "public/blogs/image/";
        try {
            $nameImageBlog = "blog_image".time().".".$this->image_article->getClientOriginalExtension();
            $this->image_article->storeAs($path,$nameImageBlog);
        } catch (\Throwable $th) {
            dd($th);
        }
        $path = str_replace("public/","/storage/",$path);
        $url_file = $path.$nameImageBlog;
        Article::create([
            'url'              => mysql_null(slugify($data['url'])),
            'title'            => mysql_null($data['name']),
            'meta_title'       => mysql_null($data['meta_title']),
            'meta_description' => mysql_null($data['meta_description']),
            'keywords'          => mysql_null($data['keywords']),
            'title'            => mysql_null($data['name']),
            'description'      => mysql_null($data['description']),
            'language'         => mysql_null($data['language']),
            'category'         => mysql_null($data['category']),
            'image'            => $url_file,
            'visible_at'       => mysql_null($data['visible_at']),
            'expired_at'       => mysql_null($data['ends_at']),
            'active'           => $active,
            'authority_site'   => mysql_null($data['authority_site']),
            'approved_at'      => $approved_at,
            'published_at'     => $published_at
        ]);

        self::resetArticlesInputFields();
        session()->flash('successArticle', trans('Article succesfully created'));
        $this->dispatchBrowserEvent('hideAddArticle');
    }

    public function editArticle() {
        if($this->requested) {
            $data = $this->validate([
                'url'              => 'required|max:255',
                'name'             => 'required|max:160',
                'description'      => 'required',
                'meta_title'       => 'required',
                'meta_description' => 'required',
                'keywords'         => 'required',
                'language'         => 'required|numeric',
                'category'         => 'required|numeric',
                'visible_at'       => 'required|date',
                'ends_at'          => 'required|date',
                'authority_site'   => 'required|numeric',
                //'image_article'    => 'mimes:jpeg,jpg,png,gif',
            ]);
        } else {
            $data = $this->validate([
                'url'              => 'required|max:255',
                'name'             => 'required|max:160',
                'description'      => 'required',
                'meta_title'       => 'required',
                'meta_description' => 'required',
                'keywords'         => 'required',
                'language'         => 'required|numeric',
                'category'         => 'required|numeric',
                'visible_at'       => 'required|date',
                'ends_at'          => 'required|date',
                'active'           => 'nullable',
                'authority_site'   => 'required|numeric',
                //'image_article'    => 'mimes:jpeg,jpg,png,gif',
            ]);
        }

        if(!empty($rules)) {
            if(count_iframe($data['description']) > 0) {
                $this->custom_error = trans('Iframe tag is not allowed in articles');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_script($data['description']) > 0) {
                $this->custom_error = trans('Script tag is not allowed in articles');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_words($data['description']) < $rules->min_words) {
                $this->custom_error = trans('Minimum of words allowed by article: ') . $rules->min_words;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_words($data['description']) > $rules->max_words) {
                $this->custom_error = trans('Maximum of words allowed by article: ') . $rules->max_words;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(count_links($data['description']) > $rules->max_links) {
                $this->custom_error = trans('Maximum of links allowed by article: ') . $rules->max_links;
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }

            if(bad_words($data['description'], 'include')) {
                $this->custom_error = trans('The article contains bad words');
                $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
                return false;
            }
        }

        // preg_match_all('!(https?:)?//\S+\.(?:jpe?g|png|gif)!Ui' , $data['image_article'], $image_url);

        $active       = 2;
        $approved_at  = null;
        $published_at = null;

        if(get_bool($data['active'])) {
            $active       = 1;
            $approved_at  = Carbon::now();
            $published_at = Carbon::now();
        }
        $url_file = $this->image_article;
        if ($this->image_article instanceof \Livewire\TemporaryUploadedFile) {
            $path = "public/blogs/image/";
            try {
                $nameImageBlog = "blog_image".time().".".$this->image_article->getClientOriginalExtension();
                $this->image_article->storeAs($path,$nameImageBlog);
            } catch (\Throwable $th) {
                dd($th);
            }
            $path = str_replace("public/","/storage/",$path);
            $url_file = $path.$nameImageBlog;
        }
        if($this->requested) {
            
            $article = ArticleRequested::find($this->article_id);
            if(!empty($article)) {
                $article->url              = mysql_null($data['url']);
                $article->title            = mysql_null($data['name']);
                $article->description      = mysql_null($data['description']);
                $article->meta_title       = mysql_null($data['meta_title']);
                $article->meta_description = mysql_null($data['meta_description']);
                $article->keywords         = mysql_null($data['keywords']);
                $article->language         = mysql_null($data['language']);
                $article->category         = mysql_null($data['category']);
                $article->visible_at       = mysql_null($data['visible_at']);
                $article->expired_at       = mysql_null($data['ends_at']);
                $article->authority_site   = mysql_null($data['authority_site']);
                $article->image            = $url_file;//mysql_null($image_url[0][0]);
                $article->save();
            }

            self::resetArticlesInputFields();
            self::loadRequests();
        } else {
            $article = Article::find($this->article_id);

            if(!empty($article)) {
                $article->url              = mysql_null($data['url']);
                $article->title            = mysql_null($data['name']);
                $article->description      = mysql_null($data['description']);
                $article->meta_title       = mysql_null($data['meta_title']);
                $article->meta_description = mysql_null($data['meta_description']);
                $article->keywords         = mysql_null($data['keywords']);
                $article->language         = mysql_null($data['language']);
                $article->category         = mysql_null($data['category']);
                $article->visible_at       = mysql_null($data['visible_at']);
                $article->expired_at       = mysql_null($data['ends_at']);
                $article->active           = $active;
                $article->authority_site   = mysql_null($data['authority_site']);
                $article->image            = $url_file;//mysql_null($image_url[0][0]);
                if(empty($article->approved_at)) {
                    $article->approved_at  = $approved_at;
                }
                if(empty($article->published_at)) {
                    $article->published_at = $published_at;
                }
                $article->save();
            }

            self::resetArticlesInputFields();
            self::loadRequests();
        }

        session()->flash('successArticle', trans('Article succesfully edited'));
        $this->dispatchBrowserEvent('hideEditArticle');
    }

    public function addWord() {
        $data = $this->validate([
            'word' => 'required|max:50'
        ]);

        if(BadWordFilter::already_exists($data['word'])) {
            $this->custom_error = trans('This word already exists on the database');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }

        BadWordFilter::create([
            'badword' => mysql_null($data['word'])
        ]);

        self::resetFiltersInputFields();

        session()->flash('successWord', trans('Bad word succesfully created'));
        $this->dispatchBrowserEvent('hideAddWord');
    }

    public function editWord() {
        $data = $this->validate([
            'word' => 'required|max:50'
        ]);

        if(BadWordFilter::already_exists_on_edit($this->word_id, $data['word'])) {
            $this->custom_error = trans('This word already exists on the database');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }

        $word = BadWordFilter::find($this->word_id);

        if(!empty($word)) {
            $word->badword = mysql_null($data['word']);
            $word->save();
        }

        self::resetFiltersInputFields();

        session()->flash('successWord', trans('Bad word succesfully edited'));
        $this->dispatchBrowserEvent('hideEditWord');
    }

    public function confirmApprove($id, $ready = false, $automatic = false) {
        if($ready) {
            $this->confirmApprove = $id;
            $this->is_automatic   = $automatic;
            $this->dispatchBrowserEvent('confirmApprove');
        } else {
            $this->custom_error = trans('The article is not ready to be approved');
            $this->dispatchBrowserEvent('showError', ['message' => $this->custom_error]);
            return false;
        }
    }

    public function approve() {
        $requested = ArticleRequested::get_article($this->confirmApprove);

        if(!empty($requested)) {
            $article = new Article();
            $article->order            = $requested->order;
            $article->url              = $requested->url;
            $article->title            = $requested->title;
            $article->description      = $requested->description;
            $article->image            = $requested->image;
            $article->active           = 1;
            $article->visible_at       = $requested->visible_at;
            $article->expired_at       = $requested->expired_at;
            $article->authority_site   = $requested->authority_site;
            $article->language         = $requested->language;
            $article->category         = $requested->category;
            $article->approved_at      = Carbon::now();
            $article->published_at     = ($this->is_automatic) ? Carbon::now() : null;
            $article->client           = $requested->customer;
            $article->save();

            ArticleRequested::destroy($this->confirmApprove);

            $template = MailingText::template('Approved article', App::getLocale());

            if(!empty($template)) {
                $user    = User::find($requested->customer);
                $subject = replace_variables($template->name, $requested->customer);
                $content = replace_variables($template->description, $requested->customer);
                $name    = $user->name . ' ' . $user->lastname;
                $email   = $user->email;

                Mail::send('mails.template', ['content' => $content], function ($mail) use ($email, $name, $subject) {
                    $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                    $mail->to($email, $name)->subject($subject);
                });
            }
        }

        $this->confirmApprove = '';

        self::loadRequests();
    }

    public function assignWrite($id) {
        $this->confirmAssign = $id;
        $this->assigned      = ArticleRequested::writer_assigned($id);
        $this->writers       = User::select_writers(@$this->assigned);

        $this->dispatchBrowserEvent('confirmAssign');
        $this->dispatchBrowserEvent('resetWriters', ['options' =>  $this->writers]);
    }

    public function assign() {
        ArticleRequested::assign($this->confirmAssign, $this->assigned);

        $template = MailingText::template('Assigned article', App::getLocale());

        if(!empty($template)) {
            $user    = User::find($this->assigned);
            $subject = replace_variables($template->name, $this->assigned);
            $content = replace_variables($template->description, $this->assigned);
            $name    = $user->name . ' ' . $user->lastname;
            $email   = $user->email;

            Mail::send('mails.template', ['content' => $content], function ($mail) use ($email, $name, $subject) {
                $mail->from(env('APP_EMAIL'), env('APP_NAME'));
                $mail->to($email, $name)->subject($subject);
            });
        }

        $this->confirmAssign = '';
        $this->assigned      = '';

        self::loadRequests();
    }

    public function confirm($id) {
        if($this->tab == 'articles') {
            $this->item = trans('article');
        }

        if($this->tab == 'requests') {
            $this->item = trans('requested article');
        }

        if($this->tab == 'filters') {
            $this->item = trans('bad word');
        }

        $this->confirm = $id;
        $this->dispatchBrowserEvent('confirmDelete');
    }

    public function confirmRequest($id) {
        $this->confirmRequested = $id;
        $this->dispatchBrowserEvent('confirmDeleteRequest');
    }

    public function delete() {
        if($this->tab == 'articles') {
            Article::destroy($this->confirm);
        }

        if($this->tab == 'requests') {
            Article::destroy($this->confirm);
        }

        if($this->tab == 'filters') {
            BadWordFilter::destroy($this->confirm);
        }

        $this->confirm = '';
    }

    public function deleteRequest() {
        $article_id = ArticleRequested::get_article($this->confirmRequested);

        if(!empty($article_id)) {
            Article::destroy($article_id);
        }

        ArticleRequested::destroy($this->confirmRequested);

        self::loadRequests();

        $this->confirmRequested = '';
    }

    private function loadRequests() {
        $this->requests = ArticleRequested::all_items();

        if(!empty($this->requests)) {
            foreach($this->requests as $i => $request) {
                $this->requests[$i]['editable'] = (!empty($request->id));
                $this->requests[$i]['ready']    = (!empty($request->writer) and $request->paid == 1 and $request->payment == 'success' and !empty($request->id) and !empty($request->site));
            }
        }
    }

    private function loadWriters() {
        $this->writers = User::select_writers();
    }

    private function loadRules() {
        $this->articles_rules = ArticleRule::all_items();
    }

    private function resetArticlesInputFields() {
        $this->edit_id          = '';
        $this->edit_article     = false;
        $this->article_id       = '';
        $this->url              = '';
        $this->meta_title       = '';
        $this->meta_description = '';
        $this->keywords         = '';
        $this->name             = '';
        $this->description      = '';
        $this->content          = '';
        $this->language         = '';
        $this->category         = '';
        $this->visible_at       = '';
        //$this->expired_at       = '';
        $this->active           = '';
        $this->authority_site   = '';
        $this->image_array      = [];
        $this->image_article    = '';
        $this->image_search     = '';
    }

    private function resetRulesInputFields() {
        $this->edit_id   = '';
        $this->edit_rule = false;
        $this->rule_id   = '';
        $this->min_words = '';
        $this->max_words = '';
        $this->max_links = '';
    }

    private function resetFiltersInputFields() {
        $this->word_id = '';
        $this->word    = '';
    }

}
