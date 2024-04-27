<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 750px;" hide-footer hide-header title="Cancel Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="modal-body">
            <div class="mt-2">
                <h5 class="mb-2 text-center">Welcome to our Customer Satisfaction Feedback Survey</h5>
                <p class="text-muted mb-4">Share your thoughts on our products and services to help us improve. By participating in this survey, you're contributing directly to the enhancement of our offerings and the overall satisfaction of our customers. Thank you for taking the time to share your thoughts with us.</p>
                <br />
                <center>
                    <span class="text-muted text-center fs-12 mb-0">Please type "CONFIRM" to continue.</span>
                    <input ref="input" class="form-control" v-model="keyword" style="min-height: 38.4px; width: 60%; !important; text-align: center;">
                </center>
                <div class="hstack gap-2 justify-content-center mt-3">
                    <button @click="hide()" class="btn btn-light btn-md" type="button">
                        <div class="btn-content"> Close</div>
                    </button>
                    <button @click="submit()" :disabled="keyword !== 'CONFIRM'" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
               tsr_id: null,
               questions: null,
               comment: null,
               attribute: null,
               option: 'survey'

            }),
            keyword: null,
            showModal: false
        }
    },
    methods: { 
        show(id,questions,comment,attribute){
            this.form.tsr_id = id;
            this.form.questions = questions;
            this.form.comment = comment;
            this.form.attribute = attribute;
            this.showModal = true;
        },
        submit(){
            this.form.post('/csf',{
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