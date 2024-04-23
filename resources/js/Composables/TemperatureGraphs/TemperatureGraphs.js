import {ref} from "vue";
import useGraphOptions from "@/Composables/TemperatureGraphs/GraphOptions.js";
export default function useTemperatureGraphs()
{
    const temperatureData = ref([]);
    const period = ref("%d.%m.%Y %H:%i")

    const getTemperatureDataForGraphs = async() => {
        let response = await axios.get("/api/get_data_for_temps_graphs?period=" + period.value);
        temperatureData.value = response.data;
    }

    return {
        temperatureData,
        period,
        getTemperatureDataForGraphs
    }
}
