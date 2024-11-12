<template>
    <table id="main_table" class="border-collapse w-full mt-3">
        <thead>
        <tr>
            <th
                class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                Имя
            </th>
            <th
                class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                IP
            </th>
            <th
                class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                Точка
            </th>
            <th
                class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                T°
            </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-600">
        <tr v-for="data in temps.data">
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                {{ data.bdcom.bdcom_name }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Имя</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                {{ data.bdcom.bdcom_ip }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">IP</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                {{ data.netping === null ? "-" : data.netping.name }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Точка</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                <span>
                                            <NormalTemp v-if="data.temperature <= 69">{{
                                                    data.temperature
                                                }}</NormalTemp>
                                            <WarningTemp
                                                v-else-if="data.temperature >= 70 && data.temperature <= 74">{{
                                                    data.temperature
                                                }}</WarningTemp>
                                            <DangerTemp
                                                v-else-if="data.temperature >= 75">{{
                                                    data.temperature
                                                }}</DangerTemp>
                                        </span>
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">T°</span>
            </td>
        </tr>
        </tbody>
    </table>
    <TailwindPagination v-if="temps"
                        :active-classes="['bg-blue-50 border-blue-500 text-blue-600 dark:bg-gray-400 dark:border-gray-300 dark:text-gray-200']"
                        :data="temps"
                        :item-classes="['bg-white text-gray-600 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200']"
                        class="mt-2"
                        @pagination-change-page="changePage"
    />
</template>

<script setup>
import {onMounted, ref} from "vue";
import useBdcomTemperatures from "@/Composables/BdcomTemperature/BdcomTemperature.js";
import {TailwindPagination} from 'laravel-vue-pagination';
import WarningTemp from "@/Components/Temperatures/WarningTemp.vue";
import NormalTemp from "@/Components/Temperatures/NormalTemp.vue";
import DangerTemp from "@/Components/Temperatures/DangerTemp.vue";


const {temps, getBdcomTemps} = useBdcomTemperatures();
const index = ref(0);

onMounted(async () => {
    await getBdcomTemps(0, 1);
})
const changePage = async () => {
    const page = event.target.innerHTML;
    await getBdcomTemps(0, 1, page);
}

setInterval(getBdcomTemps(0, 1), 300000);
</script>
