<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/searchbar.css">
    <link rel="stylesheet" href="/css/jdm.css">

</head>
<body style="position:relative;">

<div  class="jdm-logo-block">
    <div class="jdm-logo"><a BORDER=0 href="http://jeuxdemots.org/jdm-accueil.php">
            <img src="{{asset('img/logo_jdm.png')}}" style="width:200px;position:relative;left:5px;top:10px;">
            <span class="details-logo">le jeu des mots à collectionner</span></a>
    </div>
</div>
<div class="containerSearch">
    <div class="search-box" id="SBox">
        <input id="search" placeholder="Search... &#34; chat &#34; , *chat , chat*" type="text">
        <div class="also search-link" id="searchclick">
            <a  href="" id="searchButt">
                <span class="glyphicon glyphicon-search searchimg"></span>
            </a>
        </div>
        <a class="also infos">
            <span class="glyphicon glyphicon-question-sign searchimg" data-toggle="modal" data-target="#myModal"></span>
        </a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Paramètres de recherche</h4>
            </div>
            <div class="modal-body">
                <p>Afin d'affiner la recherche, il est nécessaire de rajouter quelques carractères spéciaux à votre recharche : </p>
                <ul>
                    <li> <b>Les guillemets (")</b> permettent de rechercher l’ensemble d’une expression.</li>
                    <li> <b>Utiliser une étoile (*) </b> en guise de préfixe ou de suffixe permet de retrouver tous les mots qui se terminent ou commencent par le mot indiqué</li>

                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>

    </div>
</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

    $("#searchButt").click(function(event){
        var href = document.getElementById("search").value;
        if(href ==""){
            alert("Il faut remplir le champs de recherche !"); return;
        }
         document.getElementById("searchButt").setAttribute("href","/node/"+href);
    });





</script>
<script src="/js/app.js"></script>
</html>