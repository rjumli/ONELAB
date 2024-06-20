<template>
    <div class="card" style="height: 350px;">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">For Release</h5>
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
                            <th width="30%" class="text-center">Due At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-12" v-for="(list,index) in lists" v-bind:key="index">
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.code}}</h5>
                                <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                            </td>
                            <td class="text-center">{{list.due_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['laboratory'],
    data() {
        return {
            currentUrl: window.location.origin,
            lists: [],
            target: null,
            year: new Date().getFullYear(),
        }
    },
    created() {
        this.fetch();
    },
    methods: {
        fetch() {
            axios.get(this.currentUrl + '/requests',{
                params : {
                    option : 'releasing'
                }
            })
            .then(response => {
                this.lists = response.data.data;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>