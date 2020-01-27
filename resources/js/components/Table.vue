<template>
    <div class="users">
        <p>This is table page</p>
        <div class="loading" v-if="isLoading">
            Loading...
        </div>

        <button v-on:click="refresh()" v-else="isLoading">Refresh</button>

        <posts-table></posts-table>
    </div>
</template>
<script>
    import postsTable from "./PostsTable.vue";
    import { mapGetters } from "vuex";

    export default {
        components: {
            postsTable
        },
        methods: {
            refresh() {
                this.$store.dispatch("GET_POSTS");
            },
        },
        computed: {
            ...mapGetters(["isLoading"]),
        },
    }
</script>