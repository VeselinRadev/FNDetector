<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
class CheckTextQuality
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
       CheckTextQuality::output($event);
    }
    
    private function output($event){
        $event->article->suspiciousWords = CheckTextQuality::isTextContainingSuspiciousWords($event->article->title, $this->suspiciousWords);
        $event->article->upperCaseWords = CheckTextQuality::checkForUpperCaseWords($event);
        $event->article->dramaticPunctuation = CheckTextQuality::checkForDramaticPunctuation($event->article->text);
    }

    //check if the title contains one of the suspicious words
    private function isTextContainingSuspiciousWords($title, $wordList){
        $title = mb_convert_case($title, MB_CASE_LOWER, "UTF-8");
        $title = CheckTextQuality::getOnlyWord($title);
        $titleWords = explode(" ", $title); 
        $suspiciousWords = array();
        foreach($titleWords as $word){
            foreach($wordList as $suspiciousWord){
                if($word == $suspiciousWord){
                    array_push($suspiciousWords, $word);
                }
            }
        }
        return $suspiciousWords;
    }

    //check if the article has words that are supposed to grab readers attention and mislead him
    private function checkForUpperCaseWords($event){
        $text = $event->article->text;
        $words = explode(" ", $text);
        $upperCaseWord = array(); 
        foreach($words as $word){
            $word = CheckTExtQuality::getOnlyWord($word);
            if(CheckTextQuality::checkIsUpperCyrillic($word) && strlen($word)/2  > 1 && $word != "ВИДЕО" && $word != "СНИМКИ"){
                array_push($upperCaseWord, $word);
            }
        }
        $output = array();
        foreach(array_unique($upperCaseWord) as $mistake){
            array_push($output, $mistake);
        }
        $output = CheckTextQuality::checkForConjunction($output, $event->article->mistakes, $event);
        return $output;
    }
    
    private function checkForConjunction($arr1, $arr2, $event){
        foreach($arr1 as $item1){
            foreach($arr2 as $item2){
                if($item1 == $item2){
                    unset($arr1[array_search($item1, $arr1)]);
                    unset($arr2[array_search($item2, $arr2)]);
                }
            }
        }
        $arr1 = array_values($arr1);
        $arr2 = array_values($arr2);
        $event->article->mistakes = $arr2;
        return $arr1;
    }

    //check if the article has words with special punctuation that are supposed to grab readers attention and mislead him
    private function checkForDramaticPunctuation($text){
        $flag = false;
        $words = explode(" ", $text);
        foreach($words as $word){
            $i = 0;
            while($i < strlen($word)/2){
                 $c = mb_substr($word, $i, 1, 'UTF-8');
                 if($c == "!" || $c == "?"){
                    $nc = mb_substr($word, $i + 1, 1, 'UTF-8');
                    if($nc == "!" || $c == "?"){
                        $flag = true;
                        break;
                    }
                 }
                 $i++;
            }
            if($flag == true)break;
        }
        return $flag;
    }

    //chek if the word written with cyrillic characters is in upper case 
    private function checkIsUpperCyrillic($word){
        $word = CheckTExtQuality::getOnlyWord($word);
        $flag = true; 
        $i = 0;
        while($i < strlen($word)/2){
            $c = mb_substr($word, $i, 1, 'UTF-8');
            //Check if $c is capital letter: -- 1040 responds to А -- 1071 responds to Я
            if($c != null){
                if(!(mb_ord($c, 'UTF-8') >= 1040 && mb_ord($c, 'UTF-8') <= 1071)){
                    $flag = false;
                }
            }
            $i++;
        }
        return $flag;
    }

    //It processes the string and removes the punctuation 
    private function getOnlyWord($word){
        $word = trim($word);
        $word = str_replace("!", "", $word);
        $word = str_replace(".", "", $word);
        $word = str_replace("?", "", $word);
        $word = str_replace("-", "", $word);
        $word = str_replace(",", "", $word);
        $word = str_replace("“", "", $word);
        $word = str_replace("„", "", $word);
        $word = str_replace('"', "", $word);
        return $word;
    }

    //The words that can indicate fake news
    public $suspiciousWords = array( 
        "скандал",
        "внимание",
        "трагедия",
        "бомба",
        "18+",
        "сензация",
        "еврика",
        "изненада",
        "извънредно",
        "екшън",
        "горещо",
        "скандална",
        "скандално",
        "скандални",
        "скандален",
        "трагична",
        "сензационна",
        "гореща",
        "горещи",
        "горещ",
        "извънредна",
        "извънредни",
        "извънредно",
        "сензационни",
        "сензационно",
        "трагични",
        "трагично",
        "жесток",
        "жестока",
        "жестоки",
        "жестоко",
        "страшна",
        "страшен",
        "страшни",
        "страшно",
        "изумително", 
        "изумителна",
        "изумителни",
        "изумителен", 
        "ужасяващо",
        "ужасяващ",
        "ужасяващи",
        "ужасяваща",
        "фатално",
        "фатален",
        "фатална",
        "фатални");
}
