<template>
    <table v-if="posts.length">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
        <post-item v-for="post in posts" :post="post" :key="post.id" />
    </table>
</template>

<script>
    import { mapGetters } from "vuex";
    import postItem from "./PostItem";
    export default {
        components: {
            postItem
        },
        name: "PostsTable",
        mounted() {
            this.$store.dispatch("GET_POSTS");
        },
        computed: {
            ...mapGetters(["posts"]),
        },
    };
</script>