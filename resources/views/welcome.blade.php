<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jeux de Mots</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<home errors="{{$errors->default->first('reason')}}"></home>
<!-- JavaScript -->
<script src="/js/app.js"></script>
<script>
    const app = new Vue({
        el: 'home',
        components: {
            "home": Home
        }
    });
</script>
</body>
</html>
