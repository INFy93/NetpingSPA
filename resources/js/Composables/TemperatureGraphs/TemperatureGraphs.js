import {ref} from "vue";

export default function useTemperatureGraphs()
{
    const temperatureData = ref([]);
    const period = ref("daily");
    const isServer = ref(0);
    const bdcomData = ref({});

    const getTemperatureDataForGraphs = async(page = 1) => {
        let response = await axios.get("/api/get_data_for_temps_graphs?page=" + page + "&period=" + period.value
        + "&is_server=" + isServer.value);
        temperatureData.value = response.data;
        bdcomData.value = temperatureData.value.bdcoms;
    }

    return {
        temperatureData,
        period,
        isServer,
        bdcomData,
        getTemperatureDataForGraphs
    }
}
