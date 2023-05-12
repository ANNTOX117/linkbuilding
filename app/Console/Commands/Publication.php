<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\AuthoritySite;
use App\Models\Link;
use App\Models\Wordpress;
use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;

class Publication extends Command {

    protected $signature = 'publish:links';

    protected $description = 'Publish all approved links and approved articles';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $total_links    = 0;
        $total_articles = 0;
        $links          = Link::to_publish();
        $articles       = Article::to_publish();

        if(!empty($links)) {
            foreach($links as $link) {
                $authority = AuthoritySite::find($link->authority_site);
                $website   = get_wordpress_or_site($authority);

                if($website == 'wordpress') {
                    self::publish_link_on_wordpress($link, $authority);
                } else {
                    self::publish_link_on_site($link, $authority);
                }

                $total_links++;
            }
        }

        if(!empty($articles)) {
            foreach($articles as $article) {
                $authority = AuthoritySite::find($article->authority_site);
                $website   = get_wordpress_or_site($authority);

                if($website == 'wordpress') {
                    self::publish_article_on_wordpress($article, $authority);
                } else {
                    self::publish_article_on_site($article, $authority);
                }

                $total_articles++;
            }
        }

        $this->line($total_links . ' links has been published');
        $this->line($total_articles . ' articles has been published');
    }

    private function publish_link_on_site($link, $authority) {
        Link::published($link->id, $link->url);
    }

    private function publish_link_on_wordpress($link, $authority) {

        if (is_valid_url($authority->url)) {

            try{

                $external = $authority->url. "/wp-admin/admin-ajax.php?action=wp_lb_api_services&service=update_wp_lb_links&links=[{%22link%22:%22". $link->url ."%22,%22anchor%22:%22". $link->anchor ."%22,%22linktitle%22:%22". $link->alt ."%22,%22follow%22:%22". get_follow_or_not($link->follow) ."%22}]";
                $client   = new \GuzzleHttp\Client();
                $response = $client->post($external);

                Link::published($link->id, $external);
            }
            catch (RequestException $e) {
                Wordpress::error_site_wordpress($authority->wordpresses->id, 2);
            }
        }
    }

    private function publish_article_on_site($article, $authority) {
        $external = $authority->url .'/'. $article->url;
        Article::published($article->id, $external);
    }

    private function publish_article_on_wordpress($article, $authority) {
        $external = $authority->url .'/'. $article->url;
        $client   = new \GuzzleHttp\Client();

        if(!empty(@$authority->wordpresses->username) and !empty(@$authority->wordpresses->password)) {
            $validate_domain = is_valid_url($authority->wordpresses->url);

            if($validate_domain) {
                
                try{
                
                    $response = json_decode($client->post($authority->url.'/wp-json/api-bearer-auth/v1/login', [
                        \GuzzleHttp\RequestOptions::JSON => [
                            'username' => $authority->wordpresses->username,
                            'password' => do_decrypt($authority->wordpresses->password),
                        ],
                    ])->getBody());
                    
                    if(isset($response->access_token)) {

                        $headers = [
                            'Authorization' => 'Bearer ' . $response->access_token,
                            'Accept'        => 'application/json',
                        ];

                        $validate_page = json_decode($client->get($authority->url.'/wp-json/wp/v2/posts?slug=' . $article->url, ['headers' => $headers, 'form_params'] )->getBody());

                        if(empty($validate_page)) {

                            $post_wordpress = json_decode(
                                $client->post(
                                    $authority->url.'/wp-json/wp/v2/posts/',
                                    [
                                        'headers' => $headers,
                                        'form_params' => $this->format_post($authority->url, $article->toArray(),)
                                    ])->getBody())->id;

                            preg_match_all('!(https?:)?//\S+\.(?:jpe?g|png|gif)!Ui' , $article->image, $image_thum);

                            $client->post($authority->url.'/wp-admin/admin-ajax.php?action=wp_lb_api_services&service=update_wp_post&links=[{%22urlimage%22:%22'.$image_thum[0][0].'%22,%22post_id%22:%22'. $post_wordpress .'%22}]');
                        }
                    }

                    Article::published($article->id, $external);
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

    private function format_post($url, $data){
        return [
            'title'      => $data['title'],
            'content'    => $data['description'],
            'status'     => 'publish',
            'excerpt'    => get_excerpt($data['description']),
            'date'       => date("Y-m-d H:i:s"),
            'categories' => json_decode($data['meta_description']),
            'link'       => $url."/".$data['meta_title'],
        ];
    }
}
