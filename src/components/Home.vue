<template>
    <v-container>
        <v-alert
                :value="alert"
                type="success"
                transition="scale-transition"
        >
            This is a success alert.
        </v-alert>
        <v-layout
                text-xs-center
                wrap
        >
            <v-flex xs12>
                <v-img
                        :src="require('../assets/logo-grey.svg')"
                        class="my-3"
                        contain
                        height="150"
                ></v-img>
            </v-flex>

            <v-flex mb-4>
                <h1 class="display-2 font-weight-bold mb-3">
                    EWB energy certificate trading platform
                </h1>
                <p>Based on Hyperledger Fabric Blockchain Technology</p>
            </v-flex>
        </v-layout>
        <v-form>
            <v-container>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md3>
                        <v-btn color="success" :loading="loading1" :disabled="loading1" @click="loader = 'loading1'" v-on:click="this.createSeller">Create seller</v-btn>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-btn color="success" :loading="loading2" :disabled="loading2" @click="loader = 'loading2'" v-on:click="this.createBuyer">Create buyer</v-btn>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-btn color="success" :loading="loading3" :disabled="loading3" @click="loader = 'loading3'" v-on:click="this.createSellerUniPis">Create seller's unipis</v-btn>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                        <v-btn color="success" :loading="loading4" :disabled="loading4" @click="loader = 'loading4'" v-on:click="this.createBuyerUniPis">Create buyers' unipis</v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
    </v-container>

</template>

<style>
    .custom-loader {
        animation: loader 1s infinite;
        display: flex;
    }
    @-moz-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @-webkit-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @-o-keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
    @keyframes loader {
        from {
            transform: rotate(0);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    import axios from 'axios';

    export default {
        data: () => ({
            loader: null,
            loading1: false,
            loading2: false,
            loading3: false,
            loading4: false,
            alert: false,
            producers: {
                data: [
                    {
                        id: "ewb_granatweg6bern_grid_export3p",
                        name: "Granatweg 6"
                    },
                    {
                        id: "ewb_granatweg8bern_grid_export3p",
                        name: "Granatweg 8"
                    },
                    {
                        id: "ewb_granatweg9bern_grid_export3p",
                        name: "Granatweg 9"
                    },
                    {
                        id: "ewb_granatweg10bern_grid_export3p",
                        name: "Granatweg 10"
                    },
                    {
                        id: "ewb_granatweg11bern_grid_export3p",
                        name: "Granatweg 11"
                    },
                    {
                        id: "ewb_granatweg13bern_grid_export3p",
                        name: "Granatweg 13"
                    }
                ]
            },
            consumers: {
                data: [
                    {
                        id: "ewb_kohlestromallee6bern_grid_import3p",
                        name: "Kohlestromallee 6"
                    },
                    {
                        id: "ewb_kohlestromallee8bern_grid_import3p",
                        name: "Kohlestromallee 8"
                    },
                    {
                        id: "ewb_kohlestromallee9bern_grid_import3p",
                        name: "Kohlestromallee 9"
                    },
                    {
                        id: "ewb_kohlestromallee10bern_grid_import3p",
                        name: "Kohlestromallee 10"
                    },
                    {
                        id: "ewb_kohlestromallee11bern_grid_import3p",
                        name: "Kohlestromallee 11"
                    },
                    {
                        id: "ewb_kohlestromallee13bern_grid_import3p",
                        name: "Kohlestromallee 13"
                    }
                ]
            },
        }),
        watch: {
            loader () {
                const l = this.loader
                this[l] = !this[l]

                setTimeout(() => (this[l] = false), 3000)

                this.loader = null
            }
        },
        methods: {
            createBuyer() {
                this.alert = false;
                let postBody = {
                    username: 'buyer',
                    name: "Max Köhler",
                    street: "Braunkohlegasse 12",
                    postalCode: "3003",
                };
                let that = this;
                axios({
                    method: 'post',
                    url: 'http://localhost:8000/user/create',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                        that.alert = true;
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            createSeller() {
                this.alert = false;
                let postBody = {
                    username: 'seller',
                    name: "Miriam Müller",
                    street: "Sonnenweg 15",
                    postalCode: "5003",
                };
                let that = this;
                axios({
                    method: 'post',
                    url: 'http://localhost:8000/user/create',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                        that.alert = true;
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            createSellerUniPis() {
                this.alert = false;
                for (var i = 0, len = this.producers.data.length; i < len; i++) {
                    let postBody = {
                        username: 'seller',
                        name: this.producers.data[i].name,
                        id: this.producers.data[i].id
                    };
                    console.log(postBody);
                    let that = this;
                    axios({
                        method: 'post',
                        url: 'http://localhost:8000/unipi/create',
                        data: postBody,
                        config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                    })
                        .then(function (response) {
                            that.alert = true;
                        })
                        .catch(e => {
                            console.log(e);
                        });
                }
            },
            createBuyerUniPis() {
                this.alert = false;
                for (var i = 0, len = this.consumers.data.length; i < len; i++) {
                    let postBody = {
                        username: 'buyer',
                        name: this.consumers.data[i].name,
                        id: this.consumers.data[i].id
                    };
                    console.log(postBody);
                    let that = this;
                    axios({
                        method: 'post',
                        url: 'http://localhost:8000/unipi/create',
                        data: postBody,
                        config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                    })
                        .then(function (response) {
                            that.alert = true;
                        })
                        .catch(e => {
                            console.log(e);
                        });
                }
            }
        }
    }
</script>

<style>

</style>
