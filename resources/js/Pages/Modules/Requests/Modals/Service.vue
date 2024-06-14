<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Add Service" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                 <BCol lg="12" class="mt-1">
                    <InputLabel for="region" value="Service"/>
                    <Multiselect 
                    label="label"
                    :options="services" 
                    object
                    v-model="form.service"
                    placeholder="Select Service"/>
                </BCol>
                <div class="col-lg-12" v-if="form.service"><hr class="text-muted"/></div>
                <div class="col-lg-6 mt-0" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.name" readonly=""><label>Name</label>
                    </div>
                </div>
                <div class="col-lg-6 mt-0" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.fee" readonly=""><label>Fee</label>
                    </div>
                </div>
                <div class="col-lg-12" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.description" readonly=""><label>Description</label>
                    </div>
                </div>
            </BRow>     
        </form>       
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
export default {
    components: { Multiselect },
    props: ['services'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                service: null,
                option: 'service'
            }),
            showModal: false
        }
    },
    methods: { 
        show(id){
            this.form.id = id;
            this.showModal = true;
        },
        submit(){
            this.form.post('/analyses',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('total',response.props.flash.data);
                    this.hide();
                },
            });
        },
        hide(){
            this.form.name = null;
            this.form.contact_no = null;
            this.showModal = false;
        }
    }
}
</script>