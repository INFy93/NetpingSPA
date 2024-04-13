import {ref} from "vue";

export default function useCamera()
{
    const cameraData = ref();

    const getCameraImage = async (id) => {
        cameraData.value = "";
        let response = await axios.get("/api/netping_camera/" + id, {
            responseType: 'blob'
        });
        const urlCreator = window.URL || window.webkitURL;
        cameraData.value = urlCreator.createObjectURL(response.data);
    }

    return {
        cameraData,
        getCameraImage
    }
}
