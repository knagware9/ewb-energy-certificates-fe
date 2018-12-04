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

            <v-flex xs12>
                    <line-chart v-if="loaded" :chart-data="certificatePrices" :chart-labels="labels"></line-chart>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import axios from 'axios'
    import LineChart from '@/components/LineChart'

    export default {
        components: {
            LineChart
        },
        props: {},
        data() {
            return {
                package: null,
                period: 'last-month',
                loaded: false,
                certificatePrices: [],
                showError: false,
                labels: [],
            }
        },
        mounted() {
            this.requestData()
        },
        methods: {
            resetState() {
                this.loaded = false
                this.showError = false
            },
            requestData() {
                this.resetState()
                axios.get('https://www.4eyes.ch/certificates.json')
                    .then(response => {
                        console.log(response.data)
                        this.certificatePrices = response.data.certificates.map(certificate => certificate.price*100),
                        this.labels = new Array(50),
                        this.loaded = true
                    })
                    .catch(err => {
                        this.errorMessage = err.response.data.error
                        this.showError = true
                    })
            }
        }
    }
</script>
