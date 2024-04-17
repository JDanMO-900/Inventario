<template>
  <div class="d-flex justify-content-center">
    <v-card class="text-center p-5 pt-4 pb-4 shadow" @keyup.enter="loginUser()">
      <v-img class="mx-auto" src="/images/cultura_logo.jpg" style="max-width: 300px" />
      <h2 class="pb-1" style="color: #5775b8">
        Ingresa tus datos para iniciar
      </h2>

      <BaseInput v-model="v$.email.$model" :rules="v$.email" label="Primer nombre" />
      <base-input label="Contraseña" v-model="v$.password.$model" :rules="v$.password" type="password" />
      <!-- <a href="#" class="text-left">Mostrar contraseña</a> -->
      <br>
      <base-button title="Iniciar sesión" type="primary" class="mt-5 mb-3 p-5 py-0" @click="loginUser()"
        :loading="isLoading" />
    </v-card>
  </div>
</template>

<script setup>
import { ref } from "vue";
import useVuelidate from "@vuelidate/core";

import { required, minLength, maxLength, email } from "@/lang/i18n";

import BaseButton from "../components/base-components/BaseButton.vue";
import BaseInput from "../components/base-components/BaseInput.vue";
import { useRouter } from "vue-router";
import useAuth from "@/composables/useAuth.js"
import useAlert from "@/composables/useAlert.js"

const router = useRouter();
const { user, isLoggedIn, login } = useAuth();
const { alert } = useAlert();
const isLoading = ref(false);

const state = ref({
  email: "lalopez@cultura.gob.sv",
  password: "Leonel23",
});

const rules = {
  email: {
    required,
    minLenght: minLength(1),
    email
  },
  password: {
    required,
    minLenght: minLength(1),
  }
}

const v$ = useVuelidate(rules, state);

const loginUser = async () => {
  try {
    isLoading.value = true;
    if (!await v$.value.$validate()) {
      alert.error("Campos requeridos")
      return;
    }

    await login(state.value).then(() => {
      router.push('/')
    })
    // 
    isLoading.value = false;
  } catch (error) {
    isLoading.value = false;
    alert.error(error.response.data.message)
    console.log(error)
  }
}
</script>

<style scoped>
h2 {
  font-size: 26px;
  font-weight: normal;
}
</style>
