<template>
    <v-app-bar app color="primary" dark flat>
        <v-app-bar-nav-icon @click.stop="$emit('toggle-drawer')"></v-app-bar-nav-icon>
        <v-spacer />
        <div class="mx-3" />
        <v-menu offset-y class="avatar-menu">
            <template v-slot:activator="{ attrs, on }">
                <v-avatar class="ml-4" v-bind="attrs" v-on="on">
                    <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John" />
                </v-avatar>
            </template>
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>MD Imtiaz Ahmed</v-list-item-title>
                        <v-list-item-subtitle>CEO </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>Setting</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link @click="logout">
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
    name: "Header",
    computed: {
        ...mapState(["drawer"]),
    },
    methods: {
        ...mapMutations({
            setDrawer: "SET_DRAWER"
        }),
        async logout() {
            this.$store.dispatch('logout')
        },
    }
}

</script>
<style scoped>
.v-application a {
    color: transparent !important;
}

.v-app-bar.v-app-bar--fixed {
    z-index: 9;
    left: 0px !important;
}

.v-toolbar__title a {
    display: inline-flex;
    margin-top: 10px;
}

.logo-name {
    color: #fff;
    margin-left: 10px;
    font-weight: 600;
}

.avatar-menu .v-menu__content::before {
    content: "";
    width: 0px;
    height: 0px;
    border-top: solid 7px #fff;
    border-left: solid 7px transparent;
    border-right: solid 7px transparent;
    top: 30px;
    position: absolute;
    margin: 0 auto;
    text-align: center;
}

</style>
