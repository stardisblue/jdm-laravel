<template>
    <div class="inner-addon left-addon">
        <i class="glyphicon glyphicon-search"></i>
        <input type="text" v-model="search" class="form-control"
               placeholder="Rechercher une relation liée">
        <ul v-if="results||error" class="autocomplete-list">
            <li v-for="item in results"><a href="">{{item.formattedName ? item.formattedName : item.name}}</a></li>
            <li v-if="error"><a href="">Aucun mot trouvé</a></li>
        </ul>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                search: "",
                results: null,
                error: false,
            }
        },
        props: {
            url: {
                type: String
            }
        },

        watch: {
            search: function (newVal) {
                this.error = false;
                this.fetch();
            }
        },

        methods: {
            fetch: _.debounce(function () {
                axios.get(this.url, {params: {q: this.search}}).then(function (response) {
                    this.results = response.data.results
                }.bind(this)).catch(function (error) {
                    this.results = null;
                    this.error = true;
                    return // catched
                }.bind(this))
            }),
        }
    }
</script>
<style lang="sass">
    .autocomplete-list
        position: absolute
        top: 100%
        left: 0
        z-index: 1000
        float: left
        min-width: 160px
        padding: 5px 0
        margin: 2px 0 0
        list-style: none
        font-size: 14px
        text-align: left
        background-color: #fff
        border: 1px solid #ccc
        border: 1px solid rgba(0, 0, 0, 0.15)
        border-radius: 4px
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175)
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175)
        background-clip: padding-box

        > li > a
            display: block
            padding: 3px 20px
            clear: both
            font-weight: normal
            line-height: 1.6
            color: #333333
            white-space: nowrap


</style>