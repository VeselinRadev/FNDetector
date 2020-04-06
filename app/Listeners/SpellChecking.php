<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mekras\Speller\Hunspell\Hunspell;
use Mekras\Speller\Source\StringSource;
use Cscheide\ArticleExtractor\ArticleExtractor;

class SpellChecking
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
    
    public function handle($event)
    {
        //$event->article->mistakes = SpellChecking::check($event->article->text);
        $event->article->mistakes = array();
    }
    
    private function check($text){
        $mistakenWord = array();
        $pspell = pspell_new('bg','','','utf-8',PSPELL_FAST);
        $text = SpellChecking::refactor($text);
        $words = explode(' ', $text);
        foreach ($words as $value) {
            if (!pspell_check($pspell, $value)){
                if(!SpellChecking::checkForName($value)){
                    if(SpellChecking::checkInDictionary($value)){
                        if(!SpellChecking::checkWhiteListedWords($value)){
                            array_push($mistakenWord, $value);
                        }
                    }
                }
            }
        }
        $output = array();
        foreach(array_unique($mistakenWord) as $mistake){
            array_push($output, $mistake);
        }
        return $output;
    }
    
    public function refactor($text){
        $text = trim($text);
        $text = preg_replace('/[\00-\255]+/u', ' ', $text);
        $text = str_replace('“', ' ', $text);
        $text = str_replace('”', ' ', $text);
        $text = str_replace('„', ' ', $text);
        $text = str_replace('‟', ' ', $text);
        $text = str_replace('-', ' ', $text);
        $text = str_replace('.', ' ', $text);
        $text = str_replace(',', ' ', $text);
        $text = str_replace('*', ' ', $text);
        $text = str_replace('(', ' ', $text);
        $text = str_replace(')', ' ', $text);
        $text = str_replace(':', ' ', $text);
        $text = str_replace('?', ' ', $text);
        $text = str_replace('!', ' ', $text);
        $text = str_replace('0', ' ', $text);
        $text = str_replace('1', ' ', $text);
        $text = str_replace('2', ' ', $text);
        $text = str_replace('2', ' ', $text);
        $text = str_replace('3', ' ', $text);
        $text = str_replace('4', ' ', $text);
        $text = str_replace('5', ' ', $text);
        $text = str_replace('6', ' ', $text);
        $text = str_replace('7', ' ', $text);
        $text = str_replace('8', ' ', $text);
        $text = str_replace('9', ' ', $text);
        return $text;
    }
    
    private function checkForName($word){
        if(SpellChecking::isFirstUpper(mb_substr($word, 0, 1))){
            if(SpellChecking::isFirstUpper(mb_substr($word, 1, 1)))return false;
            else return true;
        }else return false;
    }
    
    private function isFirstUpper($str) {
        return preg_match('~^\p{Lu}~u', $str);
    }
    
    private function checkInDictionary($word){
        $dic = 'https://slovored.com/search/pravopisen-rechnik/';
        $extractor = new ArticleExtractor();
        $response = $extractor->processURL($dic.$word);
        return strpos($response["text"], "непозната дума");
    }
    
    private function checkWhiteListedWords($word){
        $whiteList = array('тир', 'коронавирус', 'коронавируса', 'коронавируси', 'коронавирусът', 'коронавирусите', 'коронавирусна', 'коронавирусен', 'коронавирусни', 'коронавирусно');
        return in_array($word, $whiteList);
    }
}