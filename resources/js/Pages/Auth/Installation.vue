<template>
    <Head title="Installation"></Head>
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="text-center mb-2">
            <a to="/" class="d-block auth-logo">
                <img src="/images/logo-sm.png" alt="" height="50" class="auth-logo-dark mx-auto" />
            </a>
            <p class="font-size-11 mt-3">{{member.member.name}}<br> One-Stop Laboratory Services for Global Competitiveness</p>
          
        </div>
        
        <div class="mb-5 customform" style="width: 900px;">
            <div class="text-center mb-2">
                <p class="text-muted">Please select all laboratories offered by the agency from the dropdown menu.</p>
            </div>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label" for="address">Name  <span class="text-muted">(This will be used in printing reports)</span></label>
                    <TextInput type="text" v-model="form.name" class="form-control" :light="true"/>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="address">Acronym <span class="text-muted">(This will be used in printing reports)</span></label>
                    <TextInput type="text" v-model="form.acronym" class="form-control" :light="true"/>
                </div>
                <div class="col-md-12">
                        <Multiselect label="name"
                        :options="laboratories" 
                        :searchable="true" 
                        object
                        :close-on-select="true"
                        v-model="laboratory"
                        mode="multiple"
                        placeholder="Select Laboratory"/>
                </div>
                <div class="col-md-6">
                    <b-list-group>
                        <b-list-group-item v-for="(list,index) in leftColumn" v-bind:key="index" class="d-flex justify-content-between align-items-center">
                        {{list.name}} <i @click="remove(list.value)" class="ri-delete-bin-5-line align-middle me-2" style="cursor: pointer;"></i>
                        </b-list-group-item>
                    </b-list-group>
                </div>
                <div class="col-md-6">
                     <b-list-group>
                        <b-list-group-item v-for="(list,index) in rightColumn" v-bind:key="index" class="d-flex justify-content-between align-items-center">
                        {{list.name}} <i @click="remove(list.value)" class="ri-delete-bin-5-line align-middle me-2" style="cursor: pointer;"></i>
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </div>
             <center>
                <button @click="submit()" type="button" class="mt-4 mb-4 btn w-lg btn-primary">Submit</button>
            </center>
        </div>
        
        <div class="text-center">
            <div>
                <p>© DOST-ONELAB <i class='bx bxs-heart text-danger'></i> 2024</p>
            </div>
        </div>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, TextInput },
    props:['member','laboratories'],
    layout: null,
    data(){
        return {
            currentUrl: window.location.origin,
            laboratory: [],
            form: useForm({
                name: this.member.member.name,
                acronym: this.member.name,
                laboratories: [],
                laboratory_id: this.member.id
            }),
        }
    },
    watch: {
        'laboratory'(){
            this.form.laboratories = this.laboratory.map(item => item.value);
        },
    },
    computed: {
        leftColumn() {
            const middleIndex = Math.ceil(this.laboratory.length / 2);
            return this.laboratory.slice(0, middleIndex);
        },
        rightColumn() {
            const middleIndex = Math.ceil(this.laboratory.length / 2);
            return this.laboratory.slice(middleIndex);
        }
    },
    methods: {
        remove(value){
            this.laboratory = this.laboratory.filter(item => item.value !== value);
        },
        submit(){
            this.form.post('/install',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$inertia.get('/');
                },
            });
        },
    }
}
</script>
<style>
.row {
  display: flex;
}

.item {
  margin: 5px;
  padding: 10px;
  border: 1px solid #ccc;
}
</style>