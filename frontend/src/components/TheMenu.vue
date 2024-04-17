<template>
  <div class="menu-sidebar d-flex flex-column align-center" :class="stateSideBar">
    <div class="menu-button mt-5 mb-4">
      <a href="#" @click="stateSideBar = 'inactive'">
        <v-icon icon="mdi-menu mt-1" :size="45"></v-icon>
      </a>
    </div>
    <div class="menu-options mt-3 text-center">
      <template v-if="isLoggedIn">
        <RouterLink to="/" class="d-flex flex-column align-center mb-3">
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Inicio</span>
        </RouterLink>

        <RouterLink to="/historyChange" class="d-flex flex-column align-center mb-3">
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Historial personal</span>
        </RouterLink>


        <RouterLink to="/historyChangeAdmin" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Historial completo</span>
        </RouterLink>
        

        <RouterLink to="/Equipment" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Equipos</span>
        </RouterLink>

        <RouterLink to="/dependency" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Dependencias</span>
        </RouterLink>

        <RouterLink to="/equipmentState" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Estado de equipos</span>
        </RouterLink>

        <RouterLink to="/brand" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Marcas</span>
        </RouterLink>

        <RouterLink to="/equipmentType" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Tipos de equipos</span>
        </RouterLink>

        
        <RouterLink to="/equipmentType" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Proveedores</span>
        </RouterLink>

        <RouterLink to="/license" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Licencias</span>
        </RouterLink>

        <RouterLink to="/processState" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Estado del proceso</span>
        </RouterLink>

        <RouterLink to="/typeAction" class="d-flex flex-column align-center mb-3" >
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Tipo de acción</span>
        </RouterLink>






        <!-- Cerrar sesion -->
        <RouterLink to="/" class="d-flex flex-column align-center mb-4" @click="logout()">
          <v-icon icon="mdi-logout" size="25"></v-icon>
          <span>Cerrar sesión</span>
        </RouterLink>
      </template>
      <template v-else>
        <RouterLink to="/" class="d-flex flex-column align-center mb-4">
          <v-icon icon="mdi-login" size="25"></v-icon>
          <span>Iniciar sesión</span>
        </RouterLink>
        <!-- <RouterLink to="/register" class="d-flex flex-column align-center mb-4">
          <v-icon icon="mdi-account-plus" size="25"></v-icon>
          <span>Registrarse</span>
        </RouterLink> -->
      </template>
    </div>
  </div>
</template>

<script setup>

import { RouterLink } from "vue-router";
import useMenu from "@/composables/useMenu";
import useAuth from "../composables/useAuth";


const { stateSideBar } = useMenu();
const { isLoggedIn, logout } = useAuth();



</script>

<style lang="scss">
@import "@/assets/styles/variables.scss";

.menu-sidebar {
  width: 6rem;
  height: 100vh;
  position: fixed;
  top: 0;
  z-index: 1;
  background: $menu-color;
  font-size: 16px;
}

.menu-sidebar a {
  color: white;
}

.inactive {
  left: -6rem;
  animation: linear;
  animation-name: hideMenu;
  animation-duration: 0.4s;
}

@keyframes hideMenu {
  0% {
    left: 0;
    transform: translateX(0);
  }

  100% {
    left: -6rem;
    transform: translateX(-6rem);
  }
}

.active {
  left: 0;
  animation: linear;
  animation-name: showMenu;
  animation-duration: 0.4s;
}

@keyframes showMenu {
  0% {
    left: -6rem;
    transform: translateX(-6rem);
  }

  100% {
    left: 0;
    transform: translateX(0);
  }
}
</style>