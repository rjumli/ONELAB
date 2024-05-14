<template>
    <Head title="Order of Payment"/>
    <PageHeader title="Order of Payment" pageTitle="Finance" />
    <b-row>
        <div class="col-md-12 mt-0 mb-n2">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mt-2 text-primary fw-semibold">{{(laboratory) ? laboratory.name : 'All Laboratory'}}</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <select @change="changeLab()" v-model="laboratory" class="form-select">
                                    <option :value="null" selected>All Laboratory</option>
                                    <option :value="list" v-for="list in dropdowns.laboratories" v-bind:key="list.id">{{list.name}}</option>
                                </select>
                                <b-button @click="openCreate" type="button" variant="primary">
                                    Create &nbsp;<i class="ri-add-circle-fill"></i>
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body" style="height: calc(100vh - 305px); overflow: auto;">
                            <Lists 
                            :collections="dropdowns.collections" 
                            :payments="dropdowns.payments" 
                            :deposits="dropdowns.deposits" 
                            :orseries="dropdowns.orseries"
                            :statuses="dropdowns.statuses"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="height: calc(100vh - 305px); overflow: auto;">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </b-row>
</template>
<script>
import Lists from './Components/Lists.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['dropdowns'],
    components: { PageHeader, Lists },
    data(){
        return {
            laboratory: null,
        }
    },
}
</script>