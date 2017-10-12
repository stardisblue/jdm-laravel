<template>
    <div class="container" data-spy="scroll" data-target="#sidebar">
        <div class="row">
            <h2>{{node.name}} :
                <small v-once="" v-html="getPos"></small>
            </h2>

            <div id="description" v-html="compiledMarkdown"></div>
            <hr/>
            <div class="col-md-9">

                <relation-type v-once="v-once"
                               v-for="(relationType, id) in node.relationTypes"
                               :key="id"
                               :id="id"
                               :relationType="relationType"></relation-type>

            </div>

            <div class="col-md-3">
                <h3>Sidebar</h3>
                <sidebar :relationTypes="getRelationTypes"></sidebar>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aliquid amet corporis eos error est ipsam itaque laborum mollitia nemo possimus praesentium quae quasi qui quia, repellat soluta voluptates voluptatibus?
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
            },

            getRelationTypes: function () {
                return _.map(this.node.relationTypes, function (value, index) {
                    return {id: index, name: value.name};
                });
            }
        },

        components: {
            "relation-type": RelationType,
            "sidebar": Sidebar
        },
        props: ["node"]
    }
</script>
