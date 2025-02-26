<template>
    <Head title="Графики"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Графики температур
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Блок фильтров и пагинации -->
                <div class="mb-8 flex flex-col items-start space-y-4">
                    <!-- Фильтры с фиксированной шириной -->
                    <div class="w-72">
                        <div class="flex flex-row space-x-2">
                            <PrimaryButton @click="isServer = 0">Все</PrimaryButton>
                            <PrimaryButton @click="isServer = 1">Серверная</PrimaryButton>
                        </div>
                        <label for="devices" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Период
                        </label>
                        <select name="devices" id="devices" v-model="period"
                                class="block w-full mt-1 border-gray-300 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="daily">Сутки</option>
                            <option value="weekly">Неделя</option>
                            <option value="monthly">Месяц</option>
                            <option value="year">Год</option>
                        </select>
                    </div>
                    <!-- Пагинация -->
                    <div class="w-full flex flex-wrap justify-left">
                        <TailwindPagination
                            v-if="temperatureData.bdcoms"
                            :data="bdcomData"
                            @pagination-change-page="fetchData"
                            class="mt-2"
                            :limit="3"
                            :item-classes="['bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200']"
                            :active-classes="['bg-blue-50 border-blue-500 text-blue-600 dark:bg-gray-400 dark:border-gray-300 dark:text-gray-200']"
                        />
                    </div>
                </div>

                <!-- Область графиков с оверлеем-плейсхолдером -->
                <div class="relative">
                    <!-- Оверлей, затемняющий область графиков -->
                    <div v-if="loading"
                         class="absolute inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <img src="/storage/img/load_table.svg" alt="Загрузка графиков" class="max-w-xs"/>
                    </div>
                    <!-- Графики -->
                    <div v-if="temperatureData" class="min-h-[300px]">
                        <div v-for="(data, index) in temperatureData.graphs" :key="index" class="mt-6">
                            <div class="box grow">
                                <highcharts v-if="data.options"
                                            :options="data.options"
                                            :redraw-on-update="true"/>
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
import HighchartsVue from "highcharts-vue";
import {onMounted, watch, ref} from "vue";
import {TailwindPagination} from "laravel-vue-pagination";

const {temperatureData, period, isServer, bdcomData, getTemperatureDataForGraphs} = useTemperatureGraphs();
const loading = ref(true);

const fetchData = async (page = 1) => {
    loading.value = true;
    await getTemperatureDataForGraphs(page);
    loading.value = false;
};

onMounted(async () => {
    await fetchData();
});

watch(period, async () => {
    await fetchData();
});

watch(isServer, async () => {
    await fetchData();
});
</script>
