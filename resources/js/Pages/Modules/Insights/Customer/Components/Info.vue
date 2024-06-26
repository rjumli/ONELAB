<template>
   <BCard no-body class="card-height-100" style="height: 540px;">
        <BCardHeader class="align-items-center d-flex">
            <BCardTitle class="mb-0 flex-grow-1">{{year}} Customer Breakdown</BCardTitle>
            <div class="flex-shrink-0">
                <div class="input-group input-group-sm mb-0">
                   
                    <b-button @click="previous(this.year)" type="button" variant="primary">
                        <i class="ri-arrow-left-s-fill"></i> Prev
                    </b-button> 
                    <span class="input-group-text"><span class="me-4 ms-4">{{this.year}}</span></span>
                     <b-button @click="next(this.year)" type="button" variant="primary">
                         Next <i class="ri-arrow-right-s-fill"></i>
                    </b-button>
                </div>
            </div>
        </BCardHeader>
         <BCardHeader class="card-header p-0 border-0 bg-light-subtle">
            <div class="row g-0 text-center">
                <div class="col-sm-4" v-for="(list,index) in total" v-bind:key="index">
                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                        <h5 class="mb-1 text-primary"><span>{{list.value}}</span></h5>
                        <p class="text-muted mb-0">{{list.name}}</p>
                    </div>
                </div>
            </div>
        </BCardHeader>
        <BCardBody>
            <apexchart ref="realtimeChart" class="apex-charts mb-4" type="area" dir="ltr" height="350" :series="series"
                :options="chartOptions">
            </apexchart>
        </BCardBody>
    </BCard>
</template>
<script>
import _ from 'lodash';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            total: [],
            year: new Date().getFullYear(),
            series: [{type: "area"},{type: "bar"},{type: "line"}],
            chartOptions: {
                chart: {height: 370,type: "line",toolbar: {show: false,},},
                stroke: {curve: "straight", dashArray: [0, 0, 8],width: [2, 0, 2.2]},
                fill: {opacity: [0.1, 0.9, 1]},
                markers: {
                    size: [0, 0, 0],
                    strokeWidth: 2,
                    hover: { size: 4},
                },
                xaxis: {
                    categories: [],
                    axisTicks: {show: false},
                    axisBorder: {show: false},
                },
                grid: {
                    show: true,
                    xaxis: {lines: {show: true}},
                    yaxis: {lines: { show: false}},
                    padding: { top: 0,right: -2,bottom: 15,left: 10,},
                },
                legend: {
                    show: true,
                    horizontalAlign: "center",
                    offsetX: 0,
                    offsetY: -5,
                    markers: {width: 9,height: 9,radius: 6},
                    itemMargin: { horizontal: 10, vertical: 0},
                },
                 dataLabels: {
                    enabled: false, 
                },
                plotOptions: {
                bar: {
                    columnWidth: "50%",
                    barHeight: "70%",
                },
                },
                colors: ["#34c38f", "#556ee6", "#ea6868", "#f1b44c", "#a20cce", " #713d3d"],
            },
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl + '/insights/customers',{
                params : {
                    option : 'info',
                    year: this.year
                }
            })
            .then(response => {
                this.provinces = response.data.provinces;
                this.chartOptions = {
                    ...this.chartOptions,
                    ...{
                        xaxis: {
                            categories: response.data.categories
                        }
                    }
                };
                this.series = response.data.lists;
                this.total = response.data.total;
            })
            .catch(err => console.log(err));
        },
        openView(){
            this.$refs.industry.show();
        },
        previous(year){
            this.year = year - 1;
            this.fetch();
        },
        next(year){
            this.year = year + 1;
            this.fetch();
        }
    }
}
</script>