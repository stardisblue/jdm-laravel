<template>
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">{{node.name}}
                    <small v-once="" v-html="getPos"></small>
                </div>

                <div class="panel-body" v-html="compiledMarkdown"></div>
            </div>
            <relation-type v-once="v-once"
                           v-for="(relationType, id) in node.relationTypes"
                           :key="id"
                           :relationType="relationType"></relation-type>
        </div>
    </div>
</template>

<script>
    import * as marked from "marked";
    import RelationType from "./RelationType.vue"

    export default {
        mounted() {
            console.log('Node ' + this.node.name + ' mounted');
        },

        computed: {
            compiledMarkdown: function () {
                return marked(this.node.definition, {breaks: true, sanitize: true})
            },

            getPos: function () {
                if (this.node.relationTypes[4] !== undefined) {

                    let pos = this.node.relationTypes[4];
                    delete this.node.relationTypes[4];
                    return pos;
                }
            }
        },

        components: {
            "relation-type": RelationType
        },
        props: ["node"]
    }
</script>
