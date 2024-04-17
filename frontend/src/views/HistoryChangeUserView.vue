<template>

  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>
        <h2>{{ title }}</h2>
        <!-- <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />
        </div> -->
        <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>
      <v-data-table-server :headers="headers" :items-length="total" :items="records" :loading="loading" item-title="id"
        item-value="id" @update:options="getDataFromApi">
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" />
          
          <v-icon size="20" class="mr-2" @click="infoItem(item.raw)" icon="mdi-information" />

        </template>
        <template v-slot:no-data>
          <v-icon @click="initialize" icon="mdi-refresh" />
        </template>
      </v-data-table-server>
    </v-card>

    <v-dialog v-model="dialog" max-width="800px" >
      <v-card>
        <v-card-title>
          <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
            {{ formTitle }}
          </h2>
        </v-card-title>

        <v-card-text>
          <v-container>
            <!-- Form -->
            <v-row class="pt-3">

              <!-- location -->
              <!-- <v-col cols="12" sm="12" md="4">
                <base-select-search label="Location" v-model.trim="v$.editedItem.location.$model" :items="historychange"
                  item="location" :rules="v$.editedItem.location" />
              </v-col> -->
              <!-- location -->





              <!-- technician -->
              <v-col cols="12" sm="12" md="4">
                <base-input label="Technician" v-model="v$.editedItem.technician.$model"
                  :rules="v$.editedItem.technician" />
              </v-col>
              <!-- technician -->


            </v-row>
            <!-- Form -->
            <v-row>
              <v-col align="center">
                <base-button type="primary" title="Guardar" @click="save" />
                <base-button class="ms-1" type="secondary" title="Cancelar" @click="close" />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>

    
  </div>


  <!-- Probando modal de visualizar -->
  <v-row justify="center">
    <v-dialog v-model="dialogInfo" persistent width="1024">

      <v-card>
        <v-card-title>
          <span class="text-h5"> <b>Datos equipo</b></span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row  align="center">

              <v-col cols="4">
                <p><b>Equipo asignado a:</b> {{ this.userHistoryData.name }}</p>
              </v-col>
              <v-col cols="4">
                <p><b>Dependencia:</b> {{ this.userHistoryData.dependency }}</p>
              </v-col>
              <v-col cols="4">
                <p><b>Ubicación:</b> {{ this.userHistoryData.location }}</p>
              </v-col>


            </v-row>

            <v-row>

              <v-col cols="4">
                <p><b>Tipo de equipo:</b> {{ this.userHistoryData.type }}</p>
              </v-col>
              <v-col cols="4">
                <p><b>Marca:</b> {{ this.userHistoryData.brand }}</p>
              </v-col>
              <v-col cols="4">
                <p><b>Modelo:</b> {{ this.userHistoryData.model }}</p>
              </v-col>


            </v-row>

            <v-row>

              <v-col cols="12">
                <p><b>Licencias:</b> </p>

              </v-col>

            </v-row>
            <ol>
              <v-row  align="center">

                <v-col cols="3" v-for="(license, index) in this.userHistoryData.licenses" :key="index">
                  <li> {{ license }}</li>

                </v-col>




              </v-row>
            </ol>


          </v-container>

        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="indigo-darken-4" variant="text" @click="dialogInfo = false">
            Cerrar
          </v-btn>

        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
  <!-- Probando modal de visualizar -->



</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { messages } from "@/utils/validators/i18n-validators";
import { minLength, required, email, numeric, maxLength } from "@/lang/i18n";


import backendApi from "@/services/backendApi";

// import historyChangeApi from "@/services/historyChangeApi";




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
      dialogInfo: false,
      // dialogDelete: false,
      headers: [

        { title: "Equipo", key: "type" },
        { title: "Modelo", key: "model" },
        { title: "Proceso", key: "process" },
        { title: "Estado", key: "state" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Historial de usuario",
      total: 0,
      options: {},
      editedItem: {
        location: "", name: "", technician: "",
      },
      defaultItem: {
        location: "", name: "", technician: "",
      },
      loading: false,
      debounce: 0,
      userHistoryData: [{
        name: "", dependency: "", brand: "", model: "", process: "", state: "", description: "", location: "", type: ""
      }],


    };
  },

  watch: {
    // dialogDelete(val) {
    //   val || this.closeDelete();
    // },
  },

  // Validations
  validations() {
    return {
      editedItem: {
        location: {
          required,
          minLength: minLength(1),
        },
        name: {
          required,
          minLength: minLength(1),
        },
        technician: {
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
    infoItem(item) {
      console.log(item)
      this.editedIndex = this.records.indexOf(item);
      this.userHistoryData = Object.assign({}, item);
      this.dialogInfo = true;

    },

    async initialize() {
      // this.loading = true;
      // this.records = [];

      // let requests = [
      //   this.getDataFromApi(),
      //   historyChangeApi.get(null, {
      //     params: { itemsPerPage: -1 },
      //   }), 
      // ];

      // const responses = await Promise.all(requests).catch((error) => {
      //   alert.error("No fue posible obtener el registro.");
      // });

      // if (responses) {
      //   this.historychange = responses[1].data.historychange;


      // }

      // this.loading = false;
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
          const { data } = await backendApi.put(`/historyUserDetail/${edited.id}`, edited);

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
        const { data } = await backendApi.post('/historyUserDetail', this.editedItem);

        alert.success(data.message);
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }

      this.close();
      this.initialize();
      return;
    },

    // deleteItem(item) {
    //   this.editedIndex = this.records.indexOf(item);
    //   this.editedItem = Object.assign({}, item);

    //   this.dialogDelete = true;
    // },

    // closeDelete() {
    //   this.dialogDelete = false;
    //   this.$nextTick(() => {
    //     this.editedItem = Object.assign({}, this.defaultItem);
    //     this.editedIndex = -1;
    //   });
    // },

    // async deleteItemConfirm() {
    //   try {
    //     const { data } = await backendApi.delete(`/historyUserDetail/${this.editedItem.id}`, {
    //       params: {
    //         id: this.editedItem.id,
    //       },
    //     });

    //     alert.success(data.message);
    //   } catch (error) {
    //     this.close();
    //   }

    //   this.initialize();
      
    // },

    getDataFromApi(options) {
      this.loading = true;
      this.records = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        try {

          var user = JSON.parse(window.localStorage.getItem("user"));


          const rol = await backendApi.get(`/user/${user.name}`);


          console.log(user.name)
          const userFilter = await backendApi.get('/historyUser', {
            params: { ...options, username: user.name, search: this.search },
          });




          this.records = userFilter.data.data;
          this.total = userFilter.data.total;
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