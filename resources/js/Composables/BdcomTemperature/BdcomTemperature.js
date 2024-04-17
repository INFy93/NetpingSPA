import {ref} from "vue";
import axios from "axios";

export default function useBdcomTemperatures()
{
    const temps = ref([]);

    const getBdcomTemps = async(group) => {
        let response = await axios.get('/api/get_temps?group=' + group);

        temps.value = response.data;
    }

    return {
        temps,
        getBdcomTemps
    }
}
