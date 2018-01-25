<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Results</title>
    <link rel="stylesheet" href="/css/app.css">
</head>

<body style="position:relative">
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header" >
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
            <li><a class="navbar-brand" href="/" title="Jeux De Mots">JDM</a></li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="text-center col-md-4 col-md-offset-4">
        <h2 style="margin-top: 100px; margin-bottom: -15px;">{{count($results)}} correspondances :</h2>

        <div class="row">
                <hr>
                <ul class="list-inline">
                    @foreach($results as $result)
                        <li><a href="{{route('node',['word' => $result['name']]) }}"> {{ $result['name'] }} </a></li>
                    @endforeach
                </ul>
                <hr>

        </div>

    </div>
</div>

<!-- TODO -->
<script src="/js/app.js"></script>
</body>
</html>