<template>
    <div>
        <h1>Bonjour l'application !</h1>
        <p>
            <!-- utilisez le composant router-link pour la navigation. -->
            <!-- spécifiez le lien en le passant à la prop `to` -->
            <!-- `<router-link>` sera rendu en tag `<a>` par défaut -->
            <router-link to="/node?word=test">Aller à Foo</router-link>
            <router-link to="/search">Aller à Bar</router-link>
        </p>
        <!-- balise pour le composant router-view -->
        <!-- le composant correspondant à la route sera rendu ici -->
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log("Main mounted")
        },

        data() {
            return {errors: null}
        }
    }
</script>