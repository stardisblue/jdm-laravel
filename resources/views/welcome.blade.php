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
<body >
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header" >
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a data-toggle="modal" href="#myModal">Aide <i class="glyphicon glyphicon-question-sign"></i></a></li>
        </ul>
    </div>
</nav>

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

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <style>#myModal{color:black;}</style>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Aide</h4>
            </div>
            <div class="modal-body" style="font-weight:bold ">
                <p >2 types de recherches sont possibles :<br/>
                <ul>
                    <li><u>Recherche exacte</u> en tapant le mot complet. Exemple : <b style="color: #3097D1;">chat</b></li>
                    <li><u>Recherche approximative</u> en préfixant le mot d'un <b style="color: #3097D1;">.*</b>ou en le rajoutant à la fin
                        <br/>Exemple : <b style="color: #3097D1;">.*chat</b> retournera achat et <b style="color: #3097D1;">chat.*</b> retournera chaton</li>
                </ul>
                <b>Ps:</b> il est également possible d'utiliser des expressions régulières plus complèxes !
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
