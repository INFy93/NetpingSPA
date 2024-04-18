<template>
    <Head title="Графики"/>

    <AuthenticatedLayout>
        Тут будут графики
    </AuthenticatedLayout>
</template>

<script>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    name: "TemperatureGraphs",
    components: {AuthenticatedLayout, Head}
}
</script>

