<template>
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="Releasing Officer" />
    <b-row class="g-4">
         <div class="col-md-12 mb-n4">
            <div class="card">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <Lists :tsrs="lists.data"/>
        </div>
        <div class="col-md-6">
            <Csf />
        </div>
    </b-row>
</template>
<script>
import Csf from './Components/Csf.vue';
import Lists from './Components/Lists.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Lists, Csf },
    props: ['lists']
}
</script>