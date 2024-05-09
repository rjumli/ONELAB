<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="View Item" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <b-row class="mt-n1 border-bottom g-1 mb-3">
            <b-col md v-if="selected" class="mb-3">
                <b-row class="align-items-center g-1">
                    <b-col md="auto">
                        <b-img class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;" alt="img" src="/images/avatars/avatar.jpg" data-holder-rendered="true"></b-img>
                    </b-col>
                    <b-col md>
                        <div class="ms-2 mt-n2">
                            <h5 class="modal-title fs-16">{{selected.name}}</h5>
                            <div class="hstack gap-3 flex-wrap mt-0 mb-n2">
                                <div class="text-primary">
                                    <span class="text-body text-muted fs-12">Code :</span> <span class="text-body fs-12">{{selected.code}}</span>
                                </div>
                                <div class="vr"></div>
                                <div class="text-primary">
                                    <span class="text-body text-muted fs-12">Category :</span> <span class="text-body fs-12">{{selected.category}}</span>
                                </div>
                                <div class="vr"></div>
                                <div class="text-primary">
                                    <span class="text-body text-muted fs-12">Brand :</span> <span class="text-body fs-12">{{selected.brand}}</span>
                                </div>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
        <div class="row mb-2 g-1 border-bottom" v-if="selected">
            <div class="col-sm-4 mb-3">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-price-tag-2-line"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Price :</p>
                            <h5 class="fs-13 mb-0">{{selected.price}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri ri-qr-scan-2-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Serial no. / Batch no. :</p>
                            <h5 class="fs-13 text-success mb-0">{{selected.number}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-2-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Bought at:</p>
                            <h5 class="fs-13 mb-0">{{selected.bought}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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