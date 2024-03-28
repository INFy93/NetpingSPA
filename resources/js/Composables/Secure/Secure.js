import {useToast} from "vue-toastification";
import {router} from "@inertiajs/vue3";
export default function useSecure()
{
    const toast = useToast();
    const switchAlarm = async (id) => {
        let url = route('netping.index');
        let response = await axios.get("/api/alarm/set/" + id);
        await router.get(url, { preserveState: true, preserveScroll: true, only: ['netping'] })
        return toast.success(response.data)
    }
    return {
        switchAlarm
    }
}
