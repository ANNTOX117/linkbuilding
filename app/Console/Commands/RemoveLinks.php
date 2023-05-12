<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Wordpress;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveLinks extends Command {

    protected $signature = 'remove:links';

    protected $description = 'Remove links from expired articles';

    public $client;

    public function __construct() {
        parent::__construct();
        $this->client = new \GuzzleHttp\Client();
    }

    public function handle() {
        $total    = 0; // Counter for edited articles
        $days     = 10; // How many days after the expiration date?
        $limit    = Carbon::now()->subDays($days)->format('Y-m-d'); // Limit date, with the limit days
        $articles = Article::to_remove($limit); // Expired articles that exceed the expired days limit

        if(!empty($articles)) {
            foreach($articles as $article) {
                if(!empty($article->authority_sites) and !empty($article->authority_sites->wordpress)) {
                    $wordpress = Wordpress::find($article->authority_sites->wordpress);

                    if(!empty($wordpress) and !empty($wordpress->username) and !empty($wordpress->password)) {
                        // Check if domain exists
                        $domain = is_valid_url($wordpress->url);

                        if($domain) {
                            // We request authorization through the API with the user and password of the WP site
                            $response = json_decode($this->client->post($wordpress->url . '/wp-json/api-bearer-auth/v1/login', [
                                \GuzzleHttp\RequestOptions::JSON => [
                                    'username' => $wordpress->username,
                                    'password' => do_decrypt($wordpress->password),
                                ],
                            ])->getBody());

                            // If we receive an authorization token
                            if(isset($response->access_token)) {
                                $headers = [
                                    'Authorization' => 'Bearer ' . $response->access_token,
                                    'Accept'        => 'application/json',
                                ];

                                // We get the information of the existing article by the slug
                                $validate = json_decode($this->client->get($wordpress->url . '/wp-json/wp/v2/posts?slug=' . $article->url, ['headers' => $headers, 'form_params'])->getBody());

                                // The article exists on the WP site?
                                if(!empty($validate)) {
                                    if(is_numeric(@$validate[0]->id)) {
                                        // Then we get the ID of the article, this ID is on the WP site, not on the articles table
                                        $article_id  = $validate[0]->id;
                                        // And with the ID of the article, we can edit the article, we send same values, edited content without links
                                        $publication = json_decode($this->client->post($wordpress->url . '/wp-json/wp/v2/posts/' . $article_id, ['headers' => $headers, 'form_params' => self::details($wordpress->url, $article->toArray())])->getBody());

                                        // If the update is successful, we update the article on the articles table, we increase counter
                                        if(!empty($publication)) {
                                            Article::no_published($article->id);
                                            $total++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        $this->line($total . ' updated articles');
    }

    /*
     * We create an array with the parameters that the API needs with the values of the article
     */
    private function details($url, $data){
        return [
            'title'   => $data['title'],
            'content' => self::remove_links($data['description']),
            'status'  => 'publish',
            'excerpt' => get_excerpt($data['description']),
            'date'    => date("Y-m-d H:i:s"),
            'link'    => $url . prep_slash($data['url']),
        ];
    }

    /*
     * We receive the content of the article and remove all the links, keeping the anchor text
     */
    private function remove_links($text){
        return preg_replace('#<a.*?>([^>]*)</a>#i', '$1', $text);
    }

}
