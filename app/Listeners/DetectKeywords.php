<?php

namespace App\Listeners;

use App\Events\ArticleSearched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Article;


class DetectKeywords
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
     * @param  object  $event
     * @return void
     */
    public function handle($event){     
        $event->article->text = str_replace("\n", '', $event->article->text);
        if(null !== DetectKeywords::getKeywordsFromMeta($event->article->url)) {
            $event->article->keywordsSource = "meta";
            $event->article->keywords = DetectKeywords::getKeywordsFromMeta($event->article->url);
        }
        else {
            $event->article->keywordsSource = "alg";
            $event->article->keywords = DetectKeywords::callingRake($event);
        }
    }

    public function callingRake($event){
        //send the text of the article to rake.py
        //where the keywords are extracted
        $response = shell_exec('python '. 
            '/home/fndetect/fndetector/packages/multi_rake-master/multi_rake/rake.py 2>&1 '. $event->article->text);
        //outputs the keywords
        return DetectKeyWords::decodeText($response);
    }

    private function decodeText($text){
        //trims start and end of the words
        $text = substr($text, 2);
        $text = substr($text, 0, strlen($text)-2);
        //replace binary symbols with readable one
        $text = str_replace('\xd0\x90', 'А', $text);
        $text = str_replace('\xd0\x91', 'Б', $text);
        $text = str_replace('\xd0\x92', 'В', $text);
        $text = str_replace('\xd0\x93', 'Г', $text);
        $text = str_replace('\xd0\x94', 'Д', $text);
        $text = str_replace('\xd0\x95', 'Е', $text);
        $text = str_replace('\xd0\x96', 'Ж', $text);
        $text = str_replace('\xd0\x97', 'З', $text);
        $text = str_replace('\xd0\x98', 'И', $text);
        $text = str_replace('\xd0\x99', 'Й', $text);
        $text = str_replace('\xd0\x9a', 'К', $text);
        $text = str_replace('\xd0\x9b', 'Л', $text);
        $text = str_replace('\xd0\x9c', 'М', $text);
        $text = str_replace('\xd0\x9d', 'Н', $text);
        $text = str_replace('\xd0\x9e', 'О', $text);
        $text = str_replace('\xd0\x9f', 'П', $text);
        $text = str_replace('\xd0\xa0', 'Р', $text);
        $text = str_replace('\xd0\xa1', 'С', $text);
        $text = str_replace('\xd0\xa2', 'Т', $text);
        $text = str_replace('\xd0\xa3', 'У', $text);
        $text = str_replace('\xd0\xa4', 'Ф', $text);
        $text = str_replace('\xd0\xa5', 'Х', $text);
        $text = str_replace('\xd0\xa6', 'Ц', $text);
        $text = str_replace('\xd0\xa7', 'Ч', $text);
        $text = str_replace('\xd0\xa8', 'Ш', $text);
        $text = str_replace('\xd0\xa9', 'Щ', $text);
        $text = str_replace('\xd0\xaa', 'Ъ', $text);
        $text = str_replace('\xd0\xac', 'Ь', $text);
        $text = str_replace('\xd0\xae', 'Ю', $text);
        $text = str_replace('\xd0\xaf', 'Я', $text);
        $text = str_replace('\xd0\xb0', 'а', $text);
        $text = str_replace('\xd0\xb1', 'б', $text);
        $text = str_replace('\xd0\xb2', 'в', $text);
        $text = str_replace('\xd0\xb3', 'г', $text);
        $text = str_replace('\xd0\xb4', 'д', $text);
        $text = str_replace('\xd0\xb5', 'е', $text);
        $text = str_replace('\xd0\xb6', 'ж', $text);
        $text = str_replace('\xd0\xb7', 'з', $text);
        $text = str_replace('\xd0\xb8', 'и', $text);
        $text = str_replace('\xd0\xb9', 'й', $text);
        $text = str_replace('\xd0\xba', 'к', $text);
        $text = str_replace('\xd0\xbb', 'л', $text);
        $text = str_replace('\xd0\xbc', 'м', $text);
        $text = str_replace('\xd0\xbd', 'н', $text);
        $text = str_replace('\xd0\xbe', 'о', $text);
        $text = str_replace('\xd0\xbf', 'п', $text);
        $text = str_replace('\xd1\x80', 'р', $text);
        $text = str_replace('\xd1\x81', 'с', $text);
        $text = str_replace('\xd1\x82', 'т', $text);
        $text = str_replace('\xd1\x83', 'у', $text);
        $text = str_replace('\xd1\x84', 'ф', $text);
        $text = str_replace('\xd1\x85', 'х', $text);
        $text = str_replace('\xd1\x86', 'ц', $text);
        $text = str_replace('\xd1\x87', 'ч', $text);
        $text = str_replace('\xd1\x88', 'ш', $text);
        $text = str_replace('\xd1\x89', 'щ', $text);
        $text = str_replace('\xd1\x8a', 'ъ', $text);
        $text = str_replace('\xd1\x8c', 'ь', $text);
        $text = str_replace('\xd1\x8e', 'ю', $text);
        $text = str_replace('\xd1\x8f', 'я', $text);
        $text = str_replace('\xe2\x80\x9c', '“', $text);
        $text = str_replace('\xe2\x80\x9d', '”', $text);
        $text = str_replace('\xe2\x80\x9e', '„', $text);
        $text = str_replace('\xе2\x80\x9f', '‟', $text);
        $text = str_replace('\xe2\x80\x93', '-', $text);
        $text = trim($text);    
        return $text;
    }

    private function getKeywordsFromMeta($url){
        $tags = get_meta_tags($url);
        if(isset($tags['keywords'])) return $tags['keywords'];
        else return null;
    }
}


