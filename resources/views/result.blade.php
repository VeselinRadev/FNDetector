@extends('layouts.master')

@section('content')
<div class="container-home container--max-width" style="background-image: linear-gradient(#FDFDFD, #F5F5F5); margin-top:0px">
    <h1 align="center">{{ $title }}</h1>
    <ul class="no_bullet">
        <li>
            {!!  $source  !!}
        </li>  

        <li>
            {!! $spelling !!}
        </li>

        <li>
            {!! $textQuality !!}</h4>
        </li>

        <li>    
            {!! $relevance !!}
        </li>

        <li>
            {!! $urlStat !!}
        </li>
    </ul>


<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + 10 + "px";
    }
  });
}
</script>
@endsection