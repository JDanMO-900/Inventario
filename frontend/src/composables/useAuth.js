import axios from "axios";

import { useAuthStore } from "../stores/auth";
import { storeToRefs } from "pinia";
import { useRouter } from "vue-router";

import backendApi from "@/services/backendApi.js"

const userApiEndpoint = import.meta.env.VITE_BACKEND_USER_API_ENDPOINT;
const refreshTokenEndpoint = import.meta.env.VITE_BACKEND_REFRESH_TOKEN_ENDPOINT;

const useAuth = () => {
  const authStore = useAuthStore();
  const {
    accessToken,
    refreshToken,
    user,
    isLoggedIn,
  } = storeToRefs(authStore);
  const router = useRouter();

  const getUserInfo = async () => {
    const { data } = await backendApi
      .post(userApiEndpoint, null, {
        headers: {
          Authorization: `Bearer ${accessToken.value}`,
        },
      });

    user.value = data.user;
    isLoggedIn.value = true;
  };

  const refreshAccessToken = async () => {
    const response = await axios
      .post(refreshTokenEndpoint, null, {
        headers: {
          Authorization: `Bearer ${accessToken.value}`,
        },
      })
      .catch((error) => {
        console.log(error);
        logout();
      });

    accessToken.value = response.data.access_token;
    refreshToken.value = response.data.refresh_token;

    return response;
  };

  const logout = () => {
    localStorage.clear();
    isLoggedIn.value = false;
    router.push("/login");
  };

  const login = async (credentials) => {
    const { data } = await backendApi.post('/auth/login', credentials)

    localStorage.setItem('access_token', data.data.backend.access_token)
    localStorage.setItem('refresh_token', data.data.backend.refresh_token)
    localStorage.setItem('user', JSON.stringify(data.data.user))

    user.value = data.data.user;
    isLoggedIn.value = true;
  }

  return {
    // Variables
    accessToken,
    refreshToken,
    user,
    isLoggedIn,
    // Functions
    login,
    getUserInfo,
    refreshAccessToken,
    logout,
  };
};

export default useAuth;
