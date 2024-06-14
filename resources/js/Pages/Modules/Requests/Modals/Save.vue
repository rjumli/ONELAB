<template>
    <b-modal v-model="showModal" hide-footer hide-header title="Cancel Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="modal-body">
            <div class="mt-2">
                <h4 class="mb-4 text-center">Confirm TS Request</h4>
                <p class="text-primary mb-4 text-center">Upon confirming, you cannot add samples, analyses, or edit information for the TS Request.</p>
                <!-- <p class="text-muted mb-4">Please double-check all data to avoid cancellation or updating of the data.</p> -->
                <div class="customform">
                    <BCol lg="12" class="mt-2">
                        <InputLabel for="due" value="Report Due" :message="form.errors.due_at"/>
                        <TextInput v-model="form.due_at" type="date" class="form-control" autofocus placeholder="Please enter email" autocomplete="email" required @input="handleInput('due_at')" :light="true"/>
                    </BCol>
                    <BCol lg="12" class="mt-2">
                        <InputLabel for="due" value="Please type CONFIRM to continue."/>
                        <TextInput v-model="keyword" type="text" class="form-control" :light="true"/>
                    </BCol>
                    
                    <div class="hstack gap-2 justify-content-center mt-4">
                        <button @click="hide()" class="btn btn-light btn-md" type="button">
                            <div class="btn-content"> Close</div>
                        </button>
                        <button @click="submit()" :disabled="keyword !== 'CONFIRM'" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
               id: null,
               status_id: 2,
               due_at: null,
               option: 'Confirm'
            }),
            keyword: null,
            showModal: false
        }
    },
    methods: { 
        show(id){
            this.form.id = id;
            this.showModal = true;
        },
        submit(){
            this.form.put('/requests/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('selected',response.props.flash.data.data);
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