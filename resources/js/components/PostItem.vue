<template>
    <tr>
        <td>{{ post.headline }}</td>
        <td>{{ post.user.username }}</td>
        <td>
            <button v-on:click="deletePost(post.id)">Delete</button>
        </td>
    </tr>
</template>
<script>
    export default {
        name: "PostItem",
        props: ["post"],

        methods: {
            deletePost(id) {
                this.$store.dispatch("DELETE_POST", id);
            },
        }
    };
</script>