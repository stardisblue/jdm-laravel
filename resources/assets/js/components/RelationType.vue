<template>
    <div class="relation-type">
        <a :id="'rt'+index" class="anchor"></a> <!-- anchor -->
        <!-- relation-type-header -->
        <div class="relation-type-header">
            <h3><a :href="'#rt'+index"><i class="glyphicon glyphicon-link"></i></a>
                {{relationType.name}}</h3>

            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-filter"></span>
                    <span v-if="order === 'weight'">Poids</span>
                    <span v-if="order === 'name'">Nom</span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li :class="{active: order === 'weight'}"><a @click="order =
                    'weight'">
                        Poids</a></li>
                    <li
                            :class="{active: order === 'name'}"><a
                            @click="order = 'name'">Nom
                    </a></li>
                </ul>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-default"
                        :class="{active: this.sortBy === 'asc'}" :disabled="sortBy === 'asc'" @click="sortBy = 'asc'">
                    <span class="glyphicon glyphicon-sort-by-attributes"></span></button>
                <button type="button" class="btn btn-default"
                        :class="{active: this.sortBy === 'desc'}" :disabled="sortBy === 'desc'"
                        @click="sortBy = 'desc'">
                    <span class="glyphicon glyphicon-sort-by-attributes-alt"></span></button>
            </div>

            <div class=" inner-addon left-addon">
                <i class="glyphicon glyphicon-search"></i>
                <input type="text" :value="search" @input="searchWord($event.target.value)" class="form-control"
                       placeholder="Rechercher">
            </div>
        </div>
        <!-- definition -->
        <div class="relation-type-content">
            <p class="definition"><b>Définition :</b>{{relationType.description}}</p>

            <div v-if="inbound.rels.length > 0" class="relations-in">
                <p><u>Relations Entrantes</u></p>
                <ul class="list-inline tags">
                    <li v-for="relation in inbound.rels">
                        <word :id="relation.id" :word="relation.node" :weight="relation.weight"
                              @card="displayCard" @uncard="destroyCard"></word>
                    </li>
                    <li v-if="inbound.next">
                        <button class="btn btn-xs btn-default" @click="nextPageIn" :disabled="inbound.loading">&gt;
                            voir plus...
                        </button>
                    </li>
                </ul>
            </div>
            <div v-if="outbound.rels.length > 0" class="relations-out">
                <p><u>Relations Sortantes</u></p>
                <ul class="list-inline tags">
                    <li v-for="relation in outbound.rels">
                        <word :id="relation.id" :word="relation.node" :weight="relation.weight"
                              @card="displayCard" @uncard="destroyCard"></word>
                    </li>
                    <li v-if="outbound.next">
                        <button class="btn btn-xs btn-default" @click="nextPageOut" :disabled="outbound.loading">&gt;
                            voir plus...
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <hr/>
    </div>
</template>

<script>
    import Word from "./Word.vue"

    export default {
        props: {
            relationType: {
                type: Object,
                required: true,
            },
            index: {
                type: Number,
                required: true,
            },
            nodeId: {
                type: Number,
                required: true,
            },
        },

        data() {
            return {
                search: "",
                order: "weight",
                sortBy: 'desc',
                inbound: {
                    rels: null,
                    page: 1,
                    next: false,
                    loading: false,
                },
                outbound: {
                    rels: null,
                    page: 1,
                    next: false,
                    loading: false,
                }
            }
        },

        created() {
            this.inbound.rels = this.relationType.relations.in;
            this.inbound.next = this.inbound.rels.length === 30;
            this.outbound.rels = this.relationType.relations.out;
            this.outbound.next = this.outbound.rels.length === 30
        },

        mounted() {
            console.log('RelationType ' + this.relationType.name + ' mounted');
        },

        components: {
            "word": Word
        },

        watch: {
            heritedSearch: function (value) {
                this.search = value
            },
            relationTypes: function () {
                this.inbound.rels = this.relationType.relations.in;
                this.outbound.rels = this.relationType.relations.out;
            },

            order: function () {
                this.resetPageIn();
                this.resetPageOut();
            },
            sortBy: function () {
                this.resetPageIn();
                this.resetPageOut();
            },
        },

        methods: {
            displayCard(value) {//parent delegation
                this.$emit("card", value);
            },

            destroyCard() {//parent delegation
                this.$emit("uncard");
            },

            searchWord: _.debounce(function (search) {
                this.search = search;
                if (search === "") {
                    this.resetPageIn();
                    this.resetPageOut()
                } else {
                    const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/search/';
                    console.log(url);

                    axios.get(url, {params: {q: search}})
                        .then((response) => {
                            this.inbound.page = 1;
                            this.inbound.rels = response.data.in;
                            this.inbound.next = false;
                            this.outbound.page = 1;
                            this.outbound.rels = response.data.out;
                            this.outbound.next = false;

                            this.$emit('updateOffsetTop')
                        })
                        .catch((error) => {
                            console.log(error);
                        })

                }
            }, 300),

            resetPageIn() {
                const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/in/';
                axios.get(url, {params: {orderBy: this.order, sort: this.sortBy}})
                    .then((response) => {
                        this.inbound.page = 1;
                        this.inbound.rels = response.data.results;

                        if (this.inbound.rels.length === response.data.count) {
                            this.inbound.next = false;
                        }

                        this.$emit('updateOffsetTop')
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },

            resetPageOut() {
                const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/out/';
                axios.get(url, {params: {orderBy: this.order, sort: this.sortBy}})
                    .then((response) => {
                        this.outbound.page = 1;
                        this.outbound.rels = response.data.results;

                        if (this.outbound.rels.length === response.data.count) {
                            this.outbound.next = false;
                        }

                        this.$emit('updateOffsetTop')
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },

            nextPageIn() {
                if (this.inbound.next) {// failsafe
                    this.inbound.loading = true;
                    const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/in/' + this.inbound.page;
                    axios.get(url, {params: {orderBy: this.order, sort: this.sortBy}})
                        .then((response) => {
                            this.inbound.page++;
                            this.inbound.rels = _.concat(this.inbound.rels, response.data.results);

                            if (this.inbound.rels.length === response.data.count) {
                                this.inbound.next = false;
                            }
                            this.inbound.loading = false;

                            this.$emit('updateOffsetTop')
                        })
                        .catch((error) => {
                            console.log(error);
                            this.inbound.loading = false
                        })
                }
            },

            nextPageOut() {
                if (this.outbound.next) {// failsafe
                    this.outbound.loading = true;
                    const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/out/' + this.outbound.page;
                    axios.get(url, {params: {orderBy: this.order, sort: this.sortBy}})
                        .then((response) => {
                            this.outbound.page++;
                            this.outbound.rels = _.concat(this.outbound.rels, response.data.results);

                            if (this.outbound.rels.length === response.data.count) {
                                this.outbound.next = false;
                            }
                            this.outbound.loading = false;

                            this.$emit('updateOffsetTop')
                        })
                        .catch((error) => {
                            console.log(error)
                            this.outbound.loading = false;
                        })
                }

            },
        },

    }
</script>
<style>
    .relation-type-header h3 a {
        float: left;
        padding-right: 4px;
        padding-top: 4px;
        margin-left: -16px;
        line-height: 1;
    }

    .relation-type-header h3 a i {
        font-size: 14px;
        visibility: hidden;
    }

    .relation-type-header h3:hover a i {
        visibility: visible;
    }

    .relation-type hr {
        margin-bottom: 0;
    }

    .relation-type-header {
        display: flex;
        align-items: center;
    }

    .relation-type-header h3 {
        flex-grow: 1;
    }

    .relation-type-header button span.glyphicon {
        line-height: 1.6;
    }

    .definition:before {
        content: "» ";
        font-weight: bold;
    }

    .relations-in > ul {
        border-left: 2px blue solid;
        padding-left: 1em;
    }

    .relations-out > ul {
        border-left: 2px green solid;
        padding-left: 1em;
    }

    a.anchor {
        display: block;
        position: relative;
        top: -50px;
        visibility: hidden;
    }

</style>