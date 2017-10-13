<template>
    <div :id="id" class="relation-type">
        <div class="relation-type-header"><h3 :title="relationType.description">{{relationType.name}}</h3>
            recherche, filtres closing button
        </div>
        <hr/>
        <ul class="list-inline">
            <li v-for="relation in orderByWeight()"
                v-if="relation.from === null && relation.to !== null">
                <word :word="relation.to"></word>
            </li>
        </ul>
    </div>
</template>

<script>
    import * as _ from "lodash";
    import Word from "./Word.vue"

    export default {
        mounted() {
            console.log('RelationType ' + this.relationType.name + ' mounted');
        },

        components: {
            "word": Word
        },

        methods: {
            orderByWeight: function (order = "desc") {
                return _.orderBy(this.relationType.relations, ['weight', 'to.name'], [order, 'asc']);
            },

            orderByName: function (order = "asc") {
                return _.orderBy(this.relationType.relations, ['to.name'], [order]);
            }
        },

        props: ["id", "relationType"]
    }
</script>
