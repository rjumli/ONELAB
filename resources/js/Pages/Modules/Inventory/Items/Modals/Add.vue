<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Add Stock" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-n2">
                    <InputLabel for="supplier_id" value="Supplier" :message="form.errors.supplier_id"/>
                    <Multiselect :options="suppliers" label="name" :searchable="true" v-model="form.supplier_id" :message="form.errors.supplier_id" placeholder="Select Supplier"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>
                <BCol lg="6" class="mt-2 mb-n1">
                    <InputLabel for="name" value="Brand" :message="form.errors.brand"/>
                    <TextInput id="name" v-model="form.brand" type="text" class="form-control" autofocus placeholder="Please enter item brand" autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-2 mb-n1">
                    <InputLabel for="name" value="Serial no. / Batch no." :message="form.errors.number"/>
                    <TextInput id="name" v-model="form.number" type="text" class="form-control" autofocus placeholder="Please enter serial or batch no." autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Unit" :message="form.errors.unit"/>
                    <TextInput id="name" v-model="form.unit" type="text" class="form-control" autofocus placeholder="Please enter unit" autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                 <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Bought Date" :message="form.errors.bought_at"/>
                    <TextInput id="name" v-model="form.bought_at" type="date" class="form-control" autofocus placeholder="Please enter bought date" autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Price" :message="form.errors.bought_at"/>
                    <Amount @amount="amount" ref="testing" :readonly="false"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect, Amount },
    props: ['suppliers'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                supplier_id: null,
                item_id: null,
                bought_at: null,
                price: null,
                warranty: null,
                number: null,
                unit: null,
                brand: null,
                option: 'stock'
            }),
            provinces: [],
            municipalities: [],
            barangays: [],
            showModal: false,
            editable: false
        }
    },
    watch: {
        'form.is_equipment'(){
            if(this.form.is_equipment){
                this.form.category_id = this.dropdowns.categories[0].value;
                this.form.unit_id = this.dropdowns.units[0].value;
                this.form.unit = 1;
            }else{
                this.form.category_id = null;
                this.form.unit_id = null;
            }
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        edit(data){
            this.form.name = data.name;
            this.form.email = data.email;
            this.form.contact_no = data.contact_no;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/inventory/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                    }
                });
            }else{
                this.form.post('/inventory',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        // this.$emit('message',true);
                        this.form.reset();
                        this.hide();
                    },
                });
            }
        },
        amount(val){
            this.form.price = val;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>