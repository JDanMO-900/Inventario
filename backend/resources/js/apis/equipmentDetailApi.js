import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const equipmentDetailApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/equipmentDetail",
});

// equipmentDetailApi.interceptors.request.use(interceptorRequest);
// equipmentDetailApi.interceptors.response.reject(interceptorReponse);

export default equipmentDetailApi;
