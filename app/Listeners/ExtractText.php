<?php

namespace App\Listeners;

use App\Events\ArticleSearched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Article;

//API for extracting text
use Cscheide\ArticleExtractor\ArticleExtractor;

class ExtractText
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArticleSearched  $event
     * @return void
     */

    public function handle(ArticleSearched $event)
    {
        $article = new Article();
        ExtractText::fillArcticle($event, $article);
    }

    //Extracting text
    private function readTextFromUrl($url){
        $extractor = new ArticleExtractor();
        $response = $extractor->processURL($url);
        return $response;
    }

    //constructing the data that should be passed to the next event
    private function fillArcticle(ArticleSearched $event, Article $article){
        $response = ExtractText::readTextFromUrl($event->article->url);

        $article->url = $event->article->url;

        $event->article->title = trim($response["title"]);
        $article->title = $event->article->title;

        $event->article->text = trim($response["text"]);
        $article->text = $event->article->text;
    }

}
 