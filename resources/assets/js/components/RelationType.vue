<template>
    <div class="panel panel-default">
        <div class="panel-heading">{{relationType.name}}
            <small>{{relationType.description}}</small>
        </div>

        <div class="panel-body">
            <ul>
                <li v-for="relation in orderByWeight"
                    v-if="relation.from === null && relation.to !== null">
                    <word :word="relation.to"></word>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import * as _ from "lodash";
    import Word from "./Word.vue"

    export default {
        mounted() {
            console.log('RelationType ' + this.relationType.name + ' mounted');
        },

        computed: {
            orderByWeight() {
                return _.orderBy(this.relationType.relations, ['weight', 'name'], ['desc', 'asc']);
            },

        },

        components: {
            "word": Word
        },

        methods: {
            name(word) {
                return word.formattedName !== null ? word.formattedName : word.name;
            },
        },

        props: ["relationType"]
    }
</script>
