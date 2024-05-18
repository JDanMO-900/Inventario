import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const historyTechApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/historyTech",
});

// historyTechApi.interceptors.request.use(interceptorRequest);
// historyTechApi.interceptors.response.reject(interceptorReponse);

export default historyTechApi;
