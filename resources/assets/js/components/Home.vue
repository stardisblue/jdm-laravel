<template>
    <div id="home">
        <canvas class="fireworks"></canvas>
        <div class="flex-center position-ref full-height">

            <div class="content container-fluid">
                <div class="row title m-b-md">
                    Jeux de Mots
                    <div v-if="errors">{{errors}}</div>
                    <div class="searchbar col-sm-6 col-sm-offset-3">
                        <form action="/search">
                            <div class="input-group">

                                <input type="text" name="q" v-model="query" class="form-control"
                                       placeholder="Rechercher">
                                <span class="input-group-btn"><button class="btn btn-default"
                                                                      type="submit">
                                <i class="glyphicon glyphicon-search"></i></button></span>
                            </div><!-- /input-group -->
                        </form>
                    </div>
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
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

        props: {
            query: {type: String},
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

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }

    .fireworks {
        position: absolute;
    }
</style>