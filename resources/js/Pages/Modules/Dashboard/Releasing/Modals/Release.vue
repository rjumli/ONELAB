<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Release TSR" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow class="g-3">
                <div class="col-lg-12">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="selected.code" readonly=""><label>TSR Code</label>
                    </div>
                </div>
                 <div class="col-lg-12 mt-0">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="selected.customer.name" readonly=""><label>Customer name</label>
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                released_at: null,
                option: 'release'
            }),
            selected: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = data.id;
            this.form.released_at = new Date().toLocaleDateString("af-ZA")+' '+new Date().toLocaleTimeString("af-ZA")
            this.showModal = true;
        },
        submit(){
            this.form.put('/requests/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>