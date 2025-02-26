<template>
    <Head title="Netping"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Netping (обновление раз в 20 сек)
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-[89rem] mx-auto sm:px-6 lg:px-8">
                <TabGroup>
                    <TabList class="flex flex-wrap -mb-px font-medium text-center">
                        <Tab v-slot="{ selected }" as="template">
                            <button
                                :class="{ 'border-b-4 border-blue-500 dark:border-gray-500 dark:text-gray-200' : selected }"
                                class="mr-2 inline-flex outline-none p-4 border-b-2 border-transparent hover:border-blue-400 dark:hover:text-gray-300 dark:hover:border-gray-400 dark:text-gray-200"
                            >Netping
                            </button>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <button
                                :class="{ 'border-b-4 border-blue-500 dark:border-gray-500 dark:text-gray-200' : selected }"
                                class="mr-2 inline-flex outline-none p-4 border-b-2 border-transparent hover:border-blue-400 dark:hover:text-gray-300 dark:hover:border-gray-400 dark:text-gray-200"
                            >BDCOM
                            </button>
                        </Tab>
                    </TabList>
                    <TabPanels class="mt-2">
                        <TabPanel>
                            <div class="grid lg:grid-cols-4 gap-4 md:grid-cols-2">
                                <div v-for="(point, index) in netping.data"
                                     :key="point.id"
                                     class="max-w-sm p-3 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div>
                                        <div class="flex flex-row justify-items-center items-center">
                                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                                {{ point.name }}
                                            </h5>
                                            <span v-if="point.camera_ip"
                                                  @click="openCameraModal(point.id)"
                                                  class="flex ml-auto justify-end text-gray-900 dark:text-white cursor-pointer">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                          <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        </svg>
                      </span>
                                        </div>
                                        <h6 class="mb-2 text-base font-medium leading-tight text-surface/75 dark:text-neutral-300">
                                            {{ point.ip }}
                                        </h6>
                                    </div>
                                    <div
                                        class="flex flex-row mb-3 mt-2 space-x-3 font-normal text-gray-800 dark:text-gray-100">
                                        <!-- Power -->
                                        <span v-if="power[index] === undefined">
                      <img src="/storage/img/load_table.svg" style="width: 24px; margin: 0 auto;"/>
                    </span>
                                        <div v-else>
                                            <Success v-if="power[index][2] === '0'">220V</Success>
                                            <Danger v-else-if="power[index][2] === '1'">220V</Danger>
                                            <NoData v-else-if="power[index][2] === '3'">N/A</NoData>
                                        </div>

                                        <!-- Secure -->
                                        <span v-if="secure.secure_data === undefined">
                      <img src="/storage/img/load_table.svg" style="width: 24px; margin: 0 auto;"/>
                    </span>
                                        <div v-else>
                                            <Success
                                                v-if="secure.secure_data[index] === '1' || secure.secure_data[index] === 'direction:2'">
                                                Охрана
                                            </Success>
                                            <Danger
                                                v-else-if="secure.secure_data[index] === '0' || secure.secure_data[index] === 'direction:1'">
                                                Охрана
                                            </Danger>
                                            <NoData v-else-if="secure.secure_data[index] === '3'">N/A</NoData>
                                        </div>

                                        <!-- Door -->
                                        <span v-if="door[index] === undefined">
                      <img src="/storage/img/load_table.svg" style="width: 24px; margin: 0 auto;"/>
                    </span>
                                        <div v-else>
                                            <Success v-if="door[index][2] === '0'">Дверь</Success>
                                            <Danger v-else-if="door[index][2] === '1'">Дверь</Danger>
                                            <NoData v-else-if="door[index][2] === '3'">N/A</NoData>
                                        </div>

                                        <!-- Alarm -->
                                        <span v-if="alarm.alarm_data === undefined">
                      <img src="/storage/img/load_table.svg" style="width: 24px; margin: 0 auto;"/>
                    </span>
                                        <div v-else>
                                            <Success
                                                v-if="(alarm.revision[index] === 2 && alarm.alarm_data[index][2] === '1') || (alarm.revision[index] === 4 && alarm.alarm_data[index][2] === '0')">
                                                Сирена
                                            </Success>
                                            <Danger
                                                v-else-if="(alarm.revision[index] === 2 && alarm.alarm_data[index][2] === '0') || (alarm.revision[index] === 4 && alarm.alarm_data[index][2] === '1')">
                                                Сирена
                                            </Danger>
                                            <NoData v-else-if="alarm.alarm_data[index][2] === '3'">N/A</NoData>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-row mb-3 space-x-3 font-normal text-gray-800 dark:text-gray-100">
                    <span>BDCOM:
                      <span v-if="temps[index] !== undefined && temps[index][0].netping.id === point.id">
                        <span v-for="(temp, i) in temps[index]" :key="i">
                          <NormalTemp v-if="temp.temperature <= 69">{{ temp.temperature }}</NormalTemp>
                          <WarningTemp v-else-if="temp.temperature >= 70 && temp.temperature <= 74">{{
                                  temp.temperature
                              }}</WarningTemp>
                          <DangerTemp v-else-if="temp.temperature >= 75">{{ temp.temperature }}</DangerTemp>
                          <span
                              v-if="Object.keys(temps[index]).length >= 1 && i !== Object.keys(temps[index]).length - 1">
                            /
                          </span>
                        </span> °С
                      </span>
                    </span>
                                    </div>
                                    <div class="flex flex-row">
                    <span @click="switchSecureStatus(point.id)"
                          class="cursor-pointer inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-800 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                      <span
                          v-if="secure.secure_data !== undefined && (secure.secure_data[index] === '1' || secure.secure_data[index] === 'direction:2')">
                        Снять с охраны
                      </span>
                      <span
                          v-else-if="secure.secure_data !== undefined && (secure.secure_data[index] === '0' || secure.secure_data[index] === 'direction:1')">
                        Поставить на охрану
                      </span>
                    </span>
                                        <span @click="switchVentStatus(point.id)"
                                              v-if="vent[index] !== undefined && point.id === vent[index].id && vent[index].state[2] !== '3'"
                                              class="flex justify-end ml-auto cursor-pointer inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-800 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                      <span v-if="vent[index].state[2] === '0'">Вентилятор OFF</span>
                      <span v-else-if="vent[index].state[2] === '1'">Вентилятор ON</span>
                    </span>
                                    </div>
                                </div>
                            </div>
                        </TabPanel>
                        <TabPanel>
                            <Bdcom></Bdcom>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
            <Modal :show="openingCameraModal" @close="closeModal" max-width="camera">
                <div v-if="cameraData.length === 0">TEST</div>
                <div v-else>
                    <img :src="cameraData" alt="camera">
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {router} from "@inertiajs/vue3";
import Bdcom from "@/Pages/Bdcom/Bdcom.vue";
import useNetpingStates from "@/Composables/NetpingStates/NetpingStates.js";
import useBdcomTemperatures from "@/Composables/BdcomTemperature/BdcomTemperature.js";
import useSecure from "@/Composables/Secure/Secure.js";
import useCamera from "@/Composables/Camera/Camera.js";
import useVent from "@/Composables/Vent/Vent.js";
import Success from "@/Components/States/Success.vue";
import Danger from "@/Components/States/Danger.vue";
import NoData from "@/Components/States/NoData.vue";
import Modal from "@/Components/Modal.vue";
import NormalTemp from "@/Components/Temperatures/NormalTemp.vue";
import WarningTemp from "@/Components/Temperatures/WarningTemp.vue";
import DangerTemp from "@/Components/Temperatures/DangerTemp.vue";

defineProps({
    netping: Object
});

const {switchAlarm} = useSecure();
const {switchVent} = useVent();

const {
    powerState,
    doorState,
    alarmState,
    secureState,
    ventState,
    power,
    door,
    alarm,
    secure,
    vent
} = useNetpingStates();

const {temps, getBdcomTemps} = useBdcomTemperatures();
const {cameraData, getCameraImage} = useCamera();

const openingCameraModal = ref(false);
const cameraInterval = ref(null);
const refreshInterval = ref(null);
const bdcomInterval = ref(null);

// Обновляем все состояния за один вызов
const refreshAllStates = async () => {
    await Promise.all([
        powerState(),
        secureState(),
        doorState(),
        alarmState(),
        ventState()
    ]);
};

onMounted(async () => {
    await Promise.all([
        powerState(),
        secureState(),
        doorState(),
        alarmState(),
        ventState(),
        getBdcomTemps(1)
    ]);

    // Обновляем состояния каждые 20 секунд
    refreshInterval.value = setInterval(refreshAllStates, 20000);

    // Обновляем Bdcom температуры каждые 300 секунд (5 минут)
    bdcomInterval.value = setInterval(() => {
        getBdcomTemps(1);
    }, 300000);
});

onUnmounted(() => {
    if (refreshInterval.value) clearInterval(refreshInterval.value);
    if (bdcomInterval.value) clearInterval(bdcomInterval.value);
    if (cameraInterval.value) clearInterval(cameraInterval.value);
});

const openCameraModal = async (id) => {
    openingCameraModal.value = true;
    await getCameraImage(id);
    cameraInterval.value = setInterval(() => {
        getCameraImage(id);
    }, 8000);
};

const closeModal = () => {
    openingCameraModal.value = false;
    if (cameraInterval.value) clearInterval(cameraInterval.value);
};

const switchSecureStatus = async (id) => {
    await switchAlarm(id);
    await secureState();
};

const switchVentStatus = async (id) => {
    await switchVent(id);
    await ventState();
};
</script>
