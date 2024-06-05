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
                    v-model="or.deposit_id" 
                    label="name"
                    @input="handleInput('deposit_id')"
                    placeholder="Select Deposit type"/>
                </BCol>
                <BCol lg="6 mt-1">
                    <InputLabel for="orseries" value="O.R Series" :message="form.errors.orseries"/>
                    <Multiselect 
                    :options="orseries" 
                    v-model="or.orseries" 
                    object
                    label="name"
                    @input="handleInput('orseries')"
                    placeholder="Select OR"/>
                </BCol>
                <BCol lg="12" v-if="or.orseries">
                    <hr class="text-muted mt-0"/>
                    <div class="d-grid gap-2" >
                        <b-button variant="success">O.R # : {{or.orseries.next}}</b-button>
                    </div>
                </BCol>
                <BCol lg="12 mt-1 mb-n3">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="6 mt-2" v-if="or.selected.payment.name == 'Cheque'">
                    <InputLabel value="Cheque Number" :message="form.errors.cheque_number"/>
                    <TextInput v-model="cheque.number" type="text" class="form-control" @input="handleInput('cheque_number')" :light="true"/>
                </BCol>
                <BCol lg="6 mt-2" v-if="or.selected.payment.name == 'Cheque'">
                    <InputLabel value="Cheque Date" :message="form.errors.cheque_cheque_at"/>
                    <TextInput v-model="cheque.cheque_at" type="date" class="form-control" @input="handleInput('cheque_cheque_at')" :light="true"/>
                </BCol>
                <BCol lg="6 mt-0" v-if="or.selected.payment.name == 'Cheque'">
                    <InputLabel value="Amount" :message="form.errors.cheque_amount"/>
                    <Amount @amount="amount" ref="testing" :readonly="false" @input="handleInput('cheque_amount')"/>
                </BCol>
                <BCol lg="6 mt-0" v-if="or.selected.payment.name == 'Cheque'">
                    <InputLabel value="Bank Name" :message="form.errors.cheque_bank"/>
                    <TextInput v-model="cheque.bank" type="text" class="form-control" @input="handleInput('cheque_bank')" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
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
            or: {
                id: null,
                deposit_id: null,
                orseries: null,
                selected: {payment:{}},
                total: null,
                type: null,
                option: 'receipt'
            },
            cheque: {
                type: null,
                number: null,
                bank: null,
                cheque_at: null,
                amount: null,
            },
            form : {
                errors: []
            },
            type: null,
            customer: null,
            showModal: false
        }
    },
    methods: { 
        show(customer,data){
            this.customer = customer;
            this.or.selected = data;
            this.or.total =  this.or.selected.total;
            this.or.series = this.orseries;
            this.type =  this.or.selected.payment.name;
            this.showModal = true;
        },
        submit(){

            if(this.type === 'Cheque'){
                this.form = this.$inertia.form({
                    'deposit_id': this.or.deposit_id,
                    'orseries': this.or.orseries,
                    'orseries_id': (this.or.orseries) ? this.or.orseries.value : null,
                    'cheque_number': this.cheque.number,
                    'cheque_cheque_at': this.cheque.cheque_at,
                    'cheque_bank': this.cheque.bank,
                    'cheque_amount': this.cheque.amount,
                    'selected': this.or.selected,
                    'total': this.or.total,
                    'type': this.type,
                    'option': 'receipt'
                });
            }else{
                this.form = this.$inertia.form({
                    'deposit_id': this.or.deposit_id,
                    'orseries_id': (this.or.orseries) ? this.or.orseries.value : null,
                    'orseries': this.or.orseries,
                    'type': this.type,
                    'selected': this.or.selected,
                    'total': this.or.total,
                    'option': 'receipt'
                });
            }

            this.form.post('/finance',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.hide();
                },
            });
        },
        amount(val){
            this.cheque.amount = val;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>