<template>
<div class="">
    <form @submit.prevent="submit()" class="customforma">
        <BCard no-body>
            <BCardHeader class="align-items-center d-flex py-0" style="height: 59px;">
                <BCardTitle class="mb-0 flex-grow-1">Access Management</BCardTitle>
            </BCardHeader>
            <BCardBody class="p-4 " style="height: calc(100vh - 280px); overflow: auto;">
                <b-list-group v-for="(list,index) in activemenus" v-bind:key="index">
                    <b-list-group-item class="d-flex justify-content-between align-items-center">
                        {{list.name}} 
                        <span class="">
                            <div class="form-check form-switch" :class="(list.type == 'Menu') ? 'form-switch-primary' : 'form-switch-warning'">
                                <input class="form-check-input" type="checkbox" role="switch" @click="handleSwitchChange(list,index)" v-model="list.is_active" id="menuSwitch{{index}}">
                            </div>
                        </span>
                    </b-list-group-item>
                </b-list-group>
            </BCardBody>
        </BCard>
    </form>
</div>
<Activation @update="changeStatus" ref="activation"/>
</template>
<script>
import _ from 'lodash';
import Activation from '../Modals/Menus/Activation.vue';
import Layout from "@/Shared/Layouts/Main.vue";
import profile from "@/Pages/Modules/User/Utility/Index.vue";
export default {
    layout: (h,page) => {
        return h(Layout, [
            h(profile,[page])
        ])
    },
    components: { Activation },
    props: ['activemenus'],
    data(){
        return {
            test : true,
            index: null,
            status: null
        }
    },
    methods: {
        handleSwitchChange(data,index){
            this.index = index;
            this.status = data.is_active;
            this.$refs.activation.show(data,index);
        },
        changeStatus(val){
            if(!val){
                const status = (this.status) ? true : false;
                this.activemenus[this.index].is_active = status;
            }
        }
    }
}
</script>