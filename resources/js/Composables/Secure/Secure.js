import {useToast} from "vue-toastification";
export default function useSecure()
{
    const toast = useToast();
    const switchAlarm = async (id) => {
        let response = await axios.get("/api/alarm/set/" + id);
        return toast.success(response.data)
    }
    return {
        switchAlarm
    }
}
