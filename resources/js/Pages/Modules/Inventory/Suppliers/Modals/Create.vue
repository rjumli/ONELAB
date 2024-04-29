<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Supplier' : 'Edit Supplier'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" autofocus placeholder="Please enter name" autocomplete="name" required :class="{ 'is-invalid': form.errors.name }" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="email" value="Email" :message="form.errors.email"/>
                    <TextInput id="email" v-model="form.email" type="email" class="form-control" autofocus placeholder="Please enter email" autocomplete="email" required :class="{ 'is-invalid': form.errors.email }" @input="handleInput('email')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="contact_no" value="Contact" :message="form.errors.contact_no"/>
                    <TextInput id="contact_no" v-model="form.contact_no" type="text" class="form-control" autofocus placeholder="Please enter contact" autocomplete="contact_no" required :class="{ 'is-invalid': form.errors.contact_no }" @input="handleInput('contact_no')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <hr class="text-muted mt-1 mb-2"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="region" value="Region" :message="form.errors.region_code"/>
                    <Multiselect :options="dropdowns.regions" v-model="form.region_code" :message="form.errors.region_code" label="name" placeholder="Select Region"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="province" value="Province" :message="form.errors.province_code"/>
                    <Multiselect :options="provinces" :searchable="true" v-model="form.province_code" :message="form.errors.province_code" label="name" placeholder="Select Province"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="municipality" value="Municipality" :message="form.errors.municipality_code"/>
                    <Multiselect :options="municipalities" :searchable="true" v-model="form.municipality_code" :message="form.errors.municipality_code" label="name" placeholder="Select Municipality"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="barangay" value="Barangay" />
                    <Multiselect :options="barangays" :searchable="true" v-model="form.barangay_code" :message="form.errors.barangay_code" label="name" placeholder="Select Barangay"/>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="address" value="Address" :message="form.errors.address"/>
                    <TextInput id="address" v-model="form.address" type="text" class="form-control" autofocus placeholder="Please enter address" autocomplete="address" required :class="{ 'is-invalid': form.errors.address }" @input="handleInput('address')" :light="true"/>
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
                name: null,
                email: null,
                contact_no: null,
                address: null,
                region_code: null,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                option: 'supplier'
            }),
            provinces: [],
            municipalities: [],
            barangays: [],
            showModal: false,
            editable: false
        }
    },
    watch: {
        'form.region_code'(){
            (this.form.region_code) ? this.fetchProvince(this.form.region_code) : '';
        },
        'form.province_code'(){
            (this.form.province_code) ? this.fetchMunicipality(this.form.province_code) : '';
        },
        'form.municipality_code'(){
            (this.form.municipality_code) ? this.fetchBarangay(this.form.municipality_code) : '';
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.email = data.email;
            this.form.contact_no = data.contact_no;
            this.form.address = data.address;
            this.form.region_code = data.region.value;
            this.fetchProvince(data.region.value);
            this.form.province_code = data.province.value;
            this.fetchMunicipality(data.province.value);
            this.form.municipality_code = data.municipality.value;
            this.form.barangay_code = (data.barangay) ? data.barangay.value : null;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/inventory/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('message',this.$page.props.flash.data);
                        this.form.reset();
                        this.hide();
                    }
                });
            }else{
                this.form.post('/inventory',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('message',this.$page.props.flash.data);
                        this.form.reset();
                        this.hide();
                    },
                });
            }
        },
        fetchProvince(code){
            axios.get('/lists/locations/',{
                params: {
                    option: 'list_province',
                    code: code
                }
            })
            .then(response => {
                this.provinces = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchMunicipality(code){
            axios.get('/lists/locations/',{
                params: {
                    option: 'list_municipality',
                    code: code
                }
            })
            .then(response => {
                this.municipalities = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchBarangay(code){
            axios.get('/lists/locations/',{
                params: {
                    option: 'list_barangay',
                    code: code
                }
            })
            .then(response => {
                this.barangays = response.data;
            })
            .catch(err => console.log(err));
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