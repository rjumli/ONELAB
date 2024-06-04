<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Create Official Receipt" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12 mt-2">
                    <InputLabel for="payor" value="Payor"/>
                    <TextInput id="name" v-model="customer" type="text" class="form-control" autofocus :light="true" readonly/>
                </BCol>
                <BCol lg="6 mt-1">
                    <InputLabel for="deposit" value="Deposit Type" :message="form.errors.deposit_id"/>
                    <Multiselect 
                    :options="deposits" 
                    v-model="form.deposit_id" 
                    label="name"
                    placeholder="Select Deposit type"/>
                </BCol>
                <BCol lg="6 mt-1">
                    <InputLabel for="orseries" value="O.R Series" :message="form.errors.orseries"/>
                    <Multiselect 
                    :options="orseries" 
                    v-model="form.orseries" 
                    object
                    label="name"
                    placeholder="Select OR"/>
                </BCol>
                <BCol lg="12" v-if="form.orseries">
                    <hr class="text-muted mt-0"/>
                    <div class="d-grid gap-2" >
                        <b-button variant="success">O.R # : {{form.orseries.next}}</b-button>
                    </div>
                </BCol>
                <BCol lg="12 mt-1 mb-n3">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="6 mt-2" v-if="form.selected.payment.name == 'Cheque'">
                    <InputLabel value="Cheque Number"/>
                    <TextInput v-model="form.cheque.number" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6 mt-2" v-if="form.selected.payment.name == 'Cheque'">
                    <InputLabel value="Cheque Date"/>
                    <TextInput v-model="form.cheque.cheque_at" type="date" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6 mt-0" v-if="form.selected.payment.name == 'Cheque'">
                    <InputLabel value="Amount"/>
                    <Amount @amount="amount" ref="testing" :readonly="false"/>
                </BCol>
                <BCol lg="6 mt-0" v-if="form.selected.payment.name == 'Cheque'">
                    <InputLabel value="Bank Name"/>
                    <TextInput v-model="form.cheque.bank" type="text" class="form-control" :light="true"/>
                </BCol>
            </BRow>
            <!-- {{form.selected}} -->
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import Multiselect from "@vueform/multiselect";
import Amount from '@/Shared/Components/Forms/Amount.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Amount },
    props: ['deposits','orseries'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                deposit_id: null,
                orseries: null,
                selected: {payment:{}},
                cheque: {
                    type: null,
                    number: null,
                    bank: null,
                    cheque_at: null,
                    amount: null,
                },
                total: null,
                option: 'receipt'
            }),
            customer: null,
            showModal: false
        }
    },
    methods: { 
        show(customer,data){
            this.customer = customer;
            this.form.selected = data;
            this.form.total =  this.form.selected.total;
            this.form.cheque.type =  this.form.selected.payment.name;
            this.showModal = true;
        },
        submit(){
            this.form.post('/finance',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        amount(val){
            this.form.cheque.amount = val;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>