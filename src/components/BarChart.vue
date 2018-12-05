<script>
    import { Line } from 'vue-chartjs'
    import { Bar } from 'vue-chartjs'
    export default {
        extends: Bar,
        props: {
            chartData: {
                type: Array | Object,
                required: false
            },
            chartLabels: {
                type: Array,
                required: false
            },
            demandData: {
                type: Array | Object,
                required: false
            },
            demandLabels: {
                type: Array,
                required: false
            }
        },
        data () {
            return {
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                        xAxes: [ {
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    animation:{
                        duration: 0
                    }
                }
            }
        },
        mounted () {
            this.renderChart({
                labels: this.chartLabels,
                datasets: [
                    {
                        label: 'Ask',
                        borderColor: '#E8800F',
                        pointBackgroundColor: 'white',
                        borderWidth: 0,
                        pointBorderColor: '#E8800F',
                        backgroundColor: '#E8800F',
                        data: this.chartData,
                    },
                    {
                        label: 'Bid',
                        borderColor: '#0fe880',
                        pointBackgroundColor: 'white',
                        borderWidth: 0,
                        pointBorderColor: '#0fe880',
                        backgroundColor: '#0fe880',
                        data: this.demandData,
                    }
                ]
            }, this.options)
        }
    }
</script>
