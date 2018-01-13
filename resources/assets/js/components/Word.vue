<template>
    <a :id="'n'+id" class="word" :href="'node?word=' +word.name" @mouseover="onMouseOver" @mouseleave="onMouseLeave">{{
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
                console.log('enter')
                this.displayCard = _.debounce(() => this.$emit('card', {id: this.id})
                    , 500);
                this.displayCard();
            },

            onMouseLeave() {
                console.log('elave')
                this.displayCard.cancel()
            }

        },

        mounted() {
            //console.log('Word mounted : ');
        },
    }
</script>
