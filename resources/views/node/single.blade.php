<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/jdm.css">
    <div class="container-fluid">

        <div  class="jdm-logo-block" style="top: 0px; left: 0px;">
            <div class="jdm-logo" style="left: 0px;"><a BORDER=0 href="http://jeuxdemots.org/jdm-accueil.php">
                    <img src="{{asset('img/logo_jdm.png')}}" style="width:200px;position:relative;left:5px;top:10px;">
                    <span class="details-logo">le jeu des mots à collectionner</span></a>
            </div>
            <div><a BORDER=0 href="/search">
                    <img src="{{asset('img/home.jpg')}}" style="width:50px;position:relative;float: right;left:1050px;top:10px;">
                </a>
            </div>
        </div>
    </div>
</head>
<body style="position:relative">

@isset($node)
    <node :node="{{json_encode($node)}}"></node>
@endisset
@if(isset($reason) && $reason !== "OK")
    {{$reason}}
@endif
<script src="/js/app.js"></script>
</body>
</html>