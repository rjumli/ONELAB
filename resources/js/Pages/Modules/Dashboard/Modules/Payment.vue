<template>
    <div class="card" style="height: 540px;">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Laboratory Report</h5>
            <div>
                <div class="input-group input-group-sm mb-0">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <select @change="fetch()" v-model="type" class="form-select" id="inputGroupSelect01" style="width: 200px;">
                        <option :value="null" selected>All Laboratory</option>
                        <option :value="list" v-for="list in laboratories" v-bind:key="list.id">{{list.name}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-header p-0 border-0 bg-light-subtle">
            <div class="row g-0">
                <div class="col-sm-6 text-start">
                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                        <h5 class="mb-1 text-primary"><span>{{formatMoney(total)}}</span></h5>
                        <p class="text-muted mb-0">Total worth of Services</p>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                        <h5 class="mb-1 text-primary"><span>{{formatMoney(gratis)}}</span></h5>
                        <p class="text-muted mb-0">Total Gratis and Discounts</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <apexchart class="apex-charts" height="380" dir="ltr" :series="series" :options="chartOptions"></apexchart>
        </div>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['laboratories'],
    data() {
        return {
            currentUrl: window.location.origin,
            labs: [],
            type: null,
            total: null,
            gratis: null,
            series: [
            ],
            chartOptions: {
                chart: {
                    type: "bar",
                    height: 341,
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "65%",
                    },
                },
                stroke: {
                    show: true,
                    width: 5,
                    colors: ["transparent"],
                },
                xaxis: {
                    categories: [""],
                    axisTicks: {
                        show: false,
                        borderType: "solid",
                        color: "#78909C",
                        height: 6,
                        offsetX: 0,
                        offsetY: 0,
                    },
                    title: {
                        text: "Total Collection per Status",
                        offsetX: 0,
                        offsetY: -30,
                        style: {
                        color: "#78909C",
                        fontSize: "12px",
                        fontWeight: 400,
                        },
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                        return "₱" + value + "k";
                        },
                    },
                    tickAmount: 4,
                    min: 0,
                },
                fill: {
                    opacity: 1,
                },
                legend: {
                    show: true,
                    position: "bottom",
                    horizontalAlign: "center",
                    //   fontWeight: 500,
                    offsetX: 0,
                    offsetY: -14,
                    itemMargin: {
                        horizontal: 8,
                        vertical: 0,
                    },
                    markers: {
                        width: 9,
                        height: 9,
                    },
                },
                colors: ["#f1b44c", "#34c38f", "#556ee6", "#ea6868", "#a20cce", " #713d3d"],
            }
        }
    },
    created() {
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl + '/insights',{
                params : {
                    option : 'payment',
                    year: this.year,
                    laboratories: this.laboratories
                }
            })
            .then(response => {
                console.log(response.data);
                this.series = response.data.data;
                this.total = response.data.total;
                this.gratis = response.data.gratis;
            })
            .catch(err => console.log(err));
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    }
}
</script>