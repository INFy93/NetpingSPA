import {ref} from "vue";

export default function useNetpingStates() {
    const power = ref([]);
    const door = ref([]);
    const alarm = ref([]);
    const secure = ref([]);
    const powerState = async () => {
        power.value = [];
        let response = await axios.get('/api/power');

        power.value = response.data;
    }

    const doorState = async() => {
        door.value = [];
        let response = await axios.get('/api/door');
        door.value = response.data
    }

    const alarmState = async() => {
        alarm.value = [];
        let response = await axios.get('/api/alarm');
        alarm.value = response.data
    }

    const secureState = async() => {
        secure.value = [];
        let response = await axios.get('/api/secure');
        secure.value = response.data
    }

    return {
        power,
        door,
        alarm,
        secure,
        powerState,
        doorState,
        alarmState,
        secureState
    }
}
