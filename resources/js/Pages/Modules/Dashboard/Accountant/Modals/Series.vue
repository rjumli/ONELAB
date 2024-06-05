<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Create OR Series" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12 mt-3">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter a name" :light="true"/>
                </BCol>
                <BCol lg="6 mt-0">
                    <InputLabel for="name" value="Start" :message="form.errors.start"/>
                    <TextInput id="name" v-model="form.start" type="text" class="form-control" placeholder="Please enter start number" :light="true"/>
                </BCol>
                <BCol lg="6 mt-0">
                    <InputLabel for="name" value="End" :message="form.errors.end"/>
                    <TextInput id="name" v-model="form.end" type="text" class="form-control" placeholder="Please enter end number" :light="true"/>
                </BCol>
                <BCol lg="12 mt-0">
                    <InputLabel for="name" value="Next" :message="form.errors.next"/>
                    <TextInput id="name" v-model="form.next" type="text" class="form-control" placeholder="Please enter next number" :light="true"/>
                </BCol>
                <BCol lg="12 mt-1 mb-n3">
                    <hr class="text-muted"/>
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
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                start: null,
                end: null,
                next: null,
                option: 'series'
            }),
            showModal: false
        }
    },
    watch: {
        "form.start"(newVal){
            this.form.next = newVal;
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/finance',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
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