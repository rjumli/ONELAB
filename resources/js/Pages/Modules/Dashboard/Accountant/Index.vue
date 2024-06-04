<template>
    <Head title="Order of Payment"/>
    <PageHeader title="Order of Payment" pageTitle="Finance" />
    <b-row>
            <div class="col-md-12 mt-0 mb-n2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mt-2 text-primary fw-semibold">{{(laboratory) ? laboratory.name : 'All Laboratory'}}</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                    <select @change="changeLab()" v-model="laboratory" class="form-select">
                                        <option :value="null" selected>All Laboratory</option>
                                        <option :value="list" v-for="list in dropdowns.laboratories" v-bind:key="list.id">{{list.name}}</option>
                                    </select>
                                    <b-button @click="openCreate" type="button" variant="primary">
                                        Create &nbsp;<i class="ri-add-circle-fill"></i>
                                    </b-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-12">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="row g-3 mb-n2">
                        <b-col lg="4" md="6" v-for="(item, index) of dropdowns.counts" :key="index">
                            <b-card no-body>
                                <b-card-body>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                                <i :class="`bx ${item.icon} align-middle`"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                {{ item.name }}
                                            </p>
                                            <h4 class="mb-0">
                                                <span class="counter-value">
                                                {{ formatMoney(item.total) }}
                                                </span>
                                            </h4>
                                        </div>
                                        <div class="flex-shrink-0 align-self-end">
                                            <apexchart class="apex-charts" height="40" width="100" type="area" dir="ltr" :series="item.series" :options="chartOptions"></apexchart>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-card>
                        </b-col>
                    </div>
                    <div class="card">
                        <div class="card-body" style="height: calc(100vh - 400px); overflow: auto;">
                            <Lists 
                            :collections="dropdowns.collections" 
                            :payments="dropdowns.payments" 
                            :deposits="dropdowns.deposits" 
                            :orseries="dropdowns.orseries"
                            :statuses="dropdowns.statuses"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="height: calc(100vh - 305px); overflow: auto;">
                            <button class="btn btn-md btn-light btn-label waves-effect waves-light" type="button"><i class="bx bxs-webcam label-icon align-middle fs-16 me-2"></i> Monitor Schools</button>
                            {{dropdowns.orseries.length}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </b-row>
</template>
<script>
import Lists from './Components/Lists.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['dropdowns'],
    components: { PageHeader, Lists },
    data(){
        return {
            laboratory: null,
        }
    },
    methods: {
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>