<template lang="">
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="Lab Analyst" />
    
    <b-row class="g-4">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 mb-n4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="search-box">
                                        <input type="text" v-model="searchTerm" @input="search" class="form-control search" placeholder="Search for sample code">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-auto ms-auto">
                                    <div class="d-flex hastck gap-2 flex-wrap">
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle" type="button" id="displayModeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Display Mode
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="displayModeDropdown">
                                                <li><a class="dropdown-item" @click="setGroupDisplay(false)">Show by Sample</a></li>
                                                <li><a class="dropdown-item" @click="setGroupDisplay(true)">Show by TSR</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3">
                    <BCard class="mb-3" no-body>
                        <BLink class="card-header bg-warning-subtle" role="button">
                            <!-- <h5 class="card-title text-uppercase fw-semibold mb-1 fs-12">Pending Samples</h5>
                            <p class="text-muted mb-0">{{samples.pending.length}} ready for analyzation</p> -->
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                                    <span class="avatar-title bg-warning text-primary rounded-circle fs-4">
                                        <i class="ri-add-circle-fill text-light align-middle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0"><span class="counter-value fs-14">Pending Tests</span></h4>
                                    <p class="fs-12 text-muted mb-1">{{samples.pending.length}} TSR ready for test.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                
                    <simplebar data-simplebar style="height: calc(100vh - 395px);">
                        <BRow>
                            <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of samples.pending" :key="index">
                                <div class="card" style="cursor: pointer;" @click="openShow(item)">
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
                                            <div class="flex-grow-1">
                                                <div class="avatar-group">
                                                    <a class="avatar-group-item" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" aria-label="Darline Williams" data-bs-original-title="Darline Williams">
                                                        <div class="avatar-xxs"><img src="/img/avatar-2.0c4e66a9.jpg" alt="" class="rounded-circle img-fluid"></div>
                                                    </a>
                                                    <a class="avatar-group-item" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-original-title="Darline Williams">
                                                        <div class="avatar-xxs">
                                                            <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">+</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> {{item.tsr.due_at}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <!-- <h6 class="fs-12 mb-1">{{item.sample.code}} <span class="text-muted fs-13"> - {{item.sample.name}}</span></h6>
                                    <p class="fs-11 text-muted mb-0">Due At : {{item.sample.tsr.due_at}}</p> -->
                                <!-- <BCard no-body class="card-height-100">
                                
                                    <div class="card-header bg-transparent border-bottom-dashed px-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-0 fs-14"><a href="/apps/projects-overview" class="text-body">Testing</a></h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> 10 Jul, 2021</div>
                                            </div>
                                        </div>
                                    </div>
                                    <BCardBody>
                                        <div class="d-flex flex-column h-100">
                                            <div class="mt-auto">
                                                <div class="d-flex mb-2">
                                                    <div class="flex-grow-1">
                                                        <div>Analysis Test</div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div>
                                                            <i class="ri-list-check align-bottom me-1 text-muted"></i>1231
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress progress-sm animated-progess">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </BCardBody>
                                    <div class="card-footer bg-transparent border-top-dashed py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="avatar-group">
                                                    <a class="avatar-group-item" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" aria-label="Darline Williams" data-bs-original-title="Darline Williams">
                                                        <div class="avatar-xxs"><img src="/img/avatar-2.0c4e66a9.jpg" alt="" class="rounded-circle img-fluid"></div>
                                                    </a>
                                                    <a class="avatar-group-item" href="javascript: void(0);" target="_self" data-bs-toggle="tooltip" data-bs-original-title="Darline Williams">
                                                        <div class="avatar-xxs">
                                                            <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">+</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i> 10 Jul, 2021</div>
                                            </div>
                                        </div>
                                    </div>
                                </BCard> -->
                            </BCol>
                        </BRow>
                    <!-- <BCard v-if="samples.pending.length > 0" no-body class="mb-1" v-for="(item, index) of samples.pending" :key="index">
                        <BCardBody :class="(matches1.includes(index)) ? 'bg-warning-subtle' : ''" :id="'row-' + index">
                            <BLink class="d-flex align-items-center" role="button" @click="openView(item)">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-12 mb-1">{{item.sample.code}} <span class="text-muted fs-13"> - {{item.sample.name}}</span></h6>
                                    <p class="fs-11 text-muted mb-0">Due At : {{item.sample.tsr.due_at}}</p>
                                </div>
                            </BLink>
                        </BCardBody>
                    </BCard>
                    <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div> -->
                    </simplebar>
                    <!-- {{samples.pending[0]}} -->
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
                                    <p class="fs-12 text-muted mb-1">{{samples.ongoing.length}} ongoing analyzation.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                    <simplebar data-simplebar style="height: calc(100vh - 395px);">
                    <BCard v-if="samples.ongoing.length > 0" no-body class="mb-1" v-for="(item, index) of samples.ongoing" :key="index">
                        <BCardBody :class="(matches2.includes(index)) ? 'bg-info-subtle' : ''" :id="'row2-' + index">
                            <BLink class="d-flex align-items-center" role="button" @click="openView(item)">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-12 mb-1">{{item.sample.code}} <span class="text-muted fs-13"> - {{item.sample.name}}</span></h6>
                                    <p class="fs-11 text-muted mb-0">Due At : {{item.sample.tsr.due_at}}</p>
                                </div>
                            </BLink>
                        </BCardBody>
                    </BCard>
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
                                    <p class="fs-12 text-muted mb-1">{{samples.completed.length}} samples completed.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                    <simplebar data-simplebar style="height: calc(100vh - 395px);">
                    <BCard v-if="samples.completed.length > 0" no-body class="mb-1" v-for="(item, index) of samples.completed" :key="index">
                        <BCardBody :class="(matches3.includes(index)) ? 'bg-success-subtle' : ''" :id="'row3-' + index">
                            <BLink class="d-flex align-items-center" role="button" @click="openView(item)">
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-12 mb-1">{{item.sample.code}} <span class="text-muted fs-13"> - {{item.sample.name}}</span></h6>
                                    <p class="fs-11 text-muted mb-0">Due At : {{item.sample.tsr.due_at}}</p>
                                </div>
                            </BLink>
                        </BCardBody>
                    </BCard>
                    <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
                    </simplebar>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body" style="height: calc(100vh - 220px); overflow: auto;" >
                
                </div>
            </div>
        </div>
    </b-row>
    <View ref="view"/>
    <Show ref="show"/>
</template>
<script>
import simplebar from "simplebar-vue";
import View from './Modals/View.vue';
import Show from './Modals/Show.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, View, Show, simplebar },
    props: ['samples'],
    data(){
        return {
            currentUrl: window.location.origin,
            searchTerm: '',
            matchedRowIndex1: null,
            matchedRowIndex2: null,
            matchedRowIndex3: null,
            matches1: [],
            matches2: [],
            matches3: [],
        }
    },
    methods: {
        openView(data){
            this.$refs.view.show(data);
        },
        openShow(data){
            this.$refs.show.show(data);
        },  
        search() {
            const searchTerm = this.searchTerm.toLowerCase();
            const matchedIndices1 = this.samples.pending.reduce((indices, sample, index) => {
                if (sample.sample.code.toLowerCase().includes(searchTerm)) {
                    indices.push(index);
                }
                return indices;
            }, []);
            this.matches1 = matchedIndices1;
            if (matchedIndices1.length > 0 && searchTerm !== '') {
                const closestIndex = matchedIndices1.reduce((closest, currentIndex) => {
                    const closestDistance = Math.abs(closest - searchTerm.length);
                    const currentDistance = Math.abs(currentIndex - searchTerm.length);
                    return currentDistance > closestDistance ? currentIndex : closest;
                }, matchedIndices1[0]);

                this.matchedRowIndex1 = closestIndex;

                const rowId = 'row-' + closestIndex;
                const matchedRow = document.getElementById(rowId);

                if(matchedRow){
                    matchedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }else {
                this.matchedRowIndex1 = null;
                this.matches1 = [];
            }
    //////////
            const matchedIndices2 = this.samples.ongoing.reduce((indices, sample, index) => {
                if (sample.sample.code.toLowerCase().includes(searchTerm)) {
                    indices.push(index);
                }
                return indices;
            }, []);
            this.matches2 = matchedIndices2;
            if (matchedIndices2.length > 0 && searchTerm !== '') {
                const closestIndex = matchedIndices2.reduce((closest, currentIndex) => {
                    const closestDistance = Math.abs(closest - searchTerm.length);
                    const currentDistance = Math.abs(currentIndex - searchTerm.length);
                    return currentDistance > closestDistance ? currentIndex : closest;
                }, matchedIndices2[0]);

                this.matchedRowIndex2 = closestIndex;

                const rowId2 = 'row2-' + closestIndex;
                const matchedRow2 = document.getElementById(rowId2);

                if(matchedRow2){
                    matchedRow2.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }else {
                this.matchedRowIndex2 = null;
                this.matches2 = [];
            }

            //////////
            const matchedIndices3 = this.samples.completed.reduce((indices, sample, index) => {
                if (sample.sample.code.toLowerCase().includes(searchTerm)) {
                    indices.push(index);
                }
                return indices;
            }, []);
            this.matches3 = matchedIndices3;
            if (matchedIndices3.length > 0 && searchTerm !== '') {
                const closestIndex = matchedIndices3.reduce((closest, currentIndex) => {
                    const closestDistance = Math.abs(closest - searchTerm.length);
                    const currentDistance = Math.abs(currentIndex - searchTerm.length);
                    return currentDistance > closestDistance ? currentIndex : closest;
                }, matchedIndices3[0]);

                this.matchedRowIndex3 = closestIndex;

                const rowId3 = 'row3-' + closestIndex;
                const matchedRow3 = document.getElementById(rowId3);

                if(matchedRow3){
                    matchedRow3.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }else {
                this.matchedRowIndex3 = null;
                this.matches3 = [];
            }
        },
    }
}
</script>