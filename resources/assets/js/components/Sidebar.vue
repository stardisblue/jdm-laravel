<template>
    <div id="sidebar">
        <ul class="nav">
            <li v-for="value in relationTypes">
                <a :href="'#'+value.id">{{value.name}}</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Sidebar mounted : ');
        },

        props: ["relationTypes"]
    }
</script>