<script>
    import { Line } from 'vue-chartjs'
    export default {
        extends: Line,
        props: ['dates', 'data'],
        watch:{
            dates(newVal){
                this.dates_ = newVal;
            },
            data(newVal){
                this.data_ = newVal;
                this.fillCharts(this.dates_,this.data_);
            },

        },
        data() {
            return {
                period: 'Day',
                dates_:[],
                data_:[]
            }
        },
        mounted () {
            // Overwriting base render method with actual data.
            this.fillCharts(this.dates_,this.data_);

        },
        methods:{
            fillCharts(dates,data){
                this.renderChart({
                    labels: dates,
                    datasets: [
                        {
                            label: 'Ven.vc response time',
                            backgroundColor: 'rgba(75, 195, 243, 0.30)',
                            borderColor: 'rgba(75, 195, 243)',
                            borderWidth:2,
                            hoverRadius:1,
                            data: data,
                            radius:1
                        }
                    ]
                })
            }
        }
    }
</script>
