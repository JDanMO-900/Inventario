import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const equipmentApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/equipment",
});

// equipmentApi.interceptors.request.use(interceptorRequest);
// equipmentApi.interceptors.response.reject(interceptorReponse);

export default equipmentApi;
