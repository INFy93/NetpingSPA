<template>
    <Head title="Графики"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Графики температур</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="relative h-10 w-72 min-w-[200px]">
                    <div class="mb-8">
                        <label for="devices" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Период</label>
                        <select name="devices" id="devices" v-model="period"
                                class="block w-full mt-1 border-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="%d.%m.%Y %H:%i" selected>Дни и часы</option>
                            <option value="%d.%m.%Y" selected>Дни</option>
                        </select>
                </div>
                </div>
                <div class="mt-6" v-if="temperatureData.length">
                    <div v-for="data in temperatureData">
                        <span class="text-gray-800 dark:text-gray-200">{{ data.bdcom_name }}</span>
                        <div class="box grow">
                            <highcharts v-if="data.options" :options="data.options" :redraw-on-update="true"></highcharts>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import useTemperatureGraphs from "@/Composables/TemperatureGraphs/TemperatureGraphs.js";
import HighchartsVue from 'highcharts-vue';
import {onMounted, watch} from "vue";
import { TailwindPagination } from 'laravel-vue-pagination';
const { temperatureData, period, getTemperatureDataForGraphs } = useTemperatureGraphs();


onMounted(async () => {
    await getTemperatureDataForGraphs()
})

watch(period, async () =>{
    await getTemperatureDataForGraphs()
})

</script>

