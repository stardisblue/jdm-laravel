<template>
    <a class="word" :href="'node?word=' +word.name">{{name()}}</a>
</template>

<script>
    import _ from "lodash"

    export default {
        mounted() {
            //console.log('Word mounted : ');
        },

        methods: {
            name() {
                return this.word.formattedName !== null ? this.word.formattedName : this.word.name;
            },
        },

        props: ["word"]
    }
</script>
