<template>
    <b-modal v-model="showModal" :title="selected.tsr.code"  style="--vz-modal-width: 900px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
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
            <div class="col-sm-12">
                <!-- {{ selected.lists }} -->
                <div v-if="selected.lists" class="table-responsive mt-0 mb-4">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th class="text-center" width="5%">#</th>
                                <th width="20%">Test Name</th>
                                <th class="text-center" width="30%">Method Reference</th>
                                <th class="text-center" width="12%">Fee</th>
                                <th class="text-center" width="13%">Status</th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,index) in selected.lists.data" v-bind:key="index" class="fs-11">
                                <td class="text-center"> 
                                    {{index + 1}}
                                </td>
                                <td>{{list.testname}}</td>
                                <td class="text-center">
                                    <h5 class="fs-11 mb-0">{{list.method}}</h5>
                                    <p class="text-muted mb-0">{{list.reference}}</p>
                                </td>
                                <td class="text-center">{{list.fee}}</td>
                                <td class="text-center">
                                    <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                                </td>
                                <!-- <td></td> -->
                            </tr>
                        </tbody>
                    </table>
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
            <b-button v-if="selected.tsr.status.name === 'Pending'" @click="save(11,'start')" variant="primary" :disabled="form.processing" block>Start Analysis</b-button>
            <b-button v-if="selected.tsr.status.name === 'Ongoing'" @click="save(12,'end')" variant="primary" :disabled="form.processing" block>End Analysis</b-button>
        </template>
    </b-modal>
    <Save @hide="hide" ref="save"/>
</template>
<script>
import Save from './Save.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect, Save },
    data(){
        return {
            selected: {
                tsr: {
                    status:{}
                },
            },
            form: {},
            showModal: false
        }
    },
    methods : {
        show(data) {
            this.selected = data;
            this.showModal = true;
        },
        save(status,type){
            this.form = this.$inertia.form({
                tsr_id: this.selected.sample.tsr.id,
                id: this.selected.id,
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