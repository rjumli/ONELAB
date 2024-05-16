<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Menu Activation" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div v-if="selected">
            <div class="mt-1 mb-n3">
                <div class="avatar-md mx-auto">
                    <div class="avatar-title rounded-circle bg-light">
                        <i class="h1 mb-0" :class="(selected.is_active) ? 'bx bxs-lock-open text-success' : 'bx bxs-lock text-danger'"></i>
                    </div>
                </div>
                <div class="p-2 mt-2 text-center">
                    <h6 class="mb-1" v-if="!selected.is_active">"Deactivate user access to the module."</h6>
                    <h6 class="mb-1" v-else>"Activate user access to the module."</h6>
                    <p v-if="!selected.is_active" class="font-size-12 text-muted"><span :class="(!selected.is_active) ? 'text-danger' : 'text-success'" class="fw-semibold">{{selected.name}}</span> will no longer be accessible to the users.</p>
                    <p v-else class="font-size-12 text-muted"><span :class="(!selected.is_active) ? 'text-danger' : 'text-success'" class="fw-semibold">{{selected.name}}</span> will be accessible to the users.</p>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                is_active: null,
                option: 'activation'
            }),
            selected: {},
            showModal: false,
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = data.id;
            this.form.is_active = (this.selected.is_active == 1) ? 0 : 1,
            this.showModal = true;
        },
        submit(){
            this.form.put('/utility/menus', {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('update',true);
                    this.selected = {};
                    this.form.reset();
                    this.form.clearErrors();
                    this.showModal = false;
                },
            });
        },
        hide(){
            this.$emit('update',false);
            this.selected = {};
            this.form.reset();
            this.form.clearErrors();
            this.showModal = false;
        }
    }
}
</script>