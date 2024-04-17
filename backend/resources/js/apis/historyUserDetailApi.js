import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const historyUserDetailApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/historyUserDetail",
});

// historyUserDetailApi.interceptors.request.use(interceptorRequest);
// historyUserDetailApi.interceptors.response.reject(interceptorReponse);

export default historyUserDetailApi;
