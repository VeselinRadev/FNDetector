<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckForConjunction
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
      // CheckForConjunction($event->article->searchResults);
    }
    
    private function clearSources($urls){
        for($i = 0; $i < count($urls); $i++){
            $urlForScan = $urls[$i];
            for($j = $i + 1; $j < count($urls); $j++){
                
            }
        }
    }
    
    private function getTheMostRelevantSite($url1, $url2){
        
    }
    
    private function getSiteName($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = str_replace("www.", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        return $url;
    }
    
    private function searchedSiteData($keywords, $title){
        
    }
}
