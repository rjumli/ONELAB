<template>
    <b-modal v-model="showModal" hide-footer class="v-modal-custom" modal-class="zoomIn" fullscreen>
        <template v-slot:header>
            <div style="border-bottom: 1px solid #ccc; width: 100%;">
                <i @click="showModal=false" class="ri-close-circle-fill float-end me-3" style="cursor:pointer; font-size: 30px;"></i>
                <b-row class="mb-3">
                    <b-col md>
                        <b-row class="align-items-center g-3">
                            <b-col md>
                                <div>
                                    <h4 class="fw-semibold text-primary">{{selected.customer.name}}</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div class="text-muted">Address : 
                                            <span class="text-body fw-medium">
                                                {{selected.customer.address.barangay.name}},
                                                {{selected.customer.address.municipality.name}},
                                                {{selected.customer.address.province.name}},
                                                {{selected.customer.address.region.region}}
                                            </span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Conforme : 
                                            <span class="text-body fw-medium">{{selected.conforme}}</span>
                                        </div>
                                        <div class="vr"></div>
                                        <div class="text-muted">Contact : 
                                            <span class="text-body fw-medium">{{selected.conforme_no}}</span>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                </b-row>
             </div>
        </template>
        <div class="row" style="margin-top: -23px; height: 100%;">
            <div class="col-md-9 border-right">
                <Samples :id="selected.id" 
                :lab="selected.laboratory.name"
                :laboratory="selected.type.id" 
                :received="selected.created_at" 
                :due="selected.due_at" 
                :status="selected.status"
                :code="selected.code"
                @total="updateTotal"
                ref="samples"/>
            </div>
            <div class="col-md-3">
                <table class="table table-bordered" style="margin-left: -13px; margin-top: 2px;">
                    <tbody>
                        <tr>
                            <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">TSR Information</span></td>
                        </tr>
                        <tr>
                            <td style="border-right: none; border-left: none;">
                                <div class="row ms-n2 mb-2">
                                    <div class="col-md-12">
                                        <div class="d-flex mt-0">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-service-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Status :</p> 
                                                <span :class="'badge '+selected.status.color">{{selected.status.name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Request At :</p>
                                                <h6 class="text-truncate mb-0 fs-12">{{selected.created_at}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-event-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Discount type :</p>
                                                <h6 class="text-truncate mb-0 fs-12">{{selected.discounted.name}} ({{selected.discounted.value}}%)</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex mt-3">
                                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-account-circle-fill"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="mb-1 fs-12 text-muted">Received By :</p>
                                                <h6 class="text-truncate mb-0"> <span class="fs-12">{{selected.received}}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Payment Details</span></td>
                        </tr>
                        <tr style="border-bottom: none; border-left: none;">
                            <td style="border-right: none; border-bottom: none; border-left: none;">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Sub Total :</td>
                                                    <td class="text-end" id="cart-subtotal">{{selected.subtotal}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Discount : </td>
                                                    <td class="text-end" id="cart-discount">{{selected.discount}}</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <th>Total :</th>
                                                    <td class="text-end"><span class="fw-semibold" id="cart-total">{{selected.total}}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <b-button v-if="selected.status.name !== 'Pending'" @click="openPrint(selected.id)" class="mt-3" variant="light"><i class="ri-printer-fill"></i> Print Quotation</b-button>
                                        <b-button v-if="selected.status.name === 'Pending'" @click="openSave(selected.id)" class="mt-3" variant="primary">Save Quotation</b-button>
                                        <b-button v-if="selected.status.name === 'Ongoing'" @click="openSubmit(selected)" class="mt-0" variant="primary">Submit Quotation</b-button>
                                    </div>
                                </div>
                            </td>
                         </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </b-modal>
    <Save @selected="updateSelected" ref="save"/>
    <Submit :samples="samples" ref="submit"/>
</template>
<script>
import Save from './Save.vue';
import Submit from './Submit.vue';
import Samples from '../Components/Samples.vue';
export default {
    components: { Samples, Submit, Save },
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: {
                customer: {
                    customer_name: {},
                    address: { barangay: '', municipality: '', province: '', region: ''}
                },
                conforme: {},
                discounted:{},
                received: { profile: {} },
                laboratory: {},
                type: {},
                status: {},
                purpose: {},
            },
        }
    },
    methods: {
        show(data){
            this.selected = data;
            this.$refs.samples.fetch(this.selected.id);
            this.showModal = true;
        },
        openSave(id){
            this.$refs.save.show(id);
        },
        openPrint(id){
            window.open(this.currentUrl + '/quotations?option=print&id='+id);
        },
        openSubmit(data){
            this.$refs.submit.show(data);
        },
        updateSelected(data){
            this.selected = data;
            this.$refs.samples.fetch(this.selected.id);
            this.$emit('updateMain',data);
        },
        updateTotal(data){
            this.selected.total = data;
        },
        hide(){
            this.showModal = false;
        },
    }
}
</script>
<style scoped>
.border-right {
  border-right: 1px solid #ced4da; /* Adjust border properties as needed */
}

</style>