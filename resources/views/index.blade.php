@extends('layouts.master')
@section('content')

<div class="container-home container--max-width" style="background-image: linear-gradient(#FDFDFD, #F5F5F5); margin-top:0px;"> 
    <div class="top top--max-width" style="margin-bottom:16px;">
        <p>Fake News Detector е сайт, който дава възможност да се отсеят реалните от фалшивите новини, които в днешно време заливат интернет пространството. Това се случва като конкретната новина се проверява внимателно в подбран списък от надеждни сайтове.</p>
        <div>
            <ul style="display: flex;list-style-type: none;margin: 0;padding: 0;">
                <li style="float:left;margin-top:10px"> 
                    <ol>
                        <p><b>Указания за ползване:</b></p>
                        <li class="top-li"><p>Поставяте линка, посочващ пътя към новината, в полето по-долу и натискате бутона "Провери".</p></li>
                        <li class="top-li"><p>Отваря се нова страница, където трябва да изтриете ненужния текст.</p></li>
                        <li class="top-li"><p>Резултатът след проверката на новината е представен в отеделни категории, като след натискане на плюсчетата може да разгледате подробна информация за тях.</p></li>
                    </ol>
                </li>
                <li style="float:right;margin-top:10px"><img src={{url("images/homeImg.png")}} style="width:auto;height:auto;"></li>
            </ul>
        </div>   
    </div>  
    <form class="search-wrapper" action="{{route('article_get_info')}}" method="post">
        {{ csrf_field() }}
        <input type="text" name="article_url" required autocomplete="off" autofocus="true" placeholder="Постави линка тук..."/>
        <button type="submit">Провери</button>
    </form> 
</div>
<div class="container-home container--max-width" style="background-color:#FFFFFF">    
    <div class="cards-container">
        <ul class="cards-ul">
            <li class="cards-li">
                <div class="flip-card">
                    <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="https://img.icons8.com/dusk/100/000000/easy.png" style="width:100px;height:100px;margin-top:32px;">
                        <h2 style="color:white;"><b>Лесна<br>употреба</b><h2>
                    </div>
                    <div class="flip-card-back">
                        <h3 style="color:white; margin-top:32px; margin-left:16px; margin-right:16px; margin-bottom:16px; line-height:175%; height:inherit;">FNDetector представя интуитивен дизайн, който е подходящ за всеки един потрбител.</h3>
                    </div>
                    </div>
                </div>
            </li>    
            <li class="cards-li">
                <div class="flip-card">
                    <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="https://img.icons8.com/dusk/100/000000/trust.png" style="width:100px;height:100px;margin-top:32px;">
                        <h2 style="color:white;"><b>Надеждни<br>резултати</b><h2>
                    </div>
                    <div class="flip-card-back">
                        <h3 style="color:white; margin-top:32px; margin-left:16px; margin-right:16px; margin-bottom:16px; line-height:175%; height:inherit;">FNDetector се позовава на условните принципи при разпознаването на фалшивите новини.</h3>
                    </div>
                    </div>
                </div>
            </li>    
            <li class="cards-li">
                <div class="flip-card">
                    <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="https://img.icons8.com/dusk/100/000000/medal.png" style="width:100px;height:100px;margin-top:32px;">
                        <h2 style="color:white;"><b>Единствен<br>по рода си</b><h2>
                    </div>
                    <div class="flip-card-back">
                        <h3 style="color:white; margin-top:32px; margin-left:16px; margin-right:16px; margin-bottom:16px; line-height:175%; height:inherit;">FNDetector е напълно пригоден да се използва за българското медийно пространство.</h3>
                    </div>
                    </div>
                </div>
            </li>    
        </ul>
    </div>
</div>

@endsection