export default function useVent() {
    const switchVent = async (id) => {
        let response = await axios.get("/api/vent/set/" + id);
    }
    return {
        switchVent
    }
}
