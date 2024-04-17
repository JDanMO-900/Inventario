import axios from "axios";

const backendApi = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api",
    headers: {
        Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
});

// Response interceptor for API calls
backendApi.interceptors.response.use(
    (response) => {
        return response;
    },
    async function (error) {
        // Getting the config before the error
        const originalRequest = error.config;

        // If it's error 401, the token is invalid
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;

            // Refresh the access token
            const response = await axios.post(import.meta.env.VITE_BACKEND_URL + import.meta.env.VITE_BACKEND_REFRESH_TOKEN_ENDPOINT, null, {
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token"),
                },
            }).catch((error) => {
                localStorage.clear();
                window.location.href = "/login";
                // throw new Error(error)
            });

            // Renews the tokens
            localStorage.setItem("access_token", response.data.backend.access_token);
            localStorage.setItem("refresh_token", response.data.backend.refresh_token);

            originalRequest.headers["Authorization"] = "Bearer " + response.data.access_token;

            // Retry the request
            return backendApi(originalRequest);
        }

        // console.log("Hola")
        return Promise.reject(error);
    }
);

export default backendApi;