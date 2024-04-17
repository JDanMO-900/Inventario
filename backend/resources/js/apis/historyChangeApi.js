import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const historyChangeApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/historyChange",
});

// historyChangeApi.interceptors.request.use(interceptorRequest);
// historyChangeApi.interceptors.response.reject(interceptorReponse);

export default historyChangeApi;
