import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const equipmentTypeApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/equipmentType",
});

// equipmentTypeApi.interceptors.request.use(interceptorRequest);
// equipmentTypeApi.interceptors.response.reject(interceptorReponse);

export default equipmentTypeApi;
