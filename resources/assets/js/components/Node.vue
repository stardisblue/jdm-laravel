<template>
    <div class="container">
        <div v-if="error" class="error">
            {{ error }}
        </div>

        <div v-if="node" class="row">
            <h2>{{getName}}</h2>
            <pos :pos="getPos"></pos>
            <sem-refin :semRefin="getSemRefin"></sem-refin>

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
    import axios from "axios"

    import RelationType from "./RelationType.vue"
    import Sidebar from "./Sidebar.vue"
    import PartOfSpeech from "./PartOfSpeech.vue"
    import SemanticRefinement from "./SemanticRefinement.vue"

    export default {
        mounted() {
            console.log('Node mounted');
        },

        data() {
            return {
                node: null,
                error: null
            }
        },

        components: {
            "relation-type": RelationType,
            "pos": PartOfSpeech,
            "sidebar": Sidebar,
            "sem-refin": SemanticRefinement
        },

        beforeRouteEnter(to, from, next) {
            axios.get('/node', {params: to.query}).then(function (response) {
                console.log(response);

                next(vm => vm.node = response.data);
            }).catch(function (error) {
                console.log(error);
                next(vm => vm.error = error.response.data);
            });
        },
        // quand la route change et que ce composant est déjà rendu,
        // la logique est un peu différente
        beforeRouteUpdate(to, from, next) {
            this.node = null;
            console.log('salut');
            axios.get('/node', {params: to.query}).then(function (response) {
                console.log(response);
                this.node = response.data;
                next()
            }.bind(this)).catch(function (error) {
                console.log(error);
                next(vm => vm.error = error.response.data);
            })
        },

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
</style>