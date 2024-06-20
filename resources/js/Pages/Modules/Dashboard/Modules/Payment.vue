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
        <div class="card-body">
             <apexchart class="apex-charts" height="250" dir="ltr" :series="series"
              :options="chartOptions"></apexchart>
                <simplebar data-simplebar style="height: 200px" class="pe-2">
                    <ul class="list-group list-group-flush border-dashed mb-0 mt-3 pt-2">
                        <li class="list-group-item px-0" v-for="(list,index) in labs" v-bind:key="index">
                            <div class="d-flex">
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-1">{{list.name}}</h6>
                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>{{list.others}}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h6 class="mb-1">{{formatMoney(list.total)}}</h6>
                                    <p class="text-success fs-12 mb-0">{{formatMoney(list.discount)}} <span class="text-muted fs-10">(Discount)</span></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </simplebar>
            <!-- <div class="table-responsive">
                <table class="table table-bordered align-middle table-centered table-nowrap mb-0">
                    <thead class="text-muted table-light fs-10">
                        <tr>
                            <th width="60%" scope="col"></th>
                            <th width="20%" class="text-center">Target</th>
                            <th width="20%" class="text-center">Current</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-12">
                            <td>No. of Samples Received</td>
                            <td class="text-center">1,200</td>
                            <td class="text-center">500</td>
                        </tr>
                         <tr class="fs-12">
                            <td>No. of Tests/Calibration Services Conducted</td>
                            <td class="text-center">1,200</td>
                            <td class="text-center">500</td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
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
            year: new Date().getFullYear(),
            series: [44, 55, 13, 43, 22],
            chartOptions: {
                chart: {
                height: 200,
                type: "pie",
                },
                labels: ["Team A", "Team B", "Team C", "Team D", "Team E"],
                legend: {
                show: false
                },
                dataLabels: {
                dropShadow: {
                    enabled: false,
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
        fetch() {
            axios.get(this.currentUrl + '/insights',{
                params : {
                    option : 'target',
                    year: this.year,
                    laboratories: this.laboratories
                }
            })
            .then(response => {
                this.labs = response.data;
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