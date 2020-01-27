<template>
    <div>
        <h1>SPA Demo App</h1>

        <p>
            <router-link :to="{ name: 'table' }" tag="button">Table</router-link> |
            <router-link :to="{ name: 'about' }" tag="button">About</router-link>
        </p>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {}
</script>