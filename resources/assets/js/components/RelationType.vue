<template>
    <div :id="id" class="relation-type">
        <div class="relation-type-header"><h3 :title="relationType.description">{{relationType.name}}</h3>
            recherche, filtres closing button
        </div>
        <hr/>
        <ul class="list-inline relations-in">
            <li v-for="relation in relationType.relations.in">
                <word :word="relation.node"></word>
            </li>
        </ul>
        <ul class="list-inline relation-out">
            <li v-for="relation in relationType.relations.out"><word :word="relation.node"></word></li>
        </ul>
    </div>
</template>

<script>
    import Word from "./Word.vue"

    export default {
        mounted() {
            console.log('RelationType ' + this.relationType.name + ' mounted');
        },

        components: {
            "word": Word
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

        props: ["id", "relationType"]
    }
</script>
