<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Add Stock" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol v-if="status" lg="12" class="mt-n2">
                    <InputLabel for="item_id" value="Item" :message="form.errors.item_id"/>
                    <Multiselect :options="items" label="name" @search-change="fetchItem" :searchable="true" v-model="form.item_id" placeholder="Select Item"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>
                <BCol lg="6" class="mt-2 mb-n1">
                    <InputLabel for="name" value="Brand" :message="form.errors.brand"/>
                    <TextInput id="name" v-model="form.brand" type="text" class="form-control" autofocus placeholder="Please enter item brand" autocomplete="name" required @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-2 mb-n1">
                    <InputLabel for="name" value="Serial no. / Batch no." :message="form.errors.number"/>
                    <TextInput id="name" v-model="form.number" type="text" class="form-control" autofocus placeholder="Please enter serial or batch no." autocomplete="name" required @input="handleInput('number')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Quantity" :message="form.errors.quantity"/>
                    <TextInput id="name" v-model="form.quantity" type="text" class="form-control" autofocus placeholder="Please enter quantity" autocomplete="name" required @input="handleInput('quantity')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Price" :message="form.errors.bought_at"/>
                    <Amount @amount="amount" ref="testing" :readonly="false"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Expiration / Warranty" :message="form.errors.date"/>
                    <TextInput id="name" v-model="form.date" type="date" class="form-control" autofocus placeholder="Please enter date" autocomplete="name" required @input="handleInput('date')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-n1">
                    <InputLabel for="name" value="Bought Date" :message="form.errors.bought_at"/>
                    <TextInput id="name" v-model="form.bought_at" type="date" class="form-control" autofocus placeholder="Please enter bought date" autocomplete="name" required @input="handleInput('bought_at')" :light="true"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>
                <BCol lg="12" class="mt-2">
                    <InputLabel for="supplier_id" value="Supplier" :message="form.errors.supplier_id"/>
                    <Multiselect :options="suppliers" label="name" :searchable="true" v-model="form.supplier_id" placeholder="Select Supplier"/>
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
import Multiselect from '@/Shared/Components/Forms/Multiselect.vue';
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
                date: null,
                number: null,
                quantity: null,
                brand: null,
                option: 'stock'
            }),
            items: [],
            showModal: false,
            editable: false,
            status: false,
        }
    },
    watch: {
        'form.is_equipment'(){
            if(this.form.is_equipment){
                this.form.category_id = this.dropdowns.categories[0].value;
                this.form.quantity_id = this.dropdowns.quantitys[0].value;
                this.form.quantity = 1;
            }else{
                this.form.category_id = null;
                this.form.quantity_id = null;
            }
        }
    },
    methods: { 
        show(status){
            this.status = status;
            this.showModal = true;
        },
        edit(data){
            this.form.name = data.name;
            this.form.email = data.email;
            this.form.contact_no = data.contact_no;
            this.editable = true;
            this.showModal = true;
        },
        fetchItem(code){
            axios.get('/inventory',{
                params: {
                    option: 'search',
                    keyword: code
                }
            })
            .then(response => {
                this.items = response.data;
            })
            .catch(err => console.log(err));
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