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
                <h2 class="display-2 font-weight-bold mb-3">Current market</h2>
            </v-flex>


            <v-flex xs4 sm2 offset-sm3>
                <v-card color="#E8800F" style="display: flex; align-items: center; justify-content: center;">
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">{{ numberOfCertificates }}</h3>
                            <div># KWh</div>
                        </div>
                    </v-card-title>

                </v-card>
            </v-flex>

            <v-flex xs6 sm2 offset-sm1>
                <v-card color="#E8800F" style="display: flex; align-items: center; justify-content: center;">
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">{{ totalRevenue }}</h3>
                            <div>CHF earned</div>
                        </div>
                    </v-card-title>

                </v-card>
            </v-flex>

            <v-flex xs12>
                <div style="height: 30px"></div>
            </v-flex>

            <v-flex xs12>
                <h2>Current volume & prices</h2>
            </v-flex>

            <v-flex xs6>
                <bar-chart v-if="loaded" :chart-data="certificatePrices" :chart-labels="labels"
                           :demand-data="demandData" :demand-chart-labels="labels"></bar-chart>
            </v-flex>

            <v-flex xs6>
                <line-chart v-if="loaded" :sales-data="salesData" :sales-labels="salesLabels"></line-chart>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import axios from 'axios'
    import LineChart from '@/components/LineChart'
    import BarChart from '@/components/BarChart'

    export default {
        components: {
            BarChart,
            LineChart
        },
        props: {},
        data() {
            return {
                package: null,
                period: 'last-month',
                loaded: false,
                certificatePrices: [],
                demandData: [],
                salesData: [],
                salesLabels: [],
                showError: false,
                labels: [],
                numberOfCertificates: 0,
                totalRevenue: 0
            }
        },
        mounted() {
            this.requestData();
            this.initReload();
        },
        methods: {
            resetState() {
                //this.loaded = false
                this.showError = false
                this.certificatesReady = false;
                this.demandsReady = false;
            },
            requestData(reload = false) {
                this.resetState();
                axios.get('http://localhost:8000/certificate/all')
                    .then(response => {
                            let tmpData = response.data.map(certificate => certificate.minimalPrice * 100);
                            let tmpPrices = response.data.map(certificate => certificate.sellingPrice * 100);
                            console.log(tmpPrices);
                            if (reload === true) {
                                let numberOfElements = this.certificatePrices.length;

                                tmpData.splice(0, numberOfElements);
                                if (tmpData.length > 0) {
                                    this.loaded = false;
                                    this.certificatePrices.push(tmpData);
                                }

                                let numberOfPriceElements = this.salesData.length;
                                tmpPrices.splice(0, numberOfPriceElements);
                                if (tmpPrices.length > 0) {
                                    this.loaded = false;
                                    this.salesData.push(tmpData);
                                }
                            } else {
                                this.loaded = false;
                                this.certificatePrices = tmpData;
                                this.salesData = tmpPrices;
                            }
                            this.labels = new Array(this.certificatePrices.length);
                            this.salesLabels = new Array(this.salesData.length);
                            this.certificatesReady = true;
                            this.loaded = this.certificatesReady && this.demandsReady;

                        }
                    ).then(
                    axios.get('http://localhost:8000/demand/all')
                        .then(response => {
                            let tmpData = response.data.map(demand => demand.price * 100);
                            if (reload === true) {
                                let numberOfElements = this.demandData.length;

                                tmpData.splice(0, numberOfElements);
                                if (tmpData.length > 0) {
                                    this.loaded = false;
                                    this.demandData.push(tmpData);
                                }
                            } else {
                                this.loaded = false;
                                this.demandData = tmpData;
                            }
                            this.demandsReady = true;
                            this.loaded = this.certificatesReady && this.demandsReady;
                        })
                        .catch(err => {
                            this.errorMessage = err.response.data.error
                            this.showError = true
                        })
                )
                    .catch(err => {
                        this.errorMessage = err.response.data.error
                        this.showError = true
                    });


            },
            initReload() {
                setInterval(this.reloadData, 3000);
            }
            ,
            reloadData() {
                this.requestData(true);
                this.numberOfCertificates = this.certificatePrices.length
                let len;
                len = this.salesData.length;
                let i;
                let revenue = 0;
                for(i = 0; i < len; i++) {
                    revenue += parseInt(this.salesData[i]);
                }
                this.totalRevenue = revenue/100;
            }
        }
    }
</script>
