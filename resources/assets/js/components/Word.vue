<template>
    <a :id="xmlId" class="word" :href="'node?word=' +word.name" @mouseover="onMouseOver" @mouseleave="onMouseLeave">{{
        name()}}</a>
</template>

<script>
    export default {

        props: {
            word: {
                type: Object,
                required: true,
            },
            id: {
                type: Number,
                required: true,
            },
            prefix: {
                type: String,
                default: "r"
            }
        },

        data() {
            return {
                displayCard: null
            }
        },

        methods: {
            name() {
                return this.word.formattedName !== null ? this.word.formattedName : this.word.name;
            },

            onMouseOver() {
                this.displayCard = _.debounce(() => {
                    this.$emit('card', {word: this.word.name, xmlId: this.xmlId})
                }, 500);
                this.displayCard();
            },

            onMouseLeave() {
                this.displayCard.cancel()
                this.$emit('uncard')
            }

        },

        computed: {
            xmlId() {
                return this.prefix + this.id;
            }
        },

        mounted() {
            //console.log('Word mounted : ');
        },
    }
</script>
