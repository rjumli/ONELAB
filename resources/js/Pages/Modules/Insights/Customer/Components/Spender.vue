<template>
   <BCard no-body class="card-height-100" style="height: 350px;">
        <BCardHeader class="align-items-center d-flex">
            <BCardTitle class="mb-0 flex-grow-1">Top Spender</BCardTitle>
            <div class="flex-shrink-0">
                <BButton @click="openView()" type="button" variant="soft-primary" size="sm">
                    View All
                </BButton>
            </div>
        </BCardHeader>

        <BCardBody>
            <div class="table-responsive table-card">
                <table class="table align-middle table-centered table-nowrap mb-3">
                    <thead class="text-muted table-light fs-11">
                        <tr>
                            <th style="cursor: pointer;">  
                                <!-- <i @click="fetch('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                <i @click="fetch('desc')" v-else class="ri-sort-desc"></i>  -->
                                #
                            </th>
                            <th scope="col">Name</th>
                            <th class="text-center" scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr v-for="(list,index) in spender.data" v-bind:key="index">
                            <td>{{index + 1}}</td>
                            <td>{{list.customer_name}}</td>
                            <td class="text-center">{{formatMoney(list.total)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </BCardBody>
        <Spender ref="spender"/>
    </BCard>
</template>
<script>
import _ from 'lodash';
import Spender from '../Modals/Spender.vue';
export default {
    props: ['spender'],
    components: { Spender },
    data(){
        return {
            sort: null,
        }
    },
    methods: {
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        openView(){
            this.$refs.spender.show();
        }
    }
}
</script>