import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const equipmentStateApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/equipmentState",
});

// equipmentStateApi.interceptors.request.use(interceptorRequest);
// equipmentStateApi.interceptors.response.reject(interceptorReponse);

export default equipmentStateApi;
