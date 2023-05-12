<?php

namespace App\Console\Commands;

use App\Models\Wordpress;
use Illuminate\Console\Command;
use App\Models\Externallink;
use App\Models\AuthoritySite;
use App\Models\Article;
use App\Models\Link;
use GuzzleHttp\Exception\RequestException;

class DownLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down:links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We change the status of the links and delete them from the pages';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {

        $links = Link::Unpublished()->get();
        $articles = Article::Unpublished()->get();

        if (!empty($links)) {
            foreach ($links as $link) {
                $authority = AuthoritySite::find($link->authority_site);
                $website   = get_wordpress_or_site($authority);
                if($website == 'wordpress') {
                    self::unpublish_link_on_wordpress($link, $authority);
                }else {
                    self::unpublish_link_on_site($link, $authority);
                }
            }
        }

        if (!empty($articles)) {
            foreach ($articles as $article) {
                $authority = AuthoritySite::find($article->authority_site);
                $website   = get_wordpress_or_site($authority);

                if($website == 'wordpress') {
                    self::unpublish_article_on_wordpress($article, $authority);
                } else {
                    self::unpublish_article_on_site($article, $authority);
                }
            }
        }
    }

    private function unpublish_link_on_wordpress($link, $authority) {

        if (is_valid_url($authority->url)) {

            $components = parse_url($link->external_url);

            try{

                $response = $this->client->get($authority->url.'/wp-admin/admin-ajax.php?action=wp_lb_api_services&service=get_wp_lb_links')->getBody();

                parse_str($components['query'], $results);

                $array_data = json_decode($results['links']);

                $remove = '<br><a href="'.$array_data[0]->link.'" target="_blank" title="'.$array_data[0]->linktitle.'" rel="'.$array_data[0]->follow.'">'.$array_data[0]->anchor.'</a>';

                $bodytag = str_replace($remove,"",$response);

                $this->client->post($components['host'].'/wp-admin/admin-ajax.php?action=wp_lb_api_services&service=update_all_links&all_links='.$bodytag);

                Link::no_published($link->id);

            }

            catch (RequestException $e) {
                Wordpress::error_site_wordpress($authority->wordpresses->id, 2);
            }
        }
    }

    private function unpublish_link_on_site($link, $authority){
        Link::no_published($link->id);
    }

    private function unpublish_article_on_wordpress($article, $authority){

        if(!empty(@$authority->wordpresses->username) and !empty(@$authority->wordpresses->password)) {

            $validate_domain = is_valid_url($authority->wordpresses->url);

            if($validate_domain) {

                try{

                    $response = json_decode($this->client->post($authority->url.'/wp-json/api-bearer-auth/v1/login', [
                        \GuzzleHttp\RequestOptions::JSON => [
                            'username' => $authority->wordpresses->username,
                            'password' => do_decrypt($authority->wordpresses->password),
                        ],
                    ])->getBody());

                    if (isset($response->access_token)) {

                        $headers = [
                            'Authorization' => 'Bearer ' . $response->access_token,
                            'Accept'        => 'application/json',
                        ];

                        $validate_page = json_decode($this->client->get($authority->url.'/wp-json/wp/v2/posts?slug=' . $article->url, ['headers' => $headers, 'form_params'])->getBody());

                        $post_wordpress = json_decode($this->client->delete(
                            $authority->url.'/wp-json/wp/v2/posts/'.$validate_page[0]->id
                            ,['headers' => $headers])->getBody());

                        Article::no_published($article->id);
                    }
                }

                catch (RequestException $e) {
                    Wordpress::error_site_wordpress($authority->wordpresses->id, 2);
                }
            }
        }
        else{
            Wordpress::error_site_wordpress($authority->wordpresses->id, 1);
        }
    }

    private function unpublish_article_on_site($article, $authority){
        Article::no_published($article->id);
    }
}
