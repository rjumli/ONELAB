<template>
    <div class="card" style="height: 540px;">
        <!-- <div class="card-header align-items-center d-flex">
            <h4 class="card-title fs-14 mb-n2 flex-grow-1">Transactions through the Years</h4>
            <div class="me-2">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <select @change="fetch()" v-model="type" class="form-select" id="inputGroupSelect01" style="width: 130px;">
                        <option value="yearly">Yearly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <b-button @click="previous(this.year)" type="button" variant="primary">
                        <i class="ri-arrow-left-s-fill"></i> Prev
                    </b-button>
                     <b-button @click="next(this.year)" type="button" variant="primary">
                         Next <i class="ri-arrow-right-s-fill"></i>
                    </b-button>
                </div>
            </div>
        </div> -->
         <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">{{year}} Transaction Report</h5>
            <div>
                <div class="input-group input-group-sm mb-0">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <select @change="fetch()" v-model="type" class="form-select" id="inputGroupSelect01" style="width: 130px;">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <b-button @click="previous(this.year)" type="button" variant="primary">
                        <i class="ri-arrow-left-s-fill"></i> Prev
                    </b-button>
                     <b-button @click="next(this.year)" type="button" variant="primary">
                         Next <i class="ri-arrow-right-s-fill"></i>
                    </b-button>
                </div>
            </div>
        </div>
        <div class="card-header p-0 border-0 bg-light-subtle">
            <div class="row g-0 text-center">
                <div class="col-sm-4" v-for="(list,index) in total" v-bind:key="index">
                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                        <h5 class="mb-1 text-primary"><span>{{formatMoney(list.value)}}</span></h5>
                        <p class="text-muted mb-0">{{list.name}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <apexchart ref="realtimeChart" class="apex-charts mb-4" type="area" dir="ltr" height="350" :series="series"
                :options="chartOptions">
            </apexchart>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            currentUrl: window.location.origin,
            type: 'monthly',
            laboratory: null,
            years: [],
            year: new Date().getFullYear(),
            provinces: [],
            province: null,
            total: [],
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
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return '₱' + value.toLocaleString(); 
                        }
                    }
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
    created() {
        this.fetch();
    },
    methods: {
        fetch(laboratory =  null) {
            this.laboratory = laboratory;
            axios.get(this.currentUrl + '/insights',{
                params : {
                    option : 'recap',
                    year: this.year,
                    type: this.type,
                    laboratory: this.laboratory
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
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        previous(year){
            if(this.type == 'yearly'){
                this.year = year - 20;
                this.fetch();
            }else{
                this.year = year - 1;
                this.fetch();
            }
        },
        next(year){
            if(this.type == 'yearly'){
                this.year = year + 20;
                this.fetch();
            }else{
                this.year = year + 1;
                this.fetch();
            }
        }
    }
}
</script>