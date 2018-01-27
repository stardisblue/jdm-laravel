<template>
    <div>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/" title="Jeux De Mots">
                        JDM
                    </a>
                </div>
                <ul class="nav navbar-nav">
                    <li v-if="displayName" class="active"><a href="#">{{getName}}</a></li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="title" class="h2">{{getName}}</div> <!-- Titre -->
                    <div id="part-of-speech">
                        <ul v-if="getPos" class="list-inline"> <!-- Part of speech -->
                            <li v-for="item in getPos">
                                <word prefix="pr" v-on:card="displayCard" v-on:uncard="destroyCard"
                                      :key="item.id" :id="item.id"
                                      :word="item.node"></word>
                            </li>
                        </ul>
                    </div>
                    <div v-if="getSemRefin" id="semantic-refinement"> <!-- Raffinements sémantiques -->
                        <div v-if="getSemRefin.out.length > 0">
                            Voulez-vous dire ?
                            <word prefix="sr" v-on:card="displayCard" v-on:uncard="destroyCard"
                                  v-for="item in getSemRefin.out" :key="item.id"
                                  :id="item.id"
                                  :word="item.node"></word>
                        </div>
                        <div v-if="getSemRefin.in.length > 0">
                            Est généralisé par
                            <word prefix="sr" v-on:card="displayCard" v-on:uncard="destroyCard"
                                  v-for="item in getSemRefin.in" :key="item.id"
                                  :id="item.id"
                                  :word="item.node"></word>
                        </div>
                    </div> <!--  /Raffinements sémantiques -->

                    <div v-if="compiledMarkdown"> <!-- Description -->
                        <h3>&raquo; Description</h3>
                        <div id="description" v-html="compiledMarkdown"></div>
                    </div>

                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-9">
                    <relation-type @card="displayCard" @uncard="destroyCard" @updateOffsetTop="updateOffsetTop"
                                   v-for="(relationType, index) in node.relationTypes"
                                   :key="index"
                                   :index="index"
                                   :relationType="relationType" :nodeId="node.id"></relation-type>
                </div>
                <div class="col-sm-3 hidden-xs">
                    <sidebar v-if="relationTypes" :relationTypes="relationTypes"
                             :activeIndex="currentRelationType"></sidebar>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as marked from "marked";

    import RelationType from "./RelationType.vue"
    import Sidebar from "./Sidebar.vue"
    import Word from "./Word.vue"
    import Autocomplete from "./Autocomplete.vue"

    export default {
        data() {
            return {
                search: "",
                relationTypes: null,
                displayName: false,
                currentRelationType: -1,
                events: {
                    scroller: null
                },
                lastPopover: null,
            }
        },

        props: {
            node: {
                type: Object,
                required: true,
            },
        },

        methods: {
            displayCard(value) {
                this.lastPopover = $("#" + value.xmlId);

                axios.get('/api/card', {params: {word: value.word}})
                    .then((response) => {
                        if (this.lastPopover) {
                            const node = response.data;
                            const formattedName = node.formattedName ? node.formattedName : node.name;

                            this.lastPopover.popover({
                                title: "<span class='glyphicon glyphicon-info-sign'></span> " + formattedName,
                                content: "<span class='glyphicon glyphicon-stats'></span> <i>node weight: " + node.weight + "</i><br/><span class='glyphicon glyphicon-link'></span> <i>relation weight : " + value.weight + "</i>" +
                                _.truncate(marked(node.description, {breaks: true, sanitize: true}), {
                                    length: 150
                                }),
                                trigger: 'manual',
                                placement: "auto", html: true
                            });

                            this.lastPopover.popover("show")
                        }
                    })
            },

            destroyCard() {
                if (this.lastPopover !== null) {
                    this.lastPopover.popover('destroy');
                    this.lastPopover = null;
                }
            },

            handleScroll() {
                let scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
                this.displayName = scrollPosition > 50;
                this.currentRelationType = _.findLastIndex(this.relationTypes, element => element.offsetTop <= scrollPosition);
                if (this.currentRelationType !== -1) {
                    history.replaceState(null, null, '#rt' + this.currentRelationType);
                    // works only on recent browser but we don't care :D
                }
            },

            updateOffsetTop() {
                console.log("updateOffsetTop")
                this.relationTypes = _.map(this.node.relationTypes, function (value, index) {
                    let element = document.getElementById('rt' + index);
                    return {
                        name: value.name,
                        index: index,
                        offsetTop: element.offsetTop + element.offsetParent.offsetTop,
                    };
                });


            },
        },

        computed: {
            getName: function () {
                return this.node.formattedName ? this.node.formattedName : this.node.name;
            },

            compiledMarkdown: function () {
                return marked(this.node.description, {breaks: true, sanitize: true})
            },

            getPos: function () {
                const result = _.find(this.node.relationTypes, function (value) {
                    return value.id === 4
                });
                return result === undefined ? null : result.relations.out;
            },

            getSemRefin: function () {
                const result = _.find(this.node.relationTypes, function (value) {
                    return value.id === 1
                });

                return result === undefined ? null : result.relations;
            },
        },

        created() {
            this.events.scroller = _.throttle(this.handleScroll, 100);
            window.addEventListener('scroll', this.events.scroller);
            window.addEventListener('resize', this.events.scroller);
            console.log("created");
        },

        mounted() {
            console.log('Node ' + this.node.name + ' mounted');

            this.updateOffsetTop()
        },

        destroyed() {
            window.removeEventListener('scroll', this.events.scroller);
        },

        components: {
            "relation-type": RelationType,
            "sidebar": Sidebar,
            "word": Word,
            "autocomplete": Autocomplete,
        },
    }
</script>

<style lang="sass">
    //@formatter:off
    #part-of-speech
        margin-left: 1em
        border-left: 2px gray solid

        .list-inline
            margin-bottom: 0



    #part-of-speech, #semantic-refinement
        a
            padding-left: 1em


    #part-of-speech, #title
        display: inline-block

    /* enable absolute positioning */
    .inner-addon
        position: relative


        /* style icon */
        .glyphicon
          position: absolute
          padding: 10px
          pointer-events: none


    /* align icon */
    .left-addon
        .glyphicon
            left:  0
        input
            padding-left:  30px
    .right-addon
        .glyphicon
            right: 0
        input
            padding-right: 30px

    body
        padding-top: 50px // yay it's the fault of the fixed header


    .tags > li
        background-color: #dff2ff
        padding: 0 3px
        margin-right: 5px
        margin-bottom: 3px

</style>