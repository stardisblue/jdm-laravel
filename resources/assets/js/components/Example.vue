<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{node.name}}</div>

                    <div class="panel-body" v-html="compiledMarkdown"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as marked from "marked";

    export default {
        mounted() {
            console.log('Component mounted.');
        },

        computed: {
            compiledMarkdown: function () {
                return marked(this.node.definition, {breaks: true, sanitize: true})
            }
        },

        props: ["node"]
    }
</script>
