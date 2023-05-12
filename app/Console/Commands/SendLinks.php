<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Externallink;
use App\Models\AuthoritySite;
use App\Models\Article;
use App\Models\Wordpress;
use Carbon\Carbon;
use File;

class SendLinks extends Command {

    protected $signature = 'send:links';

    protected $description = 'We send the scheduled posts';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $forapproval = Externallink::Published()->get();

        if (!empty($forapproval)) {
            foreach ($forapproval as $record) {

                $publish = ($record->authority_site != NULL) ?  AuthoritySite::get_automatic($record->authority_site) : Wordpress::findOrFail($record->wordpress)->automatic;

                if ($publish == 1) {
                    switch ($record->type) {
                        case 1://links
                            $client = new \GuzzleHttp\Client();
                            $response = $client->post($record->url);

                            if ($response) {
                                Externallink::where('id', $record->id)->update(['active' => 1, 'published_at' => Carbon::now()]);
                            }

                        break;
                        case 2://posts

                            $response = $this->posts($record->article, $record->url, $record->wordpress, $record->id);
                        break;
                    }
                }
            }
        }
    }

    private function posts($article_id, $url, $wordpress_id, $external_id) {

        $wordpress = Wordpress::findOrFail($wordpress_id);
        $client = new \GuzzleHttp\Client();

        if ($wordpress->username != '' && $wordpress->password != '') {

            $validate_domain = is_valid_url($wordpress->url);

            if ($validate_domain) {

                $response = json_decode($client->post($wordpress->url.'/wp-json/api-bearer-auth/v1/login', [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'username' => $wordpress->username,
                        'password' => do_decrypt($wordpress->password),
                    ],
                ])->getBody());

                if (isset($response->access_token)) {

                    $url_base = parse_url($wordpress->url);

                    $headers = [
                        'Authorization' => 'Bearer ' . $response->access_token,
                        'Accept'        => 'application/json',
                    ];

                    $article = Article::find($article_id);

                    $validate_page = json_decode($client->get($url_base['host'].'/wp-json/wp/v2/posts?slug='.$article->meta_title , [ 'headers' => $headers, 'form_params' ] )->getBody());

                    $message = [];

                    if (empty($validate_page)) {

                        $message['aproved'] = true;

                        $post_wordpress = json_decode(
                            $client->post(
                                $url_base['host'] . '/wp-json/wp/v2/posts/',
                                [
                                    'headers' => $headers,
                                    'form_params' => $this->format_post($url_base['host'], $article->toArray(),)
                                ])->getBody())->id;

                        $this->client->post($url.'/wp-admin/admin-ajax.php?action=wp_lb_api_services&service=update_wp_post&links=[{%22urlimage%22:%22'.$article->articleimages->image.'%22,%22post_id%22:%22'.$post_wordpress.'%22}]');

                        if ($message['aproved']) {

                            Externallink::where('id', $external_id)
                            ->update(['active' => 1, 'published_at' => Carbon::now(), 'url' => json_encode(
                                [   $url_base['host'],
                                    $url_base['host'].'/wp-json/wp/v2/posts/'.$post_wordpress,
                                ])
                            ]);
                        }
                    }
                }
                else{
                    Externallink::where('id', $external_id)->update(['active' => 5]);//fail credentials
                }
            }
        }
        else{

            Externallink::where('id', $external_id)->update(['active' => 4]);//empty credentials
        }

        return;
    }

    private function format_post($url, $data){
        $post_format = [
            'title'      => $data['title'],
            'content'    => $data['description'],
            'status'     => 'publish',
            'date'       => date("Y-m-d H:i:s"),
            'categories' => json_decode($data['meta_description']),
            'link'       => $url."/".$data['meta_title'],
        ];

        return $post_format;
    }
}
