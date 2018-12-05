<template>
    <v-container>
        <v-layout
                text-xs-center
                wrap
        >
            <v-flex xs12>
                <v-img
                        :src="require('../assets/logo-grey.svg')"
                        class="my-3"
                        contain
                        height="200"
                ></v-img>
            </v-flex>

            <v-flex xs12>
                <h2 class="display-2 font-weight-bold mb-3">Buy certificates</h2>
            </v-flex>
            <v-flex xs6>
                <h2>Set price</h2>
                <v-form>
                    <v-container>
                        <v-layout row wrap align-self: center>
                            <v-flex xs12>
                                <v-text-field
                                        label="Price per certificate (1 KWh)"
                                        placeholder="0.02"
                                        type="number"
                                        min="0.01"
                                        step="0.01"
                                        required
                                        box
                                        v-model="buyingPrice"
                                ></v-text-field>

                                <v-btn color="success" v-on:click="this.setPrice">Set price</v-btn>

                                <v-btn color="info" v-on:click="this.getPrice">Get price</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-flex>

            <v-flex xs6>
                <h2>Compensate energy</h2>
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-select
                                        :items="consumers.data"
                                        item-value="id"
                                        item-text="name"
                                        box
                                        label="Importing smart meter"
                                        required
                                        v-model="exportUnipi"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                        label="Import energy (KWh)"
                                        placeholder="10"
                                        type="number"
                                        min="1"
                                        step="1"
                                        required
                                        box
                                        v-model="exportKwh"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-btn color="success" v-on:click="this.generateDemand">Compensate energy</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-flex>

            <v-flex xs12>
                <h2>
                    Your demands
                </h2>
            </v-flex>

            <v-flex xs12>
                <v-data-table
                        :headers="demands.headers"
                        :items="demands.data"
                        hide-actions
                        class="elevation-1"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{ props.item.unipi }}</td>
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.kwh }}</td>
                        <td class="text-xs-left">{{ props.item.price }}</td>
                    </template>
                </v-data-table>
            </v-flex>

            <v-flex xs12>
                <h2>
                    Your energy certificates
                </h2>
            </v-flex>

            <v-flex xs12>
                <v-data-table
                        :headers="certificates.headers"
                        :items="certificates.data"
                        hide-actions
                        class="elevation-1"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{ props.item.unipi }}</td>
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.minimalPrice }}</td>
                        <td class="text-xs-left">{{ props.item.sellingPrice }}</td>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {
        data: () => ({
            buyingPrice: 0.00,
            exportUnipi: '',
            exportKwh: 0,
            toolbar: {
                title: 'Certificate seller'
            },
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
            certificates: {
                headers: [
                    {text: 'Unipi', value: 'unipi'},
                    {text: 'Id', value: 'id'},
                    {text: 'Minimal price', value: 'minimalPrice'},
                    {text: 'Price sold', value: 'sellingPrice'},
                ],
                data: []
            },
            demands: {
                headers: [
                    {text: 'Unipi', value: 'unipi'},
                    {text: 'Id', value: 'id'},
                    {text: 'KWh', value: 'kwh'},
                    {text: 'Price', value: 'price'}
                ],
                data: []
            }
        }),
        mounted() {
            this.getMyDemands(function (that) {
                let dataLen = that.demands.data.length;
                for (let i = 0; i < dataLen; i++) {
                    axios({
                        method: 'get',
                        url: 'http://localhost:8000/certificate/getAllByDemand/' + that.demands.data[i].id,
                    })
                        .then(function (response) {
                            if (response.data.length > 0) {
                                // that.certificates.data.concat(response.data);
                                that.certificates.data = that.certificates.data.concat(response.data);
                            }
                        })
                        .catch(e => {
                            console.log(e);
                        });
                }
            });
            this.getPrice();
        },
        methods: {
            setPrice() {
                let postBody = {
                    buyingPrice: this.buyingPrice
                };
                let that = this;
                axios({
                    method: 'put',
                    url: 'http://localhost:8000/user/buyer/buying-price/update',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                        if (response.data === 'User updated successfully!') {
                            //that.priceUpdateSuccess.value = 'true'
                        }
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            getPrice() {
                let that = this;
                axios({
                    method: 'get',
                    url: 'http://localhost:8000/user/buyer/get',
                })
                    .then(function (response) {
                        that.buyingPrice = response.data.buyingPrice
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            generateDemand() {
                let postBody = {
                    kwh: this.exportKwh,
                    price: this.buyingPrice,
                    unipi: this.exportUnipi
                };
                let that = this;
                axios({
                    method: 'post',
                    url: 'http://localhost:8000/demand/create',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                        that.demands.data = [];
                        that.getMyDemands();
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            async getMyDemands(callback) {
                let that = this;
                let dataLen = this.consumers.data.length;
                let i = 0;
                for (i = 0; i < dataLen; i++) {
                    axios({
                        method: 'get',
                        url: 'http://localhost:8000/demand/getAllByUniPi/' + this.consumers.data[i].id,
                    })
                        .then(function (response) {
                            if (response.data.length > 0) {
                                // that.certificates.data.concat(response.data);
                                that.demands.data = that.demands.data.concat(response.data);
                                callback(that);
                            }

                        })
                        .catch(e => {
                            console.log(e);
                        });
                }
            },
            getMyCertificates() {
                let that = this;
                let dataLen = this.demands.data.length;
                let i = 0;
                for (i = 0; i < dataLen; i++) {
                    axios({
                        method: 'get',
                        url: 'http://localhost:8000/certificates/getAllByDemand/' + this.demands.data[i].id,
                    })
                        .then(function (response) {
                            if (response.data.length > 0) {
                                // that.certificates.data.concat(response.data);
                                that.certificates.data = that.certificates.data.concat(response.data);
                            }
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
