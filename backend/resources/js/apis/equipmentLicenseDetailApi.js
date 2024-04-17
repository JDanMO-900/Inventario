import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const equipmentLicenseDetailApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/equipmentLicenseDetail",
});

// equipmentLicenseDetailApi.interceptors.request.use(interceptorRequest);
// equipmentLicenseDetailApi.interceptors.response.reject(interceptorReponse);

export default equipmentLicenseDetailApi;
