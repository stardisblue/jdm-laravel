<template>
    <div class="container">
        <div class="row">
            <h2>{{getName}} :
                <small v-once="" v-html="getPos"></small>
            </h2>

            <div id="description" v-html="compiledMarkdown"></div>
            <hr/>
            <div class="col-sm-9">

                <relation-type v-once="v-once"
                               v-for="(relationType, id) in node.relationTypes"
                               :key="id"
                               :id="id"
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

    export default {
        mounted() {
            console.log('Node ' + this.node.name + ' mounted');
        },


        components: {
            "relation-type": RelationType,
            "sidebar": Sidebar
        },

        props: ["node"],

        computed: {
            getName: function () {
                return this.node.formattedName ? this.node.formattedName : this.node.name;
            },

            compiledMarkdown: function () {
                return marked(this.node.definition, {breaks: true, sanitize: true})
            },

            getPos: function () {
                if (this.node.relationTypes[4] !== undefined) {
                    return this.node.relationTypes[4];
                }
            },

            getRelationTypes: function () {
                return _.map(this.node.relationTypes, function (value, index) {
                    return {id: index, name: value.name};
                });
            }
        },

    }
</script>