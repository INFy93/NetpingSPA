export default function useSecure()
{
    const switchAlarm = async (id) => {
        let response = await axios.get("/api/alarm/set/" + id);
    }
    return {
        switchAlarm
    }
}
