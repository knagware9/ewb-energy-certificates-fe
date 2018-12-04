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
                <h2 class="display-2 font-weight-bold mb-3">Sell certificates</h2>
                <v-alert
                        type="success"
                        dismissible
                        ref="priceUpdateSuccess"
                >
                    Price update was saved in the Blockchain.
                </v-alert>
                <h2>Set price</h2>
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs12 sm6 md3>
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
                            </v-flex>
                            <v-flex xs12 sm6 md3>
                                <v-btn color="success" v-on:click="this.setSellingPrice">Set price</v-btn>
                            </v-flex>
                            <v-flex xs12 sm6 md3>
                                <v-btn color="info" v-on:click="this.getPrice">Get price</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-flex>

            <v-flex xs12>
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
                            <v-flex xs12 sm6 d-flex>
                                <v-select
                                        :items="producers.data"
                                        item-value="id"
                                        item-text="name"
                                        box
                                        label="Producer"
                                        required
                                        v-model="exportUnipi"
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm6 md3>
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
                            <v-flex xs12 sm6 md3>
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
                        :headers="seller.certificates.headers"
                        :items="seller.certificates.data"
                        hide-actions
                        class="elevation-1"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{ props.item.unipi.name }}</td>
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.price }}</td>
                        <td class="text-xs-left">{{ props.item.generated }}</td>
                        <td class="text-xs-left">{{ props.item.sold }}</td>
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
            seller: {
                certificates: {
                    headers: [
                        {text: 'Unipi', value: 'unipi'},
                        {text: 'Id', value: 'id'},
                        {text: 'Price', value: 'price'},
                        {text: 'Generated', value: 'generated'},
                        {text: 'Sold', value: 'sold'},
                    ],
                    data: [
                        {
                            id: '932828fae3ef07b7fe8c7300b061f765',
                            price: '0.02',
                            generated: '2018-12-03 22:41',
                            sold: '',
                            unipi:
                                {
                                    name: 'UniPi1 - Hauptstrasse 342'
                                }
                        },
                        {
                            id: 'afe9bfdea0c7ea66d638e56e158b192b',
                            price: '0.03',
                            generated: '2018-12-02 22:41',
                            sold: '',
                            unipi:
                                {
                                    name: 'UniPi2 - Hauptstrasse 344'
                                }
                        },
                        {
                            id: '932828fae3ef07b7fe8c7300b061f745',
                            price: '0.04',
                            generated: '2018-12-01 22:41',
                            sold: '2018-12-02 22:41',
                            unipi:
                                {
                                    name: 'UniPi2 - Hauptstrasse 344'
                                }
                        },
                        {
                            id: '28898a6cf5301fc6aa649355e089ebe4',
                            price: '0.01',
                            generated: '2018-11-30 22:41',
                            sold: '2018-11-30 23:41',
                            unipi:
                                {
                                    name: 'UniPi1 - Hauptstrasse 342'
                                }
                        }
                    ]
                }
            }
        }),
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
                        if (response.data === 'User updated successfully!') {
                            that.priceUpdateSuccess.value = 'true'
                        }
                    })
                    .catch(e => {
                        console.log(e);
                    });
            }
        }
    }
</script>

<style>

</style>
