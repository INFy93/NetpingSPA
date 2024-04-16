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
        <tr v-for="(bdcom, index) in bdcoms">
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">
            {{ bdcom.bdcom_name }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Имя</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">
                {{ bdcom.bdcom_ip }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">IP</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">
                {{ bdcom.netping[0] === undefined ? "-" : bdcom.netping[0].name }}
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Точка</span>
            </td>
            <td
                class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">
                <span v-if="temps[index] !== undefined && temps[index]['bdcom_id'] === bdcom.id">
                                            <NormalTemp v-if="temps[index]['bdcom1_temp'] <= 69">{{
                                                    temps[index]['bdcom1_temp']
                                                }}</NormalTemp>
                                            <WarningTemp
                                                v-else-if="temps[index]['bdcom1_temp'] >= 70 && temps[index]['bdcom1_temp'] <= 74">{{
                                                    temps[index]['bdcom1_temp']
                                                }}</WarningTemp>
                                            <DangerTemp
                                                v-else-if="temps[index]['bdcom1_temp'] >= 75">{{
                                                    temps[index]['bdcom1_temp']
                                                }}</DangerTemp>
                                        </span>
                <span
                    class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">T°</span>
            </td>
        </tr>
        </tbody>

    </table>
</template>

<script setup>
import useBdcoms from "@/Composables/Bdcom/bdcom.js";
import {onMounted} from "vue";
import useBdcomTemperatures from "@/Composables/BdcomTemperature/BdcomTemperature.js";
import WarningTemp from "@/Components/Temperatures/WarningTemp.vue";
import NormalTemp from "@/Components/Temperatures/NormalTemp.vue";
import DangerTemp from "@/Components/Temperatures/DangerTemp.vue";


const {bdcoms, getBdcoms} = useBdcoms();
const {temps, getBdcomTemps} = useBdcomTemperatures();

onMounted(async () => {
    await getBdcoms();
    await getBdcomTemps(0);
})
setInterval(getBdcomTemps(0), 300000);
</script>
