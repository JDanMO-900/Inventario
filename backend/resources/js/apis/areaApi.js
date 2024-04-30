import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const areaApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/area",
});

// areaApi.interceptors.request.use(interceptorRequest);
// areaApi.interceptors.response.reject(interceptorReponse);

export default areaApi;
