<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessResults
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
        $event->article->textQuality = ProcessResults::text($event->article->suspiciousWords, 
                                                            $event->article->upperCaseWords, 
                                                            $event->article->dramaticPunctuation);
        $event->article->relevance = ProcessResults::mDate($event->article->date);
        $event->article->sources = ProcessResults::source($event->article->searchResults);
        $event->article->spelling = ProcessResults::mistakes($event->article->mistakes);
        $event->article->urlStat =  ProcessResults::url($event);
    }
    
    private function mDate($date){
        if(isset($date) && $date != ""){    
            $today = date("Ymd"); 
            if(date("Ymd", strtotime($date."+7 days")) < $today){
                return  '<h4  class="collapsible">'."❌ Новината не е актуална "
                .'</h4><div class="content"><ul><li>Публикувана е на '.date(("d.m.Y"), strtotime($date)).'</li></ul></br></div>';
            }else{
                return '<h4  class="collapsible">'."✔️ Новината е актуална ".
                '</h4><div class="content"><ul><li>Публикувана е на '.date(("d.m.Y"), strtotime($date)).'</li></ul></br></div>';
            }
        }else{
            return '<h4  class="collapsible">'."❌  Дата е неизвестна"
                    .'</h4><div class="content"><ul><li>В сайта не е посочена дата на публикуване</li></ul></br></div>';
        }
    }
    
    private function text($suspiciousWords, $upperKeysWords, $dramaticPunctuation){
        $output = '<h4 class="collapsible">';
        if(sizeof($suspiciousWords) != 0  || sizeof($upperKeysWords) != 0){
            $output .= '❌ Съдържа атрактивни заглавия';
             if($dramaticPunctuation == true){
                $output .= " и драматична пунктуация";
                $output .= '</h4><div class="content"><ul>
                            <li>Съдържа много въпросителни или удивителни на едно място</li></br>';
            }else{
                $output .= ", но драматична пунктуация не";
                $output .= '</h4><div class="content"><ul>
                            <li>Не са открити много пунктуационни знаци на едно място</li></br>';
            }
            
            if(sizeof($suspiciousWords) != 0){
                $output .= '<li>Думи използвани за привличане на внимание: ';
                foreach($suspiciousWords as $word){
                    $output .= $word."; ";
                }
                $output .= '</li></br>';
            }
            if(sizeof($upperKeysWords) != 0){
                $output .= '<li>Думи с главни букви: ';
                foreach($upperKeysWords as $word){
                    $output .= $word."; ";
                }
                $output .= '</li></br>';
            }
            $output .= '</ul></div>';    
            
        }else{
            $output = 'Не съдържа атрактивни заглавия';
             if($dramaticPunctuation == true){
                $output = '<h4 class="collapsible">'."❌ " .$output;
                $output .= ', но съдържа драматична пунктуация</h4><div class="content"><ul>
                            <li>Съдържа много въпросителни или удивителни на едно място</li></br>';
            }else{
                $output = '<h4 class="collapsible">'. "✔️ " .$output;
                $output .= ' и драматична пунктуация</h4><div class="content"><ul>
                            <li>Не съдържа никакви съмнителни елементи в текста</li></br>';
            }
            $output .= '<li>Няма думи с главни букви</li></br>';
            $output .= '<li>Няма думи за привличане на внимание</li></ul></br></div>';
        }
        return $output;
    }
    
    private function source($source){
        $output = "";
        if(sizeof($source) == 0){
            $output = '<h4 class="collapsible">'.'❌ Няма съвпадения с надеждни източници </h4><div class="content"><ul>
                            <li>Новината не е открита в нито един от доверените източници</li></ul></br></div>';
        }else{
            $output = '<h4 class="collapsible">'.'✔️ Открита е в надеждни източници</h4><div class="content"><ul>';
            foreach($source as $source){
                $output .= ('<li><a href="').$source.('"><h5&>').(ProcessResults::getSite($source)).('</h5></a></li></br>');
            }
            $output .= '</ul></br></div>';
        }
        return $output;
    }
    
    private function url($event){
        $domainStat = $event->article->suspiciousDomain;
        $dotsStat = $event->article->dotsInURL;
        $numberStat = $event->article->numbersInURL;
        $output = '<h4 class="collapsible">';
        if($domainStat || $domainStat || $numberStat) $output .= '❌ Линкът е съмнителен </h4><div class="content"><ul>';
        else $output .= '✔️ Линкът е надежден </h4><div class="content"><ul>';
        if(!$domainStat) $output .= '<li>Домейнът е често срещан</li></br>';
        else $output .= '<li>Домейнът е рядко срещан</li></br>';
        if(!$dotsStat && !$numberStat) $output .= '<li>Надежден формат на името на сайта - без опит да заблуди читателя</li></br>';
        else $output .= '<li>Съмнително име на сайта - с цел да заблуди читателя</li></br>';
        $output .= '</ul></div>';
        return $output;
    }
    
    private function mistakes($mistakenWords){
         $output = ("<h4 class=").('"collapsible"').(">");
        if(sizeof($mistakenWords) == 0){
            $output .= "✔️ Няма правописни грешки</h4>";
        }else{
            $output .= ("❌ Има правописни грешки</h4><div class=").('"content"').("><ul><li>В думите: ");
            foreach($mistakenWords as $mistake){
                $output .= $mistake."; ";
            }
            $output .= "</li></ul></br></div>";
        }
        return $output;
    }
    
    private function getSite($url){
        $url = str_replace("https://", '', $url);
        $url = str_replace("http://", '', $url);
        $url = substr($url, 0, strpos($url, '/'));
        return $url;
    }
}
