<template>
    <div id="semantic-refinement lead">
        Peux signifier :
        <ul class="list-inline">
            <li v-for="item in semRefin.out">
                <word :word="item.node"></word>
            </li>
        </ul>
    </div>
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
        props: ["semRefin"]
    }
</script>

<style>
    #semantic-refinement {
        margin-left: 3em;
    }
</style>