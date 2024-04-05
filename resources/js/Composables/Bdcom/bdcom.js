import {ref} from "vue";

export default function useBdcoms()
{
    const bdcoms = ref([]);
    const bdcoms_temp = ref([])

    const getBdcoms = async () => {
        let response = await axios.get("/api/bdcoms");

        bdcoms.value = response.data.data;
    }


    return {
        bdcoms,
        getBdcoms
    }
}
