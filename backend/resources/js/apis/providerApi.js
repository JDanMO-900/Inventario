import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const providerApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/provider",
});

// providerApi.interceptors.request.use(interceptorRequest);
// providerApi.interceptors.response.reject(interceptorReponse);

export default providerApi;
