<template>
    <div id="home">
        <canvas class="fireworks"></canvas>

        <div v-if="errors" id="errors" class="alert alert-danger content" role="alert">
            {{errors}}
        </div>
        <div class="flex-center position-ref full-height">
            <div class="content container-fluid">
                <div class="row title m-b-md">
                    Jeux de Mots
                    <div class="searchbar col-sm-6 col-sm-offset-3">
                        <form action="/search">
                            <div class="input-group">
                                <input type="text" name="q" v-model="query" class="form-control"
                                       placeholder="Rechercher">
                                <span class="input-group-btn"><button class="btn btn-default"
                                                                      type="submit">
                                <i class="glyphicon glyphicon-search"></i></button></span>
                            </div><!-- /input-group -->
                            <a class="h6" data-toggle="modal" href="#myModal">Aide <i
                                    class="glyphicon glyphicon-question-sign"></i></a></li>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Aide</h4>
                    </div>
                    <div class="modal-body" style="font-weight:bold ">
                        <p>2 types de recherches sont possibles :<br/></p>
                        <ul>
                            <li><u>Recherche exacte</u> en tapant le mot complet. Exemple : <b style="color: #3097D1;">chat</b>
                            </li>
                            <li><u>Recherche approximative</u> en préfixant le mot d'un <b
                                    style="color: #3097D1;">.*</b>ou en
                                le rajoutant à la fin
                                <br/>Exemple : <b style="color: #3097D1;">.*chat</b> retournera achat et <b
                                        style="color: #3097D1;">chat.*</b> retournera chaton
                            </li>
                        </ul>
                        <p><b>Ps:</b> il est également possible d'utiliser des expressions régulières plus complèxes !
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    function init() {
       require('../poum.js') // adding poum
    }

    export default {
        mounted() {
            console.log('Main mounted');
            init();
        },

        data: function () {
            return {
                query: ""
            }
        },

        props: {
            errors: {type: String}
        },
    }
</script>
<style>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
    }

    input {
        font-weight: 600;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .m-b-md {
        margin-bottom: 30px;
    }

    .fireworks {
        position: absolute;
    }

    #errors {
        width: 100%;
        position: absolute;
        color: #636b6f;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    #myModal {
        color: black;
    }
</style>