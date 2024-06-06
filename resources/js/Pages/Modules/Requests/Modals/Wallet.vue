<template>
    <b-modal v-model="showModal" hide-footer title="Wallet" class="v-modal-custom"  header-class="p-3 bg-light" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="alert alert-danger fs-11 fw-semibold" role="alert">
            The system has detected that the customer has a balance in their wallet.
        </div>
        <div class="input-group mb-0">
            <label class="input-group-text"><i class="ri-wallet-3-fill align-middle me-2"></i> Wallet Balance</label>
            <input type="text" :value="selected.wallet.available" class="form-control" readonly style="text-align: right;">
        </div>
        <hr class="text-muted"/>
        <div class="table-responsive">
            <table class="table table-bordered mt-0 mb-0">
                <tbody>
                    <tr>
                        <td>Sub Total :</td>
                        <td class="text-end" id="cart-subtotal">{{selected.payment.subtotal}}</td>
                    </tr>
                    <tr>
                        <td>Discount : </td>
                        <td class="text-end" id="cart-discount">{{selected.payment.discount}}</td>
                    </tr>
                    <tr class="table-active">
                        <th>Total :</th>
                        <td class="text-end"><span class="fw-semibold" id="cart-total"> {{selected.payment.total}} </span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr class="text-muted mb-0"/>
        <div class="d-grid gap-2">
            <b-button @click="submit()" class="mt-3" variant="primary">Use Wallet</b-button>
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
               id: null,
               total: null,
               tsr_id: null,
               wallet_id: null,
               option: 'wallet'
            }),
            selected: {
                wallet: {},
                payment: {}
            },
            keyword: null,
            showModal: false
        }
    },
    methods: { 
        show(id,customer,payment){
            this.form.tsr_id = id;
            this.form.id = payment.id;
            this.form.total = payment.total;
            this.form.wallet_id = customer.wallet.id;
            this.selected = customer;
            this.selected.payment = payment;
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