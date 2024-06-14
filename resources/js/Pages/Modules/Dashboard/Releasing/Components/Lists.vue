<template>
    <div class="card" style="height: 450px;">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">For the release of TSRS results</h5>
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
                            <th width="60%" scope="col">TSR Code</th>
                            <th width="30%" class="text-center">Due At</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-12" v-for="(list,index) in tsrs" v-bind:key="index">
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.code}}</h5>
                                <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                            </td>
                            <td class="text-center">{{list.due_at}}</td>
                            <td class="text-center">
                                <b-button @click="openRelease(list)" variant="soft-primary" v-b-tooltip.hover title="Release" size="sm">
                                    <i class="ri-telegram-fill align-middle"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <Release ref="release"/>
</template>
<script>
import Release from '../Modals/Release.vue';
export default {
    components: { Release },
    props: ['tsrs'],
    data() {
        return {
            currentUrl: window.location.origin,
            target: null,
            year: new Date().getFullYear(),
        }
    },
    methods: {
        openRelease(data){
            this.$refs.release.show(data);
        }
    }
}
</script>