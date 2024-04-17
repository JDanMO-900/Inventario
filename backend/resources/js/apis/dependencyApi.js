import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const dependencyApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/dependency",
});

// dependencyApi.interceptors.request.use(interceptorRequest);
// dependencyApi.interceptors.response.reject(interceptorReponse);

export default dependencyApi;
