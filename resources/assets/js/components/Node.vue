<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="title" class="h2">{{getName}} </div>
                <div v-if="getPos" id="part-of-speech" class="list-inline">
                    <word v-for="item in getPos" :word="item.node"></word>
                </div>
                <div v-if="getSemRefin" id="semantic-refinement">
                    <div v-if="getSemRefin.out.length > 0">
                        Voulez-vous dire ?
                        <word v-for="item in getSemRefin.out" :word="item.node"></word>
                    </div>
                    <div v-if="getSemRefin.in.length > 0">
                        Est généralisé par
                        <word v-for="item in getSemRefin.in" :word="item.node"></word>
                    </div>
                </div>

                <div v-if="compiledMarkdown">
                    <h3>Description</h3>
                    <div id="description" v-html="compiledMarkdown"></div>
                </div>

            </div>
        </div>
        <hr/>

        <div class="row">

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
    import axios from "axios";

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

        props: {
            node: {
                type: Object,
                required: true,
            }
        },

        computed: {
            getName: function () {
                return this.node.formattedName ? this.node.formattedName : this.node.name;
            },

            compiledMarkdown: function () {
                return marked(this.node.description, {breaks: true, sanitize: true})
            },

            getPos: function () {
                const result = _.find(this.node.relationTypes, function (value) {
                    return value.id === 4
                });
                return result === undefined ? null : result.relations.out;
            },

            getSemRefin: function () {
                const result = _.find(this.node.relationTypes, function (value) {
                    return value.id === 1
                });

                return result === undefined ? null : result.relations;
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
    //@formatter:off
    #part-of-speech
        margin-left: 1em
        border-left: 2px gray solid


    #part-of-speech, #semantic-refinement
        a
            padding-left: 1em


    #part-of-speech, #title
        display: inline-block

</style>