<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Withdraw" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="table-responsive">
            <table class="table table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th style="width: 70%;">Item</th>
                        <th style="width: 15%;" class="text-center">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in form.carts" v-bind:key="index" :class="[(list.is_active == 0) ? 'table-warnings' : '']">
                        <td class="fs-12">
                            {{list.name}}  
                        </td>
                        <td class="text-center fs-12">{{list.qnty}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Confirm</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
export default {
    props: ['suppliers'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                carts: [],
                option: 'withdraw'
            }),
            items: [],
            showModal: false,
            editable: false,
            status: false,
        }
    },
    methods: { 
        show(carts){
            this.form.carts = carts;
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
                        this.$emit('message',true);
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
            this.showModal = false;
        }
    }
}
</script>