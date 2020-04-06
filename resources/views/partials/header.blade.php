<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="HandheldFriendly" content="true">
    <meta name="format-detection" content="telephone=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fake News Detector</title> 

    <link rel="stylesheet" href="{{ "css/app.css" }}">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    

	<meta name="format-detection" content="telephone=no">
	<link href="{{ asset("/favicon.ico") }}" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	
	@stack("head")
</head>
<body>
