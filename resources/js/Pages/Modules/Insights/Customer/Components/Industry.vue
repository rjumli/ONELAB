<template>
   <BCard no-body class="card-height-100" style="height: 350px;">
        <BCardHeader class="align-items-center d-flex">
            <BCardTitle class="mb-0 flex-grow-1">Industry Type</BCardTitle>
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
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">%</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr v-for="(list,index) in industry.data" v-bind:key="index">
                            <td>{{index + 1}}</td>
                            <td>{{list.name}}</td>
                            <td class="text-center">{{list.customer_industry_count}} </td>
                            <td class="text-center">{{percentage(list.customer_industry_count)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </BCardBody>
        <Industry :total="total" ref="industry"/>
    </BCard>
</template>
<script>
import _ from 'lodash';
import Industry from '../Modals/Industry.vue';
export default {
    components: { Industry },
    props: ['total','industry'],
    data(){
        return {
            sort: null,
        }
    },
    methods: {
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
        openView(){
            this.$refs.industry.show();
        }
    }
}
</script>