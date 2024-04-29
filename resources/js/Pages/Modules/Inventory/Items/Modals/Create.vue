<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Item' : 'Edit Item'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" autofocus placeholder="Please enter item name" autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>
                <BCol lg="12">
                    <BRow class="g-3">
                        <BCol lg="8"  style="margin-top: 13px; margin-bottom: -12px;" class="fs-12">Is this item an equipment / hardware?</BCol>
                        <BCol lg="4"  style="margin-top: 13px; margin-bottom: -12px;">
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.is_equipment">
                                        <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.is_equipment">
                                        <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                        </BCol>
                        <BCol lg="12"><hr class="text-muted mt-n2"/></BCol>
                    </BRow>
                </BCol>
                <BCol lg="6" class="mt-n2">
                    <InputLabel for="category_id" value="Category" :message="form.errors.category_id"/>
                    <Multiselect :options="dropdowns.categories" label="name" :searchable="true" v-model="form.category_id" :message="form.errors.category_id" placeholder="Select Category"/>
                </BCol>
                <BCol lg="6" class="mt-n2">
                    <InputLabel for="category_id" value="Unit" :message="form.errors.unit_id"/>
                    <div class="input-group mb-1">
                        <input type="text" v-model="form.unit" placeholder="Size" class="form-control" style="height: 39px; width: 50%; background-color: #f5f6f7;">
                        <select v-model="form.unit_id" class="form-select" id="inputGroupSelect02" style="height: 39px; width: 50%; background-color: #f5f6f7;">
                            <option :value="null" selected>Select</option>
                            <option :value="list.value" v-for="list in dropdowns.units" v-bind:key="list.value">{{list.name}}</option>
                        </select>
                    </div>
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
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                size: null,
                name: null,
                is_equipment: null,
                reorder: null,
                category_id: null,
                unit_id: null,
                unit: null,
                laboratory_id: 14,
                laboratory_type: 9,
                option: 'item'
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
                        this.$emit('message',true);
                        this.form.reset();
                        this.hide();
                    },
                });
            }
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