<template>
    <b-modal v-model="showModal" hide-footer title="Customer Industry Type" class="v-modal-custom" modal-class="zoomIn" size="lg" centered>
        <div class="row mb-4">
            <b-col class="mb-2" lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <!-- <select v-model="scholar" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 120px;">
                        <option :value="null" selected>All Scholars</option>
                        <option value="graduated">Graduated Scholars</option>
                        <option value="ongoing">Ongoing Scholars</option>
                    </select> -->
                    <b-button type="button" variant="primary" @click="refresh">
                        <i class="bx bx-refresh search-icon"></i>
                    </b-button>
                </div>
            </b-col>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table align-middle table-centered table-nowrap mb-3">
                        <thead class="text-muted table-light fs-11">
                            <tr>
                                <th style="cursor: pointer;">  
                                    <i @click="setSort('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                    <i @click="setSort('desc')" v-else class="ri-sort-desc"></i> 
                                </th>
                                <th scope="col">Name</th>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in lists" v-bind:key="index">
                                <td> {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                <td>{{list.name}}</td>
                                <td class="text-center">{{list.customer_industry_count}} </td>
                                <td class="text-center">{{percentage(list.customer_industry_count)}}</td>
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
    props: ['total'],
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
                    option: 'industry', 
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
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
    }
}
</script>
