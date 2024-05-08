<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="View Item" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        {{selected}}
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Add to Cart</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: null,
            form: {}
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.$emit('add',this.selected);
            this.showModal = false;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>