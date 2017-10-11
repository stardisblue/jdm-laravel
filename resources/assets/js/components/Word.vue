<template>
    <a class="word" :href="'./' +word.name">{{name()}}</a>
</template>

<script>
    import _ from "lodash"

    export default {
        mounted() {
           // console.log('Word mounted : ' + this.word.name);
        },

        methods: {
            name() {
                return this.word.formattedName !== null ? this.word.formattedName : this.word.name;
            },
        },

        props: ["word"]
    }
</script>
