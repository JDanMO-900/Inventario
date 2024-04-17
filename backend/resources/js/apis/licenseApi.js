import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const licenseApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/license",
});

// licenseApi.interceptors.request.use(interceptorRequest);
// licenseApi.interceptors.response.reject(interceptorReponse);

export default licenseApi;
