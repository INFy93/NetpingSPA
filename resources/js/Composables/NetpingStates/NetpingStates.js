import {ref} from "vue";
import axios from "axios";

export default function useNetpingStates() {
    const power = ref([]);
    const door = ref([]);
    const alarm = ref([]);
    const secure = ref([]);
    const vent = ref([]);
    const powerState = async () => {
        power.value = [];
        let response = await axios.get('/api/power');

        power.value = response.data;
    }

    const doorState = async () => {
        door.value = [];
        let response = await axios.get('/api/door');
        door.value = response.data
    }

    const alarmState = async () => {
        alarm.value = [];
        let response = await axios.get('/api/alarm');
        alarm.value = response.data
    }

    const secureState = async () => {
        secure.value = [];
        let response = await axios.get('/api/secure');
        secure.value = response.data
    }

    const ventState = async () => {
        vent.value = [];
        let response = await axios.get('/api/vent');
        vent.value = response.data
    }

    return {
        power,
        door,
        alarm,
        secure,
        vent,
        powerState,
        doorState,
        alarmState,
        ventState,
        secureState
    }
}
