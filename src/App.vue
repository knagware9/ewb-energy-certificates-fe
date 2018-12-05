<template>
    <v-app>
        <v-navigation-drawer app>
            <v-list dense>
                <v-list-tile :to="'/'">
                    <v-list-tile-action>
                        <v-icon>home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Home</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile :to="'/seller'">
                    <v-list-tile-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Seller</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile :to="'/buyer'">
                    <v-list-tile-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Buyer</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile :to="'/market'">
                    <v-list-tile-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Current market</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <!--<v-toolbar app>
            <v-toolbar-title class="headline text-uppercase">
                <span>EWB energy certificate platform</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>

        </v-toolbar>-->

        <v-content>
            <v-fade-transition mode="out-in">
                <router-view></router-view>
            </v-fade-transition>
        </v-content>
    </v-app>
</template>

<script>
    import Home from './components/Home'

    export default {
        name: 'App',
        components: {
            Home
        },
        data() {
            return {
                //
            }
        }
    }
</script>
