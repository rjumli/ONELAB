<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Item" class="form-control" style="width: 35%;">
                <span @click="openAdd" class="input-group-text" v-b-tooltip.hover title="Add Item" style="cursor: pointer;"> 
                    <i class="bx bx-plus-circle search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 15%;" class="text-center">Variant</th>
                    <th style="width: 15%;" class="text-center">Type</th>
                    <th style="width: 15%;" class="text-center">Category</th>
                    <th style="width: 10%;" class="text-center">Quantity</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 7%;" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td class="text-center"> 
                        {{ index + 1 }}.
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.code}}</p>
                    </td>
                    <td class="text-center fs-12">{{list.unit}}{{list.unittype.others}}</td>
                    <td class="text-center fs-12">{{list.laboratory_type.name}}</td>
                    <td class="text-center fs-12">{{list.category.name}}</td>
                    <td class="text-center fs-12">0</td>
                    <td class="text-center">
                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                        <span v-else class="badge bg-danger">Inactive</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="openEdit(list,index)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create @message="fetch()" :dropdowns="dropdowns" ref="create"/>
    <Add :suppliers="dropdowns.suppliers" ref="add"/>
</template>
<script>
import _ from 'lodash';
import Add from '../Modals/Add.vue';
import Create from '../Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    props: ['dropdowns','suppliers'],
    components: { Pagination, Create, Add },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            filter: {
                keyword: null
            }
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/inventory';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    count: ((window.innerHeight-350)/58),
                    option: 'items'
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;     
            })
            .catch(err => console.log(err));
        },
        openAdd(){
            this.$refs.add.show();
        },
        openCreate(){
            this.$refs.create.show();
        },
        openEdit(data,index){
            this.index = index;
            this.$refs.create.edit(data);
        },
    }
}
</script>