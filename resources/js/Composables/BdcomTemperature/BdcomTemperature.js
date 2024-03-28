import {ref} from "vue";
import axios from "axios";

export default function useBdcomTemperatures()
{
    const temps = ref([]);

    const getBdcomTemps = async() => {
        let response = await axios.get('/api/bdcoms_current_temp');

        temps.value = response.data;
    }

    return {
        temps,
        getBdcomTemps
    }
}
