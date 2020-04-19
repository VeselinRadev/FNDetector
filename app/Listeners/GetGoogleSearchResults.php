<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use DetectKewords;

//Google Searching API
use Fogg\Google\CustomSearch\CustomSearch;

class GetGoogleSearchResults
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
        $event->article->searchResults = GetGoogleSearchResults::formatSearchResults($event);
    }
    
    private function formatSearchResults($event){
        $output = array();
        $o1 = array();
        $o2 = array();
        $o1 = GetGoogleSearchResults::doSearch($event, $event->article->keywords);
        $o2 = GetGoogleSearchResults::doSearch($event, $event->article->title);
        $output = array_merge($o1, $o2);
        for($i = 0; $i < sizeof($output) - 1; $i++){
            if($i != ""){
                for($j = $i + 1; $j < sizeof($output); $j++){
                    if(getGoogleSearchResults::getSiteName($output[$i]) == getGoogleSearchResults::getSiteName($output[$j])){
                        $output[$j] = "";
                    }
                }
            }
        }
        $output = array_unique($output);
        $output = array_values($output);
        for($i = 0; $i < sizeof($output); $i++){
            if($output[$i] == "")unset($output[$i]);
        }

        $output = array_values($output);
        return $output;
    }

    private function doSearch($event, $data){
        $cs = new CustomSearch('009639852197592909373:xuorrrszy5l', 
        'AIzaSyDqvrNJW97Vu9V9RRqXBJuU7xZWvDqW1YA');
        if($event->article->date != null && $event->article->date != "")return GetGoogleSearchResults::processResult($cs->search($data, 
                            array('sort' => "date:r:".$event->article->endIntervalDate.":".$event->article->startIntervalDate)), $event);
        else return GetGoogleSearchResults::processResult($cs->simpleSearch($data), $event);       
    }

    private function processResult($search, $event){
        $output = array();
        if(isset($search->items)){
            foreach($search->items as $item){
                 array_push($output, $item->link);
            }
        }
        return $output;
    }

    private function getSiteName($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = str_replace("www.", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        return $url;
    } 
}
