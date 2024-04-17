import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const typeChangeApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/typeChange",
});

// typeChangeApi.interceptors.request.use(interceptorRequest);
// typeChangeApi.interceptors.response.reject(interceptorReponse);

export default typeChangeApi;
