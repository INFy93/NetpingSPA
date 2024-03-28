<template>
    <Head title="Netping"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Netping (обновление раз в
                20 сек)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table id="main_table" class="border-collapse w-full mt-3">
                    <thead>
                    <tr>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Имя точки
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Питание
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Охрана
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Дверь
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Сирена
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            IP точки
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Действия
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Камера
                        </th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            T°
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-600">
                    <tr v-for="(point, index) in netping.data">
                        <td
                            class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">
                            {{ point.name }}
                            <a href="#">
                                <svg class="h-5 w-5"
                                     fill="currentColor" style="float:right; display:inline-block; width: 10%;"
                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </a>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">

                            <span v-if="power[index] === undefined">Обновляю...</span>
                            <div v-else>
                                <Success v-if="power[index][2] === '0'">220V</Success>
                                <Danger v-else-if="power[index][2] === '1'">220V OFF</Danger>
                                <NoData v-else-if="power[index][2] === '3'">N/A</NoData>
                            </div>
                            <span
                                class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Питание</span>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                            <span v-if="secure.secure_data === undefined">Обновляю...</span>
                            <div v-else>
                                <Success
                                    v-if="secure.secure_data[index] === '1' || secure.secure_data[index] === 'direction:2'">
                                    Включена
                                </Success>
                                <Danger
                                    v-else-if="secure.secure_data[index] === '0' || secure.secure_data[index] === 'direction:1'">
                                    Отключена
                                </Danger>
                                <NoData v-else-if="secure.secure_data[index] === '3'">N/A</NoData>
                            </div>
                            <span
                                class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Охрана</span>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">

                            <span v-if="door[index] === undefined">Обновляю...</span>
                            <div v-else>
                                <Success v-if="door[index][2] === '0'">Закрыта</Success>
                                <Danger v-else-if="door[index][2] === '1'">Открыта!</Danger>
                                <NoData v-else-if="door[index][2] === '3'">N/A</NoData>
                            </div>

                            <span
                                class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Дверь</span>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                            <span v-if="alarm.alarm_data === undefined">Обновляю...</span>
                            <div v-else>
                                <Success
                                    v-if="(alarm.revision[index] === 2 && alarm.alarm_data[index][2] === '1') || (alarm.revision[index] === 4 && alarm.alarm_data[index][2] === '0')">
                                    Отключена
                                </Success>
                                <Danger
                                    v-else-if="(alarm.revision[index] === 2 && alarm.alarm_data[index][2] === '0') || (alarm.revision[index] === 4 && alarm.alarm_data[index][2] === '1')">
                                    ALARM!!!
                                </Danger>
                                <NoData v-else-if="alarm.alarm_data[index][2] === '3'">N/A</NoData>
                            </div>
                            <span
                                class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">Сирена</span>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                            {{ point.ip }}
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                           <span
                               class="netping_action text-blue-600 dark:text-blue-200 hover:text-blue-900 dark:hover:text-blue-300 underline cursor-pointer"
                               @click="switchAlarm(point.id)"
                           >
                                <span
                                    v-if="secure.secure_data !== undefined && (secure.secure_data[index] === '1' || secure.secure_data[index] === 'direction:2')">Снять с охраны</span>
                               <span
                                   v-else-if="secure.secure_data !== undefined && (secure.secure_data[index] === '0' || secure.secure_data[index] === 'direction:1')">Поставить на охрану</span>
                           </span>

                        </td>
                        <td
                            class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-300 border border-b text-left block lg:table-cell relative lg:static">
                            <a v-if="point.camera_ip" id="cam_link" data-bs-target="#cam_popup" data-bs-toggle="modal"
                               href="#">
                                <svg
                                    class="h-6 w-6"
                                    fill="none" stroke="currentColor" style="margin-left:auto; margin-right:auto; display:block;"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"/>
                                    <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round"
                                          stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </a>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-center border border-b block lg:table-cell relative lg:static">
                            <span v-if="temps[index] !== undefined && temps[index]['netping_id'] === point.id">
                                <NormalTemp v-if="temps[index]['bdcom1_temp'] <= 69">{{
                                        temps[index]['bdcom1_temp']
                                    }}</NormalTemp>
                                <WarningTemp
                                    v-else-if="temps[index]['bdcom1_temp'] >= 70 && temps[index]['bdcom1_temp'] <= 74">{{
                                        temps[index]['bdcom1_temp']
                                    }}</WarningTemp>
                                <DangerTemp
                                    v-else-if="temps[index]['bdcom1_temp'] >= 75">{{ temps[index]['bdcom1_temp'] }}</DangerTemp>
                            </span>
                            <span
                                v-if="temps[index] !== undefined && temps[index]['netping_id'] === point.id && temps[index]['bdcom2_temp']">
                                /
                                <NormalTemp v-if="temps[index]['bdcom2_temp'] <= 69">{{
                                        temps[index]['bdcom2_temp']
                                    }}</NormalTemp>
                                <WarningTemp
                                    v-else-if="temps[index]['bdcom2_temp'] >= 70 && temps[index]['bdcom2_temp'] <= 74">{{
                                        temps[index]['bdcom2_temp']
                                    }}</WarningTemp>
                                <DangerTemp
                                    v-else-if="temps[index]['bdcom2_temp'] >= 75">{{ temps[index]['bdcom2_temp'] }}</DangerTemp>
                            </span>
                            <span
                                class="lg:hidden absolute top-0 left-0 bg-blue-200 dark:bg-gray-700 px-2 py-1 text-xs font-bold uppercase">T°</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";
import useNetpingStates from "@/Composables/NetpingStates/NetpingStates.js";
import useBdcomTemperatures from "@/Composables/BdcomTemperature/BdcomTemperature.js";
import useSecure from "@/Composables/Secure/Secure.js";
import Success from "@/Components/States/Success.vue";
import Danger from "@/Components/States/Danger.vue";
import NoData from "@/Components/States/NoData.vue";
import {onMounted} from "vue";
import NormalTemp from "@/Components/Temperatures/NormalTemp.vue";
import WarningTemp from "@/Components/Temperatures/WarningTemp.vue";
import DangerTemp from "@/Components/Temperatures/DangerTemp.vue";

defineProps({
    netping: Object
})
const {switchAlarm} = useSecure();
const {powerState, doorState, alarmState, secureState, power, door, alarm, secure} = useNetpingStates();
const {temps, getBdcomTemps} = useBdcomTemperatures();

onMounted(async () => {
    await powerState();
    await secureState();
    await doorState();
    await alarmState();
    await getBdcomTemps();
})
setInterval(powerState, 20000);
setInterval(secureState, 20000);
setInterval(doorState, 20000);
setInterval(alarmState, 20000);
//setInterval(async () => router.reload({ only: ['netping'] }), 3000);
</script>
