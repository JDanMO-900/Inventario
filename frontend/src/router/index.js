import { createRouter, createWebHistory } from "vue-router";
import NotFoundView from "../views/NotFoundView.vue";
import backendApi from "@/services/backendApi";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: () => import("../views/LoginView.vue"),
    },
    {
      path: "/callback",
      name: "callback",
      component: () => import("../views/CallbackView.vue"),
    },
    {
      path: "/",
      name: "home",
      component: () => import("../views/HomeView.vue"),
      meta: { requiresAuth: true }, // add meta field to specify the route requires authentication
    },
    {
      path: "/test",
      name: "test",
      component: () => import("../views/TestView.vue"),
      meta: { requiresAuth: true,  }, // add meta field to specify the route requires authentication
    },
    {
      path: "/equipmentState",
      name: "equipmentState",
      component: () => import("../views/EquipmentStateView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/brand",
      name: "brand",
      component: () => import("../views/BrandView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/equipmentType",
      name: "equipmentType",
      component: () => import("../views/EquipmentTypeView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    
    {
      path: "/dependency",
      name: "dependency",
      component: () => import("../views/DependencyView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/provider",
      name: "provider",
      component: () => import("../views/ProviderView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    ,
    {
      path: "/technicalDescription",
      name: "technicalDescription",
      component: () => import("../views/TechnicalDescriptionView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    
    {
      path: "/license",
      name: "license",
      component: () => import("../views/LicenseView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/processState",
      name: "processState",
      component: () => import("../views/ProcessStateView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/typeAction",
      name: "typeAction",
      component: () => import("../views/TypeActionView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },
    {
      path: "/movements",
      name: "movements",
      component: () => import("../views/HistoryChangeView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },


    {
      path: "/equipment",
      name: "equipment",
      component: () => import("../views/EquipmentView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },

        {
      path: "/location",
      name: "location",
      component: () => import("../views/LocationView.vue"),
      meta: { requiresAuth: true, roles: ['Jefe', 'Tecnico'] }, // add meta field to specify the route requires authentication
    },

    {
      path: "/historyUser",
      name: "historyUser",
      component: () => import("../views/HistoryUserDetailView.vue"),
      meta: { requiresAuth: true}, // add meta field to specify the route requires authentication
    },




    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: NotFoundView,
    },
  ],
});

// add a global beforeEnter guard to check if the route requires authentication and if the user has an access token
router.beforeEach(async (to, from, next) => {

  const requiresRole = to.meta.roles;


  if (to.meta.requiresAuth && !hasAccessToken()) {
    next("/login");
  } else {
    if(!requiresRole){
      next();
    }
    else{
      var user = JSON.parse(window.localStorage.getItem("user"));
      const rol = await backendApi.get(`/user/${user.name}`);
      const userRole = rol.data[0].rolName;
      if(userRole == "Jefe" ){
        next();
      }
      else{
        next("/unauthorized")
      }
      
    }
    
  }
});

// helper function to check if the user has an access token
function hasAccessToken() {
  const token = localStorage.getItem("access_token");
  return token !== null && token !== undefined;
}

export default router;
