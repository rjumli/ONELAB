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
                                            <ul class="dropdown-menu" aria-labelledby="displayModeDropdown" style="cursor: pointer;">
                                                <li><a class="dropdown-item" @click="setDisplay('tsr')">Show by TSR</a></li>
                                                <li><a class="dropdown-item" @click="setDisplay('sample')">Show by Sample</a></li>
                                                <li><a class="dropdown-item" @click="setDisplay('testname')">Show by Testname</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Tsr v-if="mode === 'tsr'" :samples="samples"/>
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
import Tsr from './Display/Tsr.vue';
import simplebar from "simplebar-vue";
import View from './Modals/View.vue';
import Show from './Modals/Show.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, View, Show, simplebar, Tsr },
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
            mode: 'tsr'
        }
    },
    methods: {
        setDisplay(mode){
            this.mode = mode;
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