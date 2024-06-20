<template>
    <div class="col-md-4 mt-3">
        <BCard class="mb-3" no-body>
            <BLink class="card-header bg-warning-subtle" role="button">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                        <span class="avatar-title bg-warning text-primary rounded-circle fs-4">
                            <i class="ri-add-circle-fill text-light align-middle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0"><span class="counter-value fs-14">Pending Tests</span></h4>
                        <p class="fs-12 text-muted mb-1">{{pendings.length}} TSR ready for test.</p>
                    </div>
                </div>
            </BLink>
        </BCard>
    
        <simplebar data-simplebar style="height: calc(100vh - 395px);">
            <BRow>
                <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of pendings" :key="index">
                    <div class="card" style="cursor: pointer;" @click="openShow(item,'Pending')">
                        <div class="card-header">
                            <h6 class="card-title mb-n1 fs-13 fw-semibold"><span class="text-primary">{{item.tsr.code}}</span></h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="mt-auto">
                                    <div class="d-flex mb-2">
                                        <div class="flex-grow-1">
                                            <div>Analysis Test</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div>
                                                <i class="ri-list-check align-bottom me-1 text-muted"></i>{{item.ongoing}}/{{item.analyses}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm animated-progess">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 text-muted fs-13">
                                    {{item.tsr.samples.length}} <span v-if="item.tsr.samples.length === 1">sample</span> <span v-else>samples</span> 
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> {{item.tsr.due_at}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </BCol>
            </BRow>
        </simplebar>
    </div>
    <div class="col-md-4 mt-3">
        <BCard class="mb-3" no-body>
            <BLink class="card-header bg-info-subtle" role="button">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                        <span class="avatar-title bg-info text-primary rounded-circle fs-4">
                            <i class="ri-record-circle-fill text-light align-middle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0"><span class="counter-value fs-14">Ongoing Tests</span></h4>
                        <p class="fs-12 text-muted mb-1">{{ongoings.length}} ongoing analyzation.</p>
                    </div>
                </div>
            </BLink>
        </BCard>
        <simplebar data-simplebar style="height: calc(100vh - 395px);">
        <BRow v-if="ongoings.length > 0">
            <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of ongoings" :key="index">
                <div class="card" style="cursor: pointer;" @click="openShow(item,'Ongoing')">
                    <div class="card-header">
                        <h6 class="card-title mb-n1 fs-13 fw-semibold"><span class="text-primary">{{item.tsr.code}}</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column h-100">
                            <div class="mt-auto">
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1">
                                        <div>Analysis Test</div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div>
                                            <i class="ri-list-check align-bottom me-1 text-muted"></i>{{item.completed}}/{{item.ongoing}}
                                        </div>
                                    </div>
                                </div>
                                <div class="progress progress-sm animated-progess">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 text-muted fs-13">
                                {{item.tsr.samples.length}} <span v-if="item.tsr.samples.length === 1">sample</span> <span v-else>samples</span> 
                            </div>
                            <div class="flex-shrink-0">
                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> {{item.tsr.due_at}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </BCol>
        </BRow>
        <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
        </simplebar>
    </div>
    <div class="col-md-4 mt-3">
        <BCard class="mb-3" no-body>
            <BLink class="card-header bg-success-subtle" role="button">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                        <span class="avatar-title bg-success text-primary rounded-circle fs-4">
                            <i class="ri-checkbox-circle-fill text-light align-middle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-0"><span class="counter-value fs-14">Completed Tests</span></h4>
                        <p class="fs-12 text-muted mb-1">{{completeds.length}} samples completed.</p>
                    </div>
                </div>
            </BLink>
        </BCard>
        <simplebar data-simplebar style="height: calc(100vh - 395px);">
            <BRow v-if="completeds.length > 0">
            <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of completeds" :key="index">
                <div class="card" style="cursor: pointer;" @click="openShow(item,'Completed')">
                    <div class="card-header">
                        <h6 class="card-title mb-n1 fs-13 fw-semibold"><span class="text-primary">{{item.tsr.code}}</span></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column h-100">
                            <div class="mt-auto">
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1">
                                        <div>Analysis Test</div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div>
                                            <i class="ri-list-check align-bottom me-1 text-muted"></i>{{item.completed}}/{{item.analyses}}
                                        </div>
                                    </div>
                                </div>
                                <div class="progress progress-sm animated-progess">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 text-muted fs-13">
                                {{item.tsr.samples.length}} <span v-if="item.tsr.samples.length === 1">sample</span> <span v-else>samples</span> 
                            </div>
                            <div class="flex-shrink-0">
                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> {{item.tsr.due_at}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </BCol>
        </BRow>
        <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
        </simplebar>
    </div>
    <Show ref="show"/>
</template>
<script>
import Show from '../Modals/Show.vue';
import simplebar from "simplebar-vue";
export default {
    components: { simplebar, Show },
    props: ['samples'],
    computed: {
        pendings(){
            return this.samples.filter(item => item.pending > 0);
        },
        ongoings(){
            return this.samples.filter(item => item.ongoing > 0 && item.ongoing !== item.completed);
        },
        completeds(){
            return this.samples.filter(item => item.completed  === item.analyses );
        }
    },
    methods: {
        openShow(data,status){
            this.$refs.show.show(data,status);
        },  
    }
}
</script>