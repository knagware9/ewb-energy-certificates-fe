<script>
    import { Line } from 'vue-chartjs'
    import { Bar } from 'vue-chartjs'
    export default {
        extends: Bar,
        props: {
            salesLabels: {
                type: Array,
                required: false
            },
            salesData: {
                type: Array | Object,
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
                labels: this.salesLabels,
                datasets: [
                    {
                        label: 'Sale prices',
                        borderColor: '#e8380f',
                        pointBackgroundColor: 'white',
                        borderWidth: 2,
                        pointBorderColor: '#e8380f',
                        backgroundColor: 'transparent',
                        data: this.salesData,
                        type: 'line'
                    }
                ]
            }, this.options)
        }
    }
</script>
