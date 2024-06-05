<template>
  <div class="menu-sidebar d-flex flex-column align-center" :class="stateSideBar">
    <div class="menu-button mt-5 mb-4">
      <a href="#" @click="stateSideBar = 'inactive'">
        <v-icon icon="mdi-menu mt-1" :size="40"></v-icon>
      </a>
    </div>
    <div class="menu-options mt-3 text-center">
      <template v-if="isLoggedIn">
        <RouterLink to="/" class="d-flex flex-column align-center mb-3 p-1">
          <v-icon icon="mdi-home" size="20"></v-icon>
          <span>Inicio</span>
        </RouterLink>

        <RouterLink to="/equipment" class="d-flex flex-column align-center mb-3 p-1">
          <v-icon icon="mdi-remote-desktop" size="20"></v-icon>
          <span>Inventario</span>
        </RouterLink>

        <RouterLink to="/movements" class="d-flex flex-column align-center mb-3 p-1">
          <v-icon icon="mdi-archive-sync-outline" size="20"></v-icon>
          <span>Movimientos</span>
        </RouterLink>               

        <RouterLink to="/dashboard" class="d-flex flex-column align-center mb-3 p-1">
          <v-icon icon="mdi-shield-home" size="20"></v-icon>
          <span>Dashboard</span>
        </RouterLink>

        <RouterLink to="/ticket" class="d-flex flex-column align-center mb-3 p-1">
          <v-icon icon="mdi-ticket" size="20"></v-icon>
          <span>Ticket</span>
        </RouterLink>

        <!-- Cerrar sesion -->
        <RouterLink to="/" class="d-flex flex-column align-center mb-4 p-1" @click="logout()">
          <v-icon icon="mdi-logout" size="20"></v-icon>
          <span>Cerrar sesión</span>
        </RouterLink>
        
      </template>
      <template v-else>
        <RouterLink to="/" class="d-flex flex-column align-center mb-4 p-1">
          <v-icon icon="mdi-login" size="20"></v-icon>
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
  width: 8rem;
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

.menu-options a:hover{
  color: #E0E5F1;
  background-color: #474F7A;
  border-radius: 20px;
  // opacity: 50%;
}

.inactive {
  left: -8rem;
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