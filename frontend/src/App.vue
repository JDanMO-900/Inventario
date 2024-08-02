<script setup>
import { onMounted, ref } from "vue";

import TheFooter from "./components/TheFooter.vue";
import TheHeader from "./components/TheHeader.vue";
import useAuth from "./composables/useAuth";
import TheMenu from "./components/TheMenu.vue";
import Alert from "@/components/Alert.vue";
import { useRouter } from "vue-router";
import backendApi from "@/services/backendApi";

const router = useRouter();

const { accessToken, refreshToken, user, isLoggedIn, getUserInfo } =
  useAuth();

const userRol = ref("");

onMounted(async () => {
  accessToken.value = localStorage.getItem("access_token");
  refreshToken.value = localStorage.getItem("refresh_token");
  user.value = JSON.parse(localStorage.getItem("user"));

  isLoggedIn.value = user.value ? true : false;

  if (isLoggedIn.value) {
    await getUserInfo();
  }

  if (user.value) {

    userRol.value =user.value['rol'];

  }

});




</script>

<template>
  <the-header />
  <the-menu :userRol="userRol" />

  <main>
    <v-container fluid>
      <v-row justify="center">
        <v-col cols="10">
          <Alert class="mb-3" />
          <RouterView />
        </v-col>
      </v-row>
    </v-container>
  </main>

  <the-footer />
</template>
