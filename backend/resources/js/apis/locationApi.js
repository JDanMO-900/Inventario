import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const locationApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/location",
});

// locationApi.interceptors.request.use(interceptorRequest);
// locationApi.interceptors.response.reject(interceptorReponse);

export default locationApi;
