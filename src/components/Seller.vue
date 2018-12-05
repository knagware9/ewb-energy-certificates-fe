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
                        height="150"
                ></v-img>
            </v-flex>


            <v-flex xs12>
                <h2 class="display-2 font-weight-bold mb-3">Sell certificates</h2>
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
                                        v-model="sellingPrice"
                                ></v-text-field>
                                <v-btn color="success" v-on:click="this.setSellingPrice">Set price</v-btn>
                                <v-btn color="info" v-on:click="this.getPrice">Get price</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-flex>

            <v-flex xs6>
                <v-alert
                        type="success"
                        dismissible
                        ref="priceUpdateSuccess"
                >
                    Price update was saved in the Blockchain.
                </v-alert>
                <h2>Export energy</h2>
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-select
                                        :items="producers.data"
                                        item-value="id"
                                        item-text="name"
                                        box
                                        label="Exporting smart meter"
                                        required
                                        v-model="exportUnipi"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field
                                        label="Export energy (KWh)"
                                        placeholder="10"
                                        type="number"
                                        min="1"
                                        step="1"
                                        required
                                        box
                                        v-model="exportKwhs"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-btn color="success" v-on:click="this.generateCertificates">Generate certificates
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-flex>

            <v-flex mb-4>
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
            <!--
                        <v-flex xs12>
                            <h2>Producers</h2>
                            <v-data-table
                                    :headers="producers.headers"
                                    :items="producers.data"
                                    hide-actions
                                    class="elevation-1"
                            >
                                <template slot="items" slot-scope="props">
                                    <td class="text-xs-left">{{ props.item.id }}</td>
                                </template>
                            </v-data-table>
                        </v-flex>

                        -->
        </v-layout>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {
        data: () => ({
            sellingPrice: 0.00,
            exportUnipi: '',
            exportKwhs: 0,
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
            certificates: {
                headers: [
                    {text: 'Unipi', value: 'unipi'},
                    {text: 'Id', value: 'id'},
                    {text: 'Minimal price', value: 'minimalPrice'},
                    {text: 'Price sold', value: 'sellingPrice'},
                ],
                data: []
            }
        }),
        mounted() {
            this.getMyCertificates();
            this.getPrice();
        },
        methods: {
            setSellingPrice() {
                let postBody = {
                    sellingPrice: this.sellingPrice
                };
                let that = this;
                axios({
                    method: 'put',
                    url: 'http://localhost:8000/user/seller/selling-price/update',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                        if (response.data === 'User updated successfully!') {
                            that.priceUpdateSuccess.value = 'true'
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
                    url: 'http://localhost:8000/user/seller/get',
                })
                    .then(function (response) {
                        that.sellingPrice = response.data.sellingPrice
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            generateCertificates() {
                this.getPrice()
                let postBody = {
                    minimalPrice: this.sellingPrice,
                    unipi: this.exportUnipi,
                    kwhs: this.exportKwhs
                };
                let that = this;
                axios({
                    method: 'post',
                    url: 'http://localhost:8000/certificate/create',
                    data: postBody,
                    config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                })
                    .then(function (response) {
                            //that.priceUpdateSuccess.value = 'true'
                            that.certificates.data = [];
                            that.getMyCertificates();
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            getMyCertificates() {
                let that = this;
                let dataLen = this.producers.data.length;
                let i = 0;
                for (i = 0; i < dataLen; i++) {
                    axios({
                        method: 'get',
                        url: 'http://localhost:8000/certificate/getAllByUniPi/' + this.producers.data[i].id,
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
