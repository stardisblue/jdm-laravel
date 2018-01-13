<template>
    <div class="relation-type">
        <a :id="'rt'+index" class="anchor"></a>
        <div class="relation-type-header">
            <div class="h3" :title="relationType.description">{{relationType.name}}
            </div>
            recherche, filtres closing button
        </div>
        <hr/>
        <div v-if="relationsIn.length > 0" class="relations-in">
            <p>Relations Entrantes</p>
            <ul class="list-inline">
                <li v-for="relation in relationsIn">
                    <word :id="relation.id" :word="relation.node"></word>
                </li>
                <li>
                    <button class="btn btn-xs btn-default">>voir plus...</button>
                </li>
            </ul>
        </div>
        <div v-if="relationsOut.length > 0" class="relations-out">
            <p>Relations Sortantes</p>
            <ul class="list-inline">
                <li v-for="relation in relationsOut">
                    <word :id="relation.id" :word="relation.node"></word>
                </li>
                <li>
                    <button class="btn btn-xs btn-default">>voir plus...</button>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    import Word from "./Word.vue"

    export default {
        props: {
            relationType: {
                type: Object,
                required: true,
            },
            index: {
                type: Number,
                required: true,
            }
        },

        data() {
            return {
                relationsIn: null,
                relationsOut: null
            }
        },

        created() {
            this.relationsIn = this.relationType.relations.in;
            this.relationsOut = this.relationType.relations.out
        },

        mounted() {
            console.log('RelationType ' + this.relationType.name + ' mounted');
        },

        components: {
            "word": Word
        },

        watch: {
            relationTypes: function () {
                this.relationsIn = this.relationType.relations.in;
                this.relationsOut = this.relationType.relations.out
            }
        },

        methods: {
            inOrderByWeight: function (order = "desc") {
                this.relationType.relations['in'] = _.orderBy(this.relationType.relations.in, ['weight', 'node.name'],
                    [order, 'asc']);
            },

            inOrderByName: function (order = "asc") {
                this.relationType.relations['in'] = _.orderBy(this.relationType.relations.in, ['node.name'], [order]);
            },
            outOrderByWeight: function (order = "desc") {
                this.relationType.relations.out = _.orderBy(this.relationType.relations.out, ['weight', 'node.name'],
                    [order, 'asc']);
            },

            outOrderByName: function (order = "asc") {
                this.relationType.relations.out = _.orderBy(this.relationType.relations.out, ['node.name'], [order]);
            }
        },

    }
</script>
<style>
    .relations-in > ul {
        border-left: 2px blue solid;
        padding-left: 1em;
    }

    .relations-out > ul {
        border-left: 2px green solid;
        padding-left: 1em;
    }

    a.anchor {
        display: block;
        position: relative;
        top: -70px;
        visibility: hidden;
    }
</style>