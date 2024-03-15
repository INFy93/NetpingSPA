import {ref} from "vue";

export default function useNetpingStates() {
    const power = ref([]);
    const door = ref([]);
    const alarm = ref([]);
    const secure = ref([]);
    const powerState = async() => {
       let response = await axios.get('/api/power');
       power.value = response.data
    }

    const doorState = async() => {
        let response = await axios.get('/api/door');
        door.value = response.data
    }

    const alarmState = async() => {
        let response = await axios.get('/api/alarm');
        alarm.value = response.data
    }

    const secureState = async() => {
        let response = await axios.get('/api/secure');
        console.log(response.data)
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
