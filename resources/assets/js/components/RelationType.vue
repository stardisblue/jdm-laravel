<template>
    <div class="relation-type">
        <a :id="'rt'+index" class="anchor"></a>
        <div class="relation-type-header">
            <div class="h3" :title="relationType.description">{{relationType.name}}
            </div>
            recherche, filtres closing button
        </div>
        <div v-if="inbound.rels.length > 0" class="relations-in">
            <p>Relations Entrantes</p>
            <ul class="list-inline">
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
            <p>Relations Sortantes</p>
            <ul class="list-inline">
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
            }
        },

        data() {
            return {
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
            relationTypes: function () {
                this.inbound.rels = this.relationType.relations.in;
                this.outbound.rels = this.relationType.relations.out
            }
        },

        methods: {
            displayCard(value) {//parent delegation
                this.$emit("card", value);
            },

            destroyCard() {//parent delegation
                this.$emit("uncard");
            },

            nextPageIn() {
                if (this.inbound.next) {// failsafe
                    this.inbound.loading = true
                    const url = '/api/node/' + this.nodeId + '/relation-type/' + this.relationType.id + '/in/' + this.inbound.page;
                    axios.get(url)
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
                    axios.get(url)
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

            /**
             inOrderByWeight: function (order = "desc") {
                this.relationType.relations['in'] = _.orderBy(this.relationType.relations.in, ['weight', 'node.name'],
                    [order, 'asc']);
            },

             inOrderByName: function (order = "asc") {
                this.relationType.relations['in'] = _.orderBy(this.relationType.relations.in, ['node.name'], [order]);
            },
             outOrderByWeight: function (order = "desc") {
                this.relationType.relations.out = _.orderBy(this.relationType.relations.out, ['weight', 'node.name'],
                    [order, 'asc']);
            },

             outOrderByName: function (order = "asc") {
                this.relationType.relations.out = _.orderBy(this.relationType.relations.out, ['node.name'], [order]);
            }**/
        },

    }
</script>
<style>
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
        top: -70px;
        visibility: hidden;
    }
</style>