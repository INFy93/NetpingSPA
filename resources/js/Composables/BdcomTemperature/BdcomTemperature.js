import {ref} from "vue";
import axios from "axios";

export default function useBdcomTemperatures()
{
    const temps = ref([]);

    const getBdcomTemps = async(group) => {
        let response = await axios.get('/api/bdcoms_current_temp?group=' + group);

        temps.value = response.data;
    }

    return {
        temps,
        getBdcomTemps
    }
}
