<template>
    <div class="card overflow-hidden" style="height: 136px;">
        <div class="card-body bg-warning-subtle">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <h5 class="fs-14 mb-3">Total Wallet Balance</h5>
                    <h2 class="fs-26 mb-3">{{formatMoney(wallet.total)}}</h2>
                    <p class="text-muted mb-0">
                        <button @click="openView()" class="btn btn-soft-primary btn-sm me-2" type="button">
                            <div class="btn-content">View Wallets</div>
                        </button>
                        <span class="fs-12">{{wallet.count}} customer<span v-if="wallet.count > 1">s</span> has wallet balance</span>
                    </p>
                </div>
                <div class="flex-shrink-0"><i class="mdi mdi-wallet-outline text-primary" style="font-size: 70px;"></i></div>
            </div>
        </div>
    </div>
   <BCard no-body class="card-height-100" style="height: 380px;">
        <BCardHeader class="align-items-center d-flex">
            <BCardTitle class="mb-0 flex-grow-1">Provinces</BCardTitle>
            <div class="flex-shrink-0">
                <BButton @click="openView()" type="button" variant="soft-primary" size="sm">
                    View All
                </BButton>
            </div>
        </BCardHeader>

        <BCardBody>
            <div class="table-responsive table-card">
                <simplebar data-simplebar style="height: 290px;">
                <table class="table align-middle table-centered table-nowrap mb-3">
                    <thead class="text-muted table-light fs-11 thead-fixed">
                        <tr>
                            <th style="cursor: pointer;">  
                                <!-- <i @click="fetch('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                <i @click="fetch('desc')" v-else class="ri-sort-desc"></i>  -->
                                #
                            </th>
                            <th scope="col">Name</th>
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">%</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr v-for="(list,index) in location.data" v-bind:key="index">
                            <td>{{index + 1}}</td>
                            <td>{{list.name}}</td>
                            <td class="text-center">{{list.customers_count}} </td>
                            <td class="text-center">{{percentage(list.customers_count)}}</td>
                        </tr>
                    </tbody>
                </table>
                        </simplebar>
            </div>
        </BCardBody>
        <Wallet ref="wallet"/>
    </BCard>
</template>
<script>
import _ from 'lodash';
import simplebar from "simplebar-vue";
import Wallet from '../Modals/Wallet.vue';
export default {
    components: { simplebar, Wallet },
    props: ['location','total','wallet'],
    data(){
        return {
            sort: null,
        }
    },
    methods: {
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        openView(){
            this.$refs.wallet.show();
        }
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>