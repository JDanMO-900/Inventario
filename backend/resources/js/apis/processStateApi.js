import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const processStateApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/processState",
});

// processStateApi.interceptors.request.use(interceptorRequest);
// processStateApi.interceptors.response.reject(interceptorReponse);

export default processStateApi;
