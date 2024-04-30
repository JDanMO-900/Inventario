import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const levelApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/level",
});

// levelApi.interceptors.request.use(interceptorRequest);
// levelApi.interceptors.response.reject(interceptorReponse);

export default levelApi;
