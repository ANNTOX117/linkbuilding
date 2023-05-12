<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Link;
use Illuminate\Console\Command;

class AlreadyExists extends Command {

    protected $signature = 'check:links';

    protected $description = 'Check if the article or links already exists, if not, we can set error 404 for it';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $links    = Link::published_links();
        $articles = Article::published_articles();
        $total    = 0;

        if(!empty($links)) {
            foreach($links as $link) {
                if(self::already_exists($link->url)) {
                    Link::set_error($link->id, null);
                } else {
                    Link::set_error($link->id, 1);
                }
                $total++;
            }
        }

        if(!empty($articles)) {
            foreach($articles as $article) {
                if(self::already_exists($article->url)) {
                    Link::set_error($article->id, null);
                } else {
                    Link::set_error($article->id, 1);
                }
                $total++;
            }
        }

        $this->line($total . ' links and articles were updated');
    }

    private function already_exists($url) {
        if(getHttpResponseCode_using_curl($url) != 200){
            return false;
        }
        return true;
    }

}
