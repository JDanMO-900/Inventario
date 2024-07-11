<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>
        <h1>Gestión de Equipos y Recursos</h1>
      </v-container>
      <v-row class="p-2 mt-2">
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/equipmentType')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de tipo de equipos" title="Tipo de equipos" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-desktop-classic" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/equipmentState')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión del estado de los equipos" title="Estado de los equipos" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-checkbox-marked-circle" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/brand')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de marcas" title="Marcas de equipos" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-label" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/technicalDescription')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de descripciones técnicas" title="Descripciones técnicas" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-memory" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/license')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de licencias" title="Licencias" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-certificate" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>
      </v-row>
    </v-card>


    <v-card class="p-3 mt-3">
      <v-container>
        <h1>Gestión Organizativa</h1>
      </v-container>
      <v-row class="p-2 mt-2">
        <v-col v-if="rolRetrieveUser == 'Jefe'" cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/users')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de dependencias" title="Usuarios" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-account-check" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/dependency')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de dependencias" title="Dependencias" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-sitemap" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/location')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de ubicaciones" title="Ubicación" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-map-marker" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/provider')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de proveedores" title="Proveedores" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-store" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>
      </v-row>
    </v-card>

    <v-card class="p-3 mt-3">
      <v-container>
        <h1>Gestión de Procesos</h1>
      </v-container>
      <v-row class="p-2 mt-2">
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/processState')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de estados de procesos" title="Estado del proceso" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-progress-clock" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-2 pb-0 pr-2">
          <v-card class="mx-auto" @click="$router.push('/typeAction')" max-width="23.5rem" rel="noopener"
            subtitle="Gestión de ubicaciones" title="Movimientos" color="grey-lighten-5">
            <template v-slot:prepend>
              <v-icon icon="mdi-shuffle" color="primary" size="small"></v-icon>
            </template>
          </v-card>
        </v-col>

      </v-row>
    </v-card>
  </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { messages } from "@/utils/validators/i18n-validators";
import { minLength, required, email, numeric, maxLength } from "@/lang/i18n";


import backendApi from "@/services/backendApi";


import BaseButton from "../components/base-components/BaseButton.vue";
import BaseInput from "../components/base-components/BaseInput.vue";
import BaseSelect from "../components/base-components/BaseSelect.vue";
import useAlert from "../composables/useAlert";

const { alert } = useAlert();
const langMessages = messages["es"].validations;

export default {
  components: { BaseButton, BaseInput, BaseSelect },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      rolRetrieveUser: "",
      headers: [

        { title: "Name", key: "name" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Listado de marcas",
      total: 0,
      options: {},
      editedItem: {
        name: "",
      },
      defaultItem: {
        name: "",
      },
      loading: false,
      debounce: 0,

    };
  },

  watch: {
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  // Validations
  validations() {
    return {
      editedItem: {
        name: {
          required,
          minLength: minLength(1),
        },
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
    search(val) {
      this.getDataFromApi();
    },
    dialog(val) {
      val || this.close();
    },
    dialogBlock(val) {
      val || this.closeBlock();
    },
  },

  created() {
    this.initialize();
  },

  beforeMount() {
    this.getDataFromApi({ page: 1, itemsPerPage: 10, sortBy: [], search: "" });
  },

  methods: {
    async initialize() {
      this.loading = true;
      this.records = [];

      let requests = [
        this.getDataFromApi(),

      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });

      if (responses) {

      }
      const retrieveUser = await backendApi.get(`/user/${JSON.parse(window.localStorage.getItem("user")).email}`)
      this.rolRetrieveUser = retrieveUser.data[0].rolName;

      this.loading = false;
    },

    editItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async save() {
      this.v$.$validate();
      if (this.v$.$invalid) {
        alert.error("Campos obligatorios");
        return;
      }

      // Updating record
      if (this.editedIndex > -1) {
        const edited = Object.assign(
          this.records[this.editedIndex],
          this.editedItem
        );

        try {
          const { data } = await backendApi.put(`/brand/${edited.id}`, edited);

          alert.success(data.message);
        } catch (error) {
          alert.error("No fue posible actualizar el registro.");
        }

        this.close();
        this.initialize();
        return;
      }

      //Creating record
      try {
        const { data } = await backendApi.post('/brand', this.editedItem);

        alert.success(data.message);
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }

      this.close();
      this.initialize();
      return;
    },

    deleteItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);

      this.dialogDelete = true;
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async deleteItemConfirm() {
      try {
        const { data } = await backendApi.delete(`/brand/${this.editedItem.id}`, {
          params: {
            id: this.editedItem.id,
          },
        });

        alert.success(data.message);
      } catch (error) {
        this.close();
      }

      this.initialize();
      this.closeDelete();
    },

    getDataFromApi(options) {
      this.loading = true;
      this.records = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        try {
          const { data } = await backendApi.get('/brand', {
            params: { ...options, search: this.search },
          });

          this.records = data.data;
          this.total = data.total;
          this.loading = false;
        } catch (error) {
          alert.error("No fue posible obtener los registros.");
        }
      }, 500);
    },

    addRecord() {
      this.dialog = true;
      this.editedIndex = -1;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.v$.$reset();
    },
  },
};
</script>