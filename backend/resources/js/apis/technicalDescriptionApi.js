import axios from "axios";
// import { interceptorRequest, interceptorReponse } from "./interceptor";

const technicalDescriptionApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api/technicalDescription",
});

// technicalDescriptionApi.interceptors.request.use(interceptorRequest);
// technicalDescriptionApi.interceptors.response.reject(interceptorReponse);

export default technicalDescriptionApi;
