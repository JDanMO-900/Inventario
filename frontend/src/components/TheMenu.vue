<template>
  <div class="menu-sidebar" :class="stateSideBar">
    <div class="menu-button mt-5 mb-4 d-flex flex-column align-center">
      <a href="#" @click="stateSideBar = 'inactive'">
        <v-icon icon="mdi-menu mt-1" :size="40"></v-icon>
      </a>
    </div>
    <div class="menu-options mt-3 text-center">
      <template v-if="isLoggedIn">
        <v-template v-if="rol!==1">        
          <RouterLink to="/equipment" class="d-flex flex-column align-center pt-2 pb-2 p-1">
            <v-icon icon="mdi-remote-desktop" size="20"></v-icon>
            <span>Inventario</span>
          </RouterLink>

          <RouterLink to="/movements" class="d-flex flex-column align-center pt-2 pb-2 p-1">
            <v-icon icon="mdi-archive-sync-outline" size="20"></v-icon>
            <span>Movimientos</span>
          </RouterLink>               

          <RouterLink to="/dashboard" class="d-flex flex-column align-center pt-2 pb-2 p-1">
            <v-icon icon="mdi-shield-home" size="20"></v-icon>
            <span>Catálogos</span>
          </RouterLink>        
          
          <v-menu location="end" open-on-hover>
            <template v-slot:activator="{ props }">
              <div class="d-flex flex-column align-center pt-2 pb-2 p-1" v-bind="props">
                <v-icon icon="mdi-file" size="20"></v-icon>
                <span>Reportes</span>
              </div>
            </template>

            <v-list density="compact" variant="plain" class="my-list pt-2 pb-2">
              <RouterLink to="/availableEquipment">
                <v-list-item> 
                  Equipos en bodega
                </v-list-item>
              </RouterLink>            
              <RouterLink to="/equipmentTypeReport">
                <v-list-item> 
                  Listado de equipos
                </v-list-item>
              </RouterLink>
              <RouterLink to="/filterMovement">
                  <v-list-item>
                  Movimientos de inventario
                </v-list-item>
              </RouterLink>
              <!-- <RouterLink v-if="userRol != 'Usuario'" to="/locationReport">
                <v-list-item> 
                  Equipos por ubicación
                </v-list-item>
              </RouterLink> -->
            </v-list>
          </v-menu>
        </v-template>

        <RouterLink to="/ticket" class="d-flex flex-column align-center pt-2 pb-2  p-1">
          <v-icon icon="mdi-ticket" size="20"></v-icon>
          <span>Ticket</span>
        </RouterLink>  

        <!-- Cerrar sesion -->
        <RouterLink to="/" class="d-flex flex-column align-center pt-2 pb-2 p-1" @click="logout()">
          <v-icon icon="mdi-logout" size="20"></v-icon>
          <span>Cerrar sesión</span>
        </RouterLink>
        
      </template>
      <template v-else>
        <RouterLink to="/" class="d-flex flex-column align-center pt-2 pb-2 p-1">
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
  const { isLoggedIn, rol, logout } = useAuth();

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

.menu-sidebar a, .menu-sidebar div {
  color: white;
}

.menu-options a:hover, .menu-options div:hover{
  color: #E0E5F1;
  background-color: #474F7A;
  //border-radius: 10px;
}

.my-list {
  background-color: #3C486B !important;
}

.my-list a{
  color: #F0F0F0 !important;
}

.my-list .v-list-item:hover{
  color: white !important;  
  background-color: #82B1FF;
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