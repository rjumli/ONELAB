<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 1100px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Package' : 'Edit Package'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
            
        <form class="customform">
            <BRow>
                <BCol lg="12" class="mt-2">
                    <InputLabel for="name" value="Package name" :message="form.errors.name" />
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter package name" :light="true"/>
                </BCol>  
                <BCol lg="4" class="mt-1">
                    <InputLabel for="classification_id" value="Laboratory Type" :message="form.errors.laboratory_type"/>
                    <Multiselect :options="dropdowns.types" :searchable="true" v-model="form.laboratory_type" ref="multiselectL" placeholder="Select Laboratory type"/>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="sampletype" value="Sample type" :message="form.errors.sampletype_id"/>
                    <Multiselect @search-change="checkSearchSample" 
                    :options="sampletypes" label="name" :searchable="true" 
                    :clearOnSearch="true" v-model="form.sampletype_id"
                    placeholder="Select Sample type" ref="multiselectS"/>
                </BCol>
                
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Fee" :message="form.errors.fee"/>
                    <Amount @amount="amount" ref="testing" :readonly="true"/>
                </BCol>
            </BRow>
        </form>
        <BRow>
            <BCol lg="12" class="mt-1">
                <hr class="text-muted"/>
            </BCol>
            <BCol lg="12" class="mt-0" v-if="form.sampletype_id">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="searchTerm" @input="search" placeholder="Search Test Service" class="form-control" style="width: 35%;">
                        <b-button type="button" variant="primary">
                            {{countTestServices}} testservices selected
                        </b-button>
                    </div>
                </b-col>
                <div class="table-responsive mt-2">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th style="width: 7%;"></th>
                                <th style="width: 25%;" class="text-center">Testname</th>
                                <th style="width: 53%;" class="text-center">Method</th>
                                <th style="width: 15%;" class="text-center">Fee</th>
                            </tr>
                        </thead>
                    </table>
                    <simplebar data-simplebar style="max-height: 200px">
                        <div>
                            <table class="table table-centered table-bordered table-nowrap mb-0">
                                <tbody>
                                    <tr v-for="(list,index) in testservices" v-bind:key="index" :class="(list.is_checked) ? 'table-success' : (index == matchedRowIndex) ? 'table-warning' : ''" :id="'row-' + index">
                                        <td style="width: 7%;" class="text-center"> 
                                            <input class="form-check-input me-1" type="checkbox" v-model="list.is_checked">
                                        </td>
                                        <td style="width: 25%;" class="text-center fs-11">{{list.testname}}</td>
                                        <td style="width: 53%;" class="text-center fs-11">{{list.method}} <span v-if="list.method_short" class="text-muted">({{list.method_short}})</span></td>
                                        <td style="width: 15%;" class="text-center fs-11">{{list.fee}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </simplebar>
               </div>
            </BCol>
        </BRow>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import simplebar from 'simplebar-vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import Multiselect from '@/Shared/Components/Forms/Multiselect.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { simplebar, InputLabel, TextInput, Multiselect, Amount },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                fee: null,
                laboratory_type: null,
                sampletype_id: null,
                lists: [],
                option: 'create'
            }),
            filter: {
                keyword: null,
                sampletype: null
            },
            searchTerm: null,
            matchedRowIndex: null,
            sampletypes: [],
            testservices: [],
            type: null,
            showModal: false,
            editable: false,
        }
    },
    watch: {
        'form.laboratory_type'(){
            this.sampletypes = [];
            this.$refs.multiselectS.clear();
            this.form.sampletype_id = null;
        },
        'form.sampletype_id'(){
            this.fetchTest();
        },
        totalFee(newTotalFee) {
            this.form.fee = newTotalFee;
            this.$refs.testing.emitValue(this.form.fee);
        }
        
    },
    computed: {
        totalFee() {
            const total = this.testservices.reduce((acc, item) => {
                return item.is_checked ? acc + parseFloat(item.fee_num) : acc;
            }, 0);
            return total.toFixed(2);
        },
        countTestServices() {
            return this.testservices.filter(item => item.is_checked).length;
        },
        checkedItems() {
            const test = this.testservices.filter(item => item.is_checked);
            return test.map(item => item.value);
        },
    },
    methods: { 
        show(){
            this.showModal = true;
        }, 
        submit(){
            this.form.lists = this.checkedItems;
            this.form.post('/services/packages',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        amount(val){
            this.form.fee = val;
        },
        checkSearchSample: _.debounce(function(string) {
            (string) ? this.fetchSample(string) : '';
            this.filter.sampletype = string;
        }, 300),
        fetchSample(code){
            this.sampletypes = [];
            axios.get('/services/testservices',{
                params: {
                    option: 'search',
                    laboratory_type: this.form.laboratory_type,
                    type: 96,
                    keyword: code
                }
            })
            .then(response => {
                this.sampletypes = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchTest(code){
            axios.get('/services/packages',{
                params: {
                    option: 'testservices',
                    laboratory_type: this.form.laboratory_type,
                    sampletype_id: this.form.sampletype_id,
                    keyword: code
                }
            })
            .then(response => {
                this.testservices = response.data.data;
            })
            .catch(err => console.log(err));
        },
        search() {
            const searchTerm = this.searchTerm.toLowerCase();
            const matchedIndex = this.testservices.findIndex(
                (l) => l.testname.toLowerCase().includes(searchTerm) || 
                l.method.toLowerCase().includes(searchTerm) || l.method_short.toLowerCase().includes(searchTerm)
            );
            if (matchedIndex !== -1 && searchTerm !== '') {
                this.matchedRowIndex = matchedIndex;

                const rowId = 'row-' + matchedIndex;
                const matchedRow = document.getElementById(rowId);

                if (matchedRow) {
                matchedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                this.matchedRowIndex = null;
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.$refs.multiselectL.clear();
            this.form.reset();
            this.form.clearErrors();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>