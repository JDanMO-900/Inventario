<script setup>
import { onMounted } from "vue";

import TheFooter from "./components/TheFooter.vue";
import TheHeader from "./components/TheHeader.vue";
import useAuth from "./composables/useAuth";
import TheMenu from "./components/TheMenu.vue";
import Alert from "@/components/Alert.vue";
import { useRouter } from "vue-router";

const router = useRouter();
const { accessToken, refreshToken, user, isLoggedIn, getUserInfo } =
  useAuth();

onMounted(async () => {
  accessToken.value = localStorage.getItem("access_token");
  refreshToken.value = localStorage.getItem("refresh_token");
  user.value = JSON.parse(localStorage.getItem("user"));

  isLoggedIn.value = user.value ? true : false;

  if (isLoggedIn.value) {
    await getUserInfo();
  }
});
</script>

<template>
  <the-header />
  <the-menu />

  <main>
    <v-container>
      <Alert class="mb-3" />
      <RouterView />
    </v-container>
  </main>

  <the-footer />
</template>