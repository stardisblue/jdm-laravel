<template>
    <div>
        {{results}}
    </div>
</template>
<script>
    import axios from 'axios'

    export default {
        mounted() {
            console.log("eh oui")
        },


        data() {
            return {results: null}
        },
    }
</script>
<style></style>