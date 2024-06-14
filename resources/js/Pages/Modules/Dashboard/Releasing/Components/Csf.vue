<template>
    <div class="card" style="height: 450px;">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">CSF Records</h5>
            <div>
                <button @click="view()" class="btn btn-soft-primary btn-sm" type="button">
                    <div class="btn-content"> View all </div>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle table-centered table-nowrap mb-0">
                    <thead class="text-muted table-light fs-10">
                        <tr>
                            <th width="70%" scope="col">TSR Code</th>
                            <th width="20%" class="text-center">Rating</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-12" v-for="(list,index) in lists" v-bind:key="index">
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.tsr.code}}</h5>
                                <p class="fs-12 text-muted mb-0">{{list.tsr.customer.customer_name.name}}</p>
                            </td>
                            <td class="text-center">5</td>
                            <td class="text-center">
                                <b-button @click="openView(list)" variant="soft-primary" v-b-tooltip.hover title="View" size="sm">
                                    <i class="ri-eye-fill align-middle"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination },
    data() {
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url) {
            page_url = page_url || '/csf';
            axios.get(page_url, {
                params: { 
                    option: 'lists'
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>