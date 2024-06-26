<template>
    <Head title="Customer Insights"/>
    <PageHeader title="Customer Insights" pageTitle="Menu" />
    <BRow style="height: calc(100vh - 225px); overflow: auto;">
        <BCol xl="8">
            <Info/>
        </BCol>
        <BCol xl="4">
            <Location :location="customer.location" :total="customer.total" :wallet="customer.wallet"/>
        </BCol>
        <BCol xl="4">
            <Industry :industry="customer.industry" :total="customer.total"/>
        </BCol>
        <BCol xl="4">
            <Tsr :tsr="customer.tsr" :total="customer.total_request"/>
        </BCol>
        <BCol xl="4">
            <Spender :spender="customer.spender"/>
        </BCol>
    </BRow>
</template>
<script>
import Info from './Components/Info.vue';
import Tsr from './Components/Tsr.vue';
import Spender from './Components/Spender.vue';
import Industry from './Components/Industry.vue';
import Location from './Components/Location.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['customer'],
    components: { PageHeader, Industry, Spender, Tsr, Location, Info }
}
</script>