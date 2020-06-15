<template>
    <v-app-bar app
               color="blue darken-3"
               dark
               elevate-on-scroll
    >
        <router-link :to="{name: 'content'}">
            <v-toolbar-title
                    style="width: 300px"
                    class="ml-0 pl-4"
            >
                <span class="hidden-sm-and-down">WebSolutions</span>
            </v-toolbar-title>
        </router-link>
        <v-spacer></v-spacer>
        <router-link :to="{name: 'loginDomain'}">
            <v-btn active-class="otlined"
                   rounded
            >
                LOGIN
            </v-btn>
        </router-link>
        <router-link :to="{name: 'register'}">
            <v-btn active-class="otlined"
                   rounded
            >
                REGISTER
            </v-btn>
        </router-link>
    </v-app-bar>
</template>

<script>


    export default {
        data: () => ({}),
    }
</script>