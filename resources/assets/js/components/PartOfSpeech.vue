<template>
    <ul id="part-of-speech" class="list-inline">
        <li v-for="item in pos">
            <word :word="item.node"></word>
        </li>
    </ul>
</template>

<script>
    import * as marked from "marked";
    import _ from "lodash";
    import Word from "./Word.vue"

    export default {
        mounted() {
            console.log('part of speech mounted');
        },

        components: {
            "word": Word
        },
        props: ["pos"]
    }
</script>

<style>
    #part-of-speech {
        margin-left: 3em;
        border-left: 2px gray solid;
    }
</style>