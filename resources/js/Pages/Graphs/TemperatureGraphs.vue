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
                        <div class="flex flex-row space-x-2">
                            <PrimaryButton @click="isServer = 0">Все</PrimaryButton>
                            <PrimaryButton @click="isServer = 1">Серверная</PrimaryButton>
                        </div>

                        <label for="devices" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Период</label>
                        <select name="devices" id="devices" v-model="period"
                                class="block w-full mt-1 border-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="daily" selected>Сутки</option>
                            <option value="weekly" selected>Неделя</option>
                            <option value="monthly" selected>Месяц</option>
                            <option value="year" selected>Год</option>
                        </select>
                        <TailwindPagination v-if="temperatureData.bdcoms"
                            :data="bdcomData"
                            @pagination-change-page="getTemperatureDataForGraphs"
                                            class="mt-2"
                                            :item-classes="['bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200']"
                                            :active-classes="['bg-blue-50 border-blue-500 text-blue-600 dark:bg-gray-400 dark:border-gray-300 dark:text-gray-200']"
                        />
                </div>
                </div>
                <div class="mt-6" v-if="temperatureData">
                    <div v-for="(data, index) in temperatureData.graphs">
                        <div >
                            <div class="box grow mt-6" >
                                <highcharts :class="{ 'mt-[7rem]' : index === 0 }" v-if="data.options" :options="data.options" :redraw-on-update="true"></highcharts>
                            </div>
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import useTemperatureGraphs from "@/Composables/TemperatureGraphs/TemperatureGraphs.js";
import HighchartsVue from 'highcharts-vue';
import {onMounted, watch} from "vue";
import { TailwindPagination } from 'laravel-vue-pagination';
const { temperatureData, period, isServer, bdcomData, getTemperatureDataForGraphs } = useTemperatureGraphs();


onMounted(async () => {
    await getTemperatureDataForGraphs()
})

watch(period, async () =>{
    await getTemperatureDataForGraphs()
})

watch(isServer, async () => {
    await  getTemperatureDataForGraphs()
})

</script>

