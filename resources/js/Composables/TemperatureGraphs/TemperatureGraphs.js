import {ref} from "vue";

export default function useTemperatureGraphs()
{
    const temperatureData = ref([]);
    const period = ref("daily")
    const bdcomData = ref({});

    const getTemperatureDataForGraphs = async(page = 1) => {
        let response = await axios.get("/api/get_data_for_temps_graphs?page=" + page + "&period=" + period.value );
        temperatureData.value = response.data;
        bdcomData.value = temperatureData.value.bdcoms;
    }

    return {
        temperatureData,
        period,
        bdcomData,
        getTemperatureDataForGraphs
    }
}
