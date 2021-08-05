<template>
    <v-app id="main">
        <nav-bar v-if="auth" ref="navbar"></nav-bar>
        <app-header v-if="auth"  @toggle-drawer="$refs.navbar.drawer = !$refs.navbar.drawer"></app-header>
        <v-main :style="auth?'padding: 64px 0px 0px 256px;':''" >
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import navBar from '../components/navBar'
import appHeader from '../components/header'

export default {
    components: { navBar, appHeader },
    data() {
        return {
            show: false
        }
    },
    computed: {
        auth() {
            return this.$store.getters.auth
        },
        drawer: {
          get() {
            return this.$store.state.drawer;
          },
          set(val) {
            this.$store.commit("SET_DRAWER", val);
          }
        },
    },
    mounted() {
       
        if (this.auth) {
            this.show = true
        }

    },
    methods: {},
    watch: {
        auth(val) {
            if (val) {
                this.show = true
            }
        }
    }

}

</script>
<style>
.padding
{
  padding: 64px 0px 0px 256px;
}

</style>
