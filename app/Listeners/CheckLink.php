<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckLink
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
        $event->article->suspiciousDomain = CheckLink::checkDomain($event->article->url);
        $event->article->dotsInURL = CheckLink::checkDots($event->article->url);
        $event->article->numbersInURL = CheckLink::checkForNumbers($event->article->url);
    }
    
    private function checkDomain($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = str_replace("www.", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        $domain = substr($url,strpos($url, '.'));
        $domain = trim($domain);
        if($domain == ".bg" || $domain == ".com" || $domain == ".net" || $domain == ".eu")return false;
        else return true;
    }
    
    private function checkDots($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = str_replace("www.", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        $occ = substr_count($url, '.');
        if($occ > 1) return true;
        else return false;
    }
    
    private function checkForNumbers($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = str_replace("www.", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        return strpbrk($url, '1234567890');
    }
}
