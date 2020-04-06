@extends('layouts.master')

@section('content')

<div class="container-home container--max-width" style="background-image: linear-gradient(#FDFDFD, #F5F5F5); margin-top:0px;">
    <form action="{{route('article_check')}}" method="post">
        {{ csrf_field() }}
        <textarea name="article_text" rows="20" cols="50">{{ $text }}</textarea>
        <input type="hidden" name="article_url" value="{{ $url }}">
        <input type="hidden" name="article_title" value="{{ $title }}">
        <button type="submit" style="height: 46px; font-size: 20px; border-radius:40px; color: white; margin-left: auto; display: block;">Продължи</button>
    </form>
</div>    

@endsection