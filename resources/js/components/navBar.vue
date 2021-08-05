<template>
    <v-navigation-drawer v-model="drawer" app>
        <v-list dense expand nav>
            <v-list-item link exact :to="{ name: 'category' }">
                <v-list-item-action>
                    <v-icon>mdi-arrange-bring-forward</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Category</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link exact :to="{ name: 'subcategory' }">
                <v-list-item-action>
                    <v-icon>mdi-arrange-bring-forward</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Sub Category</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link exact :to="{ name: 'products' }">
                <v-list-item-action>
                    <v-icon>mdi-arrange-bring-forward</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Products</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
export default {
    data() {
        return {
              drawer: true
        }
    },
    computed: {
        // drawer: {
        //     get() {
        //         return this.$store.state.drawer;
        //     },
        //     set(val) {
        //         this.$store.commit("SET_DRAWER", val);
        //     }
        // },
    },
    watch:{
        // drawer(val){
        //     console.log(val)
        //     this.drawer=val
        // }
    }
}

</script>
<style scoped>
.v-navigation-drawer {
    position: fixed;
    top: 0px;
    left: 0;
    padding-top: 65px !important;
    min-height: 100vh;
    width: 230px;
    background-color: #fff;
}

</style>
