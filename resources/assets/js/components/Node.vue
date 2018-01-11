<template>
    <div class="container">
        <div class="row" style="margin-top:100px">
            <h2>{{getName}} </h2>
            <ul id="part-of-speech" class="list-inline">
                <li v-for="item in getPos">
                    <word :word="item.node"></word>
                </li>
            </ul>
            <div id="semantic-refinement lead">
                Peux signifier :
                <ul class="list-inline">
                    <li v-for="item in getSemRefin.out">
                        <word :word="item.node"></word>
                    </li>
                </ul>
            </div>
            <h3>Description</h3>
            <div id="description" v-html="compiledMarkdown"></div>
            <hr/>
            <div class="col-sm-9">

                <relation-type v-once="v-once"
                               v-for="relationType in node.relationTypes"
                               :key="relationType.id"
                               :id="relationType.id"
                               :relationType="relationType"></relation-type>

            </div>

            <div class="col-sm-3 hidden-xs">
                <sidebar :relationTypes="getRelationTypes"></sidebar>
            </div>
        </div>
    </div>
</template>

<script>
    import * as marked from "marked";
    import _ from "lodash";
    import RelationType from "./RelationType.vue"
    import Sidebar from "./Sidebar.vue"
    import Word from "./Word.vue"

    export default {
        mounted() {
            console.log('Node ' + this.node.name + ' mounted');
        },


        components: {
            "relation-type": RelationType,
            "sidebar": Sidebar,
            "word": Word
        },

        props: ["node"],

        computed: {
            getName: function () {
                return this.node.formattedName ? this.node.formattedName : this.node.name;
            },

            compiledMarkdown: function () {
                return marked(this.node.description, {breaks: true, sanitize: true})
            },

            getPos: function () {
                return _.find(this.node.relationTypes, function (value) {
                    return value.id === 4
                }).relations.out
            },

            getSemRefin: function () {
                return _.find(this.node.relationTypes, function (value) {
                    return value.id === 1
                }).relations
            },


            getRelationTypes: function () {
                return _.map(this.node.relationTypes, function (value) {
                    return {id: value.id, name: value.name};
                });
            }
        },

    }
</script>

<style lang="sass">
    #part-of-speech, #semantic-refinement

        margin-left: 3em
        border-left: 2px gray solid
</style>