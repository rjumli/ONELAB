<template lang="">
    <Head title="Import"/>
    <PageHeader title="Import Test Services" pageTitle="Import" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" placeholder="Search Testservices" class="form-control" style="width: 35%;">
                        <span @click="openUpload()" class="input-group-text" v-b-tooltip.hover title="Upload Scholars" style="cursor: pointer;"> 
                            <i class="ri-upload-cloud-fill search-icon"></i>
                        </span>
                        <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                            <i class="bx bx-refresh search-icon"></i>
                        </span>
                        <b-button @click="openFilter()" type="button" variant="primary">
                            <i class="ri-filter-fill search-icon"></i>
                        </b-button>
                    </div>
                </b-col>
            </b-row>
        </div>
    </div>
    <Upload ref="upload" @status="fetch()"/>
</template>
<script>
import Upload from './Modals/Upload.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Upload },
    methods: {
        openUpload(){
            this.$refs.upload.show();
        },
    }
}
</script>