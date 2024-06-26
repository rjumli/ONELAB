<template>
    <b-modal v-model="showModal" hide-footer title="Top Count Request" class="v-modal-custom" modal-class="zoomIn" size="lg" centered>
        <div class="row mb-4">
            <b-col class="mb-2" lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="tet" v-model="filter.keyword" placeholder="Search Customer" class="form-control" style="width: 45%">
                    <select v-model="filter.type" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 70px;">
                        <option :value="null" selected>All</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                     <select v-if="filter.type === 'monthly'" v-model="filter.month" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 70px;">
                        <option :value="null" selected>All</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <input v-if="filter.type !==  null" type="number" v-model="filter.year" placeholder="Year" class="form-control" style="width: 70px;">
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
                                <td>{{list.customer_name.name}}</td>
                                <td class="text-center">{{list.tsrs_count}} </td>
                                <td class="text-center">{{percentage(list.tsrs_count)}}</td>
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
            filter: {
                type: null,
                month: null,
                year: null,
                keyword: null
            },
            showModal: false
        }
    },
    watch: {
        "filter.year"(newVal){
            this.fetch();
        }
    },
    methods : {
        show(){
            this.fetch();
            this.showModal = true;
        },
        fetch(page_url) {
            page_url = page_url || '/insights/laboratories';
            axios.get(page_url, {
                params: { 
                    option: 'tsr', 
                    sort: this.sort,
                    year: this.filter.year,
                    month: this.filter.month
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
