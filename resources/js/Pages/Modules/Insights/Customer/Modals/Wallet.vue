<template>
    <b-modal v-model="showModal" hide-footer title="Customer Wallets" class="v-modal-custom" modal-class="zoomIn" size="lg" centered>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table align-middle table-centered table-nowrap mb-3">
                        <thead class="text-muted table-light fs-11">
                            <tr>
                                <th width="7%" style="cursor: pointer;">  
                                    <i @click="setSort('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                    <i @click="setSort('desc')" v-else class="ri-sort-desc"></i> 
                                </th>
                                <th width="70%" scope="col">Name</th>
                                <th width="23%" class="text-center" scope="col">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in lists" v-bind:key="index">
                                <td> {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                <td>{{list.customer_name.name}} <span v-if="list.customer_name.has_branches"> - {{list.name}}</span></td>
                                <td class="text-center">{{formatMoney(list.wallet.available)}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components : { Pagination },
    data() {
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            sort: 'desc',
            showModal: false
        }
    },
    methods : {
        show(){
            this.fetch();
            this.showModal = true;
        },
        fetch(page_url) {
            page_url = page_url || '/insights/customers';
            axios.get(page_url, {
                params: { 
                    option: 'wallet', 
                    sort: this.sort
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        refresh(){
            this.fetch();
        },
        setSort(data){
            this.sort = data;
            this.fetch();
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>
