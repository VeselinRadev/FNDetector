<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;
use App\Events\ArticleSearched;
use App\Events\DataCollected;

class ArticlesController extends Controller
{
    public function getInfo(Request $request)
    {
        //reads the url from the input
        $article = new Article();
        $article->url = $request->input('article_url'); 

        //calling event that article is passed to the server
        event(new ArticleSearched($article));  

        //building page for cropping the text
        return view('cropText')
            ->with('text', $article->text)
            ->with('url', $article->url)
            ->with('title', $article->title);
    }

    public function cropText(Request $request){
        //filling $article
        $article = new Article();
        $article->text = $request->input('article_text');
        $article->url = $request->input('article_url');
        $article->title = $request->input('article_title');

        //calling event that article has been cropped
        
        event(new DataCollected($article)); 
        return view('result')  
            ->with('title', $article->title)
            ->with('relevance', $article->relevance)
            ->with('textQuality', $article->textQuality)
            ->with('source', $article->sources)
            ->with('spelling', $article->spelling)
            ->with('urlStat', $article->urlStat);
        
    }
}
