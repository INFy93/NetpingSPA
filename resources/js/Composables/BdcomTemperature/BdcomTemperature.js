import {ref} from "vue";
import axios from "axios";

export default function useBdcomTemperatures()
{
    const temps = ref([]);

    const getBdcomTemps = async(group, paginate = 0, page = 1) => {
        let isPaginate = 0;
        paginate === 0 ? isPaginate = 0 : isPaginate = 1;

        let response = await axios.get('/api/get_temps?group=' + group + '&isPaginate=' + isPaginate + '&page=' + page);

        temps.value = response.data;
    }

    return {
        temps,
        getBdcomTemps
    }
}
