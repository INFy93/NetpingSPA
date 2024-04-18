import {ref} from "vue";

export default function useTemperatureGraphs()
{
    const temperatureData = ref([])

    const getTemperatureDataForGraphs = async() => {
        let response = await axios.get("/api/get_data_for_temps_graphs");

        temperatureData.value = response.data;
    }
    return {
        temperatureData,
        getTemperatureDataForGraphs
    }
}
