<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetDateListener
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
        $event->article->date = GetDateListener::checkDateValidation($event);
        $event->article->startIntervalDate = GetDateListener::genStartInterval($event->article->date);
        $event->article->endIntervalDate = GetDateListener::genEndInterval($event->article->date);
    }

    private function checkDateValidation($event){
        if(ctype_digit(GetDateListener::getDate($event->article->url))){
            return GetDateListener::getDate($event->article->url);
        }else{
            $html = file_get_contents($event->article->url);
            for($i = 0; $i < substr_count($html, 'date'); $i++){  
                $date = substr($html, strpos($html, 'date', $i), 200);
                $date = substr($date, GetDateListener::getFirstOccurenceOfNumber($date));
                $date = substr($date, 0, 10);
                $date = str_replace("-", "", $date);
                $date = str_replace(".", "", $date);
                if(ctype_digit($date))return $date;
            }
        }
    }

    private function getDate($url){
        $html = file_get_contents($url);
        $date = GetDateListener::findDate($html);
        $date = substr($date, GetDateListener::getFirstOccurenceOfNumber($date));
        $date = substr($date, 0, 10);
        $date = str_replace("-", "", $date);
        $date = str_replace(".", "", $date);
        return $date;
    }

    private function genStartInterval($date){
        $date = strtotime($date."+7 days");
        $startDate = date("Ymd", $date);
        return $startDate;
    }

    private function genEndInterval($date){
        $date = strtotime($date."-7 days");
        $startDate = date("Ymd", $date);
        return $startDate;
    }

    private function findDate($string){
        if(strpos($string, "datePublished") != "") $firstOccurrence = strpos($string, 'datePublished');
        else if(strpos($string, "datepublished") != "") $firstOccurrence = strpos($string, 'datepublished');
        else $firstOccurrence = strpos($string, 'date');
        $date = substr($string, $firstOccurrence, 200);
        return $date;
    }

    private function getFirstOccurenceOfNumber($string){
        $nums = array("0","1","2","3","4","5","6","7","8","9");
        $min = strpos($string, $nums[0]);
        for($i = 0; $i < sizeof($nums); $i++){
            $index = strpos($string, $nums[$i]);
            if($index < $min && $index != null)$min = $index;
        }
        return $min;
    }
}
