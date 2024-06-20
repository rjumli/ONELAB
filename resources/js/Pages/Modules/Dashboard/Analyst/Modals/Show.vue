<template>
    <b-modal v-model="showModal" :title="selected.tsr.code"  style="--vz-modal-width: 1000px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <div class="row mb-2">
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                    class="ri-qr-code-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Ref Code :</p>
                            <h5 class="fs-12 mb-0">{{selected.tsr.code}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                    class="ri-calendar-todo-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Due At:</p>
                            <h5 class="fs-12 mb-0">{{selected.tsr.due_at}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-award-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                           <p class="text-muted fs-11 mb-0">Status :</p><span class="badge" :class="selected.tsr.status.color">{{selected.tsr.status.name}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12"><hr class="text-muted"/></div>
            <b-col lg>
            <div class="input-group mb-2">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" placeholder="Search Request" class="form-control" style="width: 45%;">
                <select v-model="filterCode" class="form-select" id="inputGroupSelect01">
                    <option :value="null" selected>Select All</option>
                    <option :value="list" v-for="list in uniqueCodes" v-bind:key="list.id">{{list}}</option>
                </select>
                <b-button type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
            <div class="col-sm-12">
                <!-- {{ selected.lists }} -->
                
                <div v-if="selected.lists" class="table-responsive mt-0 mb-0">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th class="text-center" width="5%">#</th>
                                <th width="24%">Test Name</th>
                                <th class="text-center" width="47%">Method Reference</th>
                                <th class="text-center" width="12%">Fee</th>
                                <th class="text-center" width="12%"></th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                    </table>
                    <simplebar data-simplebar style="max-height: calc(100vh - 500px);">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                <tr v-for="(list,index) in filteredLists" v-bind:key="index" class="fs-11" :class="(list.selected) ? 'table-info' : ''">
                                    <td  width="5%" class="text-center fs-14"> 
                                        <input v-if="list.status.name !== 'Completed'" type="checkbox" v-model="list.selected" class="form-check-input" />
                                        <i v-else class="text-success ri-checkbox-circle-fill fs-18"></i>
                                        <!-- <i v-if="list.status.name === 'Ongoing'" class="text-info ri-record-circle-fill fs-18"></i>
                                        <i v-if="list.status.name === 'Completed'" class="text-success ri-checkbox-circle-fill fs-18"></i> -->
                                    </td>
                                    <td  width="24%">
                                        <h5 class="fs-11 mb-0">{{list.code}} - {{list.sample}}</h5>
                                        <p class="text-muted mb-0">{{list.testname}}</p>
                                    </td>
                                    <td  width="47%" class="text-center">
                                        <h5 class="fs-11 mb-0">{{list.method}}</h5>
                                        <p class="text-muted mb-0">{{list.reference}}</p>
                                    </td>
                                    <td  width="12%" class="text-center">{{list.fee}}</td>
                                    <td  width="12%" class="text-center">
                                        <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                                    </td>
                                    <!-- <td></td> -->
                                </tr>
                            </tbody>
                        </table>
                    </simplebar>
                </div>
                <!-- <b-accordion class=" nesting-accordion custom-accordionwithicon accordion-border-box" id="accordionnesting">
                    <div class="accordion-item" v-for="(item, index) of selected.tsr.samples" :key="index">
                        <h2 class="accordion-header" id="accordionnestingExample3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#view'+index" aria-expanded="false" aria-controls="accor_nestingExamplecollapse3">
                            {{item.code}} <span class="text-muted ms-2">({{item.name}})</span>
                        </button>
                        </h2>
                        <div :id="'view'+index" class="accordion-collapse collapse" aria-labelledby="accordionnestingExample3" data-bs-parent="#accordionnesting">
                            <div class="accordion-body">
                                <BListGroup tag="ul" flush>
                                    <BListGroupItem tag="li"  v-for="(analysis, index) of item.analyses" :key="index">
                                        <input class="form-check-input me-1" type="checkbox" value=""> {{analysis.testservice.method.method.name}}
                                        <span class="float-end">{{analysis.testservice.method.fee}}</span>
                                    </BListGroupItem>
                                </BListGroup>
                            </div>
                        </div>
                    </div>
                </b-accordion > -->
            </div>
        </div>

        <!-- {{ selected.tsr.samples }} -->
        
        <!-- <form class="row customform mb-n1 mt-n2 g-2">
            <div class="col-lg-12">
                <hr class="text-muted mt-2 mb-2"/>
            </div>
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" class="form-control" :value="selected.testservice.testname.name" readonly=""><label>Testname</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" class="form-control" :value="selected.testservice.method.method.name" readonly=""><label>Method</label>
                </div>
            </div>
             <div class="col-lg-12">
                <div class="form-floating">
                    <input type="text" class="form-control" :value="selected.testservice.method.reference.name" readonly=""><label>Reference</label>
                </div>
            </div>
             <div class="col-lg-12">
                <blockquote class="blockquote custom-blockquote blockquote-dark rounded mb-0">
                    <p class="text-body fs-12 mb-2">{{selected.sample.customer_description}}, {{selected.sample.description}}</p>
                    <footer class="blockquote-footer fs-10 mt-0"><cite title="Source Title">{{selected.sample.name}}</cite></footer>
                </blockquote>
            </div>
        </form> -->
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="status === 'Pending'" @click="save(11,'start')" variant="primary" :disabled="form.processing" block>Start Analysis</b-button>
            <b-button v-if="status === 'Ongoing'" @click="save(12,'end')" variant="primary" :disabled="form.processing" block>End Analysis</b-button>
        </template>
    </b-modal>
    <Save @hide="hide" ref="save"/>
</template>
<script>
import simplebar from "simplebar-vue";
import Save from './Save.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect, Save, simplebar },
    data(){
        return {
            selected: {
                tsr: {
                    status:{}
                },
            },
            filterCode: null,
            status: null,
            form: {},
            showModal: false
        }
    },
    computed: {
        uniqueCodes() {
            if(this.selected.lists){
                const codesSet = new Set(this.selected.lists.data.map(item => item.code));
                return Array.from(codesSet);
            }else{
                return [];
            }
        },
        filteredLists() {
            if(this.selected.lists){
                if(this.filterCode) {
                    return this.selected.lists.data.filter(item => item.code.includes(this.filterCode));
                }
                return this.selected.lists.data.filter(item => item.status.name.includes(this.status));
            }
        },
        tests(){
            if(this.filteredLists){
                return this.filteredLists.filter(item => item.selected).map(selectedItem => selectedItem.id);
            }else{ 
                return [];
            }
        }
    },
    methods : {
        show(data,status) {
            this.status = status;
            this.selected = data;
            this.showModal = true;
        },
        save(status,type){
            this.form = this.$inertia.form({
                tsr_id: this.selected.tsr.id,
                id: this.tests,
                status_id: status,
                start_at: null,
                end_at: null,
                option: type
            });
            this.$refs.save.show(this.form);
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.showModal = false;
        },
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>