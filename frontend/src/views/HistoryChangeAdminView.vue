<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>
        <h2>{{ title }}</h2>
        <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />
        </div>
        <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>
      <v-data-table-server :headers="headers" :items-length="total" :items="records" :loading="loading" item-title="id"
        item-value="id" @update:options="getDataFromApi">
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" />
          <v-icon size="20" class="mr-2" @click="deleteItem(item.raw)" icon="mdi-delete" />
          <v-icon size="20" class="mr-2" @click="infoItem(item.raw)" icon="mdi-information" />
        </template>
        <template v-slot:no-data>
          <v-icon @click="initialize" icon="mdi-refresh" />
        </template>
      </v-data-table-server>
    </v-card>

    <v-dialog v-model="dialog" max-width="800px">
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
              <v-col cols="6" sm="12" md="6">
                <base-input label="Ubicación del dispositivo asignado" v-model.trim="v$.editedItem.location.$model"
                  :items="historychange" :rules="v$.editedItem.location" />
              </v-col>
              <!-- location -->


              <!-- name -->

              <v-col cols="6" sm="12" md="6">
                <base-select placeholder="Usuarios" :items="this.users" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.name.$model" :rules="v$.editedItem.name">
                </base-select>
              </v-col>
              <!-- name -->

            </v-row>

            <v-row class="pt-3">

              <!-- technician -->
              <!-- <v-col cols="4" sm="12" md="6">
                <base-input label="Technician" v-model="v$.editedItem.technician.$model"
                  :rules="v$.editedItem.technician" />
              </v-col> -->

              <v-col cols="4" sm="12" md="6">
                <base-select placeholder="Ténico" :items="this.technicians" item-title="tipoUsuario" :value="value"
                  v-model.trim="v$.editedItem.technician.$model" :rules="v$.editedItem.technician">
                </base-select>
              </v-col>
              <!-- technician -->

              <!-- accion realizada -->
              <v-col cols="4" sm="12" md="6">
                <base-select placeholder="Procedimiento" :items="this.typeAction" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.action.$model" :rules="v$.editedItem.action">
                </base-select>



              </v-col>
              <!-- Accion realizada -->






              <!-- Cantidad de salida -->
              <v-col cols="4" sm="12" md="6">
                <base-input label="Cantidad de equipos que entregan: " v-model="v$.editedItem.quantity_out.$model"
                  :rules="v$.editedItem.quantity_out" type="number" min="0" max="100" />
              </v-col>


              <!-- Cantidad de salida -->

              <!-- Cantidad de entrada -->
              <v-col cols="4" sm="12" md="6">
                <base-input label="Cantidad de equipos que se reciben: " v-model="v$.editedItem.quantity_in.$model"
                  :rules="v$.editedItem.quantity_in" type="number" min="0" max="100" />
              </v-col>


              <!-- Cantidad de entrada -->

              <!-- Estado de proceso -->

              <v-col cols="4" sm="12" md="12">
                <base-select placeholder="Estado del proceso" :items="this.processState" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.process.$model" :rules="v$.editedItem.process">
                </base-select>
              </v-col>
              <!-- Estado de proceso -->







            </v-row>

            <v-row class="pt-3">


              <!-- Numero de activo fijo 1 -->


              <v-col cols="4" sm="12" md="6">
                <base-select placeholder="Equipo asignado principal" :items="this.equipment"
                  item-title="number_internal_active" :value="number_internal_active"
                  v-model.trim="v$.editedItem.number_internal_active1.$model"
                  :rules="v$.editedItem.number_internal_active1">
                </base-select>
              </v-col>
              <!-- Numero de activo fijo 1 -->


              <!-- Numero de activo fijo 2 -->

              <v-col cols="4" sm="12" md="6">
                <base-select placeholder="Equipo asignado complementario" :items="this.equipment"
                  item-title="number_internal_active" :value="number_internal_active"
                  v-model.trim="v$.editedItem.number_internal_active2.$model"
                  :rules="v$.editedItem.number_internal_active2">
                </base-select>
              </v-col>
              <!-- Numero de activo fijo 2 -->

              
              <!-- Descripcion -->

              <v-col cols="12" sm="12" md="12">
                <base-text-area placeholder="Descripción" v-model="v$.editedItem.description.$model"
                  :rules="v$.editedItem.description" />


              </v-col>



              <!-- Descripcion -->

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

    <v-dialog v-model="dialogDelete" max-width="400px">
      <v-card class="h-100">
        <v-container>
          <h1 class="black-secondary text-center mt-3 mb-3">
            Eliminar registro
          </h1>
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="deleteItemConfirm" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeDelete" />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>



  <!-- Probando modal de visualizar -->
  <v-row justify="center">
    <v-dialog v-model="dialogInfo" width="1024">

      <v-card>
        <v-card-title>
          <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
            Datos del equipo
          </h2>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row class="pt-3">
              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Datos de asignación </b></p>
              </v-col>


              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 "><b class="text-indigo-darken-4">Equipo asignado a: </b>{{
                  this.editedItem.name }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Dependencia: </b>{{
                  this.editedItem.dependency }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Ubicación:</b> {{
                  this.editedItem.location }}</p>

              </v-col>
              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Datos de registro de equipo </b></p>
              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Activo fijo ministerio de cultura:
                  </b>{{ this.editedItem.activo_fijo }}</p>

              </v-col>


              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">
                    Registro interno:</b>{{ this.editedItem.activo_fijo_interno }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>



              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Especificaciones del equipo </b></p>
              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Tipo de equipo: </b>{{
                  this.editedItem.type }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Marca: </b>{{
                  this.editedItem.brand
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Modelo: </b>{{
                  this.editedItem.model
                }}</p>

              </v-col>



              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de serie:
                  </b>{{ this.editedItem.serial
                  }}</p>

              </v-col>

              <v-col cols="3">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Licencias:
                  </b></p>
                <p class="text-grey-darken-4 text-h6" v-for="(license, index) in this.editedItem.licenses" :key="index">
                  {{
                    license }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Datos del estado y funcionamiento del equipo </b>
                </p>
              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Estado: </b>{{
                  this.editedItem.state }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Descripcion:: </b>{{
                  this.editedItem.description }}</p>

              </v-col>




              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Proceso:</b>{{
                  this.editedItem.process }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Acción tomada: </b>{{
                  this.editedItem.action }}</p>

              </v-col>

            </v-row>




          </v-container>

        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <base-button class="ms-1" type="secondary" title="Cerrar" @click="dialogInfo = false" />


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




import BaseButton from "../components/base-components/BaseButton.vue";
import BaseInput from "../components/base-components/BaseInput.vue";
import BaseSelect from "../components/base-components/BaseSelect.vue";
import BaseTextArea from "../components/base-components/BaseTextArea.vue";
import useAlert from "../composables/useAlert";

const { alert } = useAlert();
const langMessages = messages["es"].validations;

export default {
  components: { BaseButton, BaseInput, BaseSelect, BaseTextArea },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      dialogInfo: false,
      headers: [

        { title: "Equipo", key: "type" },
        { title: "Modelo", key: "model" },
        { title: "Proceso", key: "process" },
        { title: "Estado", key: "state" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Historial usuario",
      total: 0,
      options: {},
      editedItem: {
        location: "", technician: "", name: "", description: "", quantity_out: 0,
        quantity_in: 0, action: "", number_internal_active1: "", number_internal_active2: "", process: "",
      },
      defaultItem: {
        location: "", technician: "", name: "", description: "", quantity_out: 0,
        quantity_in: 0, action: "", number_internal_active1: "", number_internal_active2: "", process: ""
      },
      loading: false,
      debounce: 0,
      technicians: [
        { "tipoUsuario":"tecnico", 'value': 1 },
        { "tipoUsuario":"usuario", 'value': 0 }
      ],

      typeAction: [],
      users: [],
      equipment: [],
      processState: [],

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
        description: {
          required,
          minLength: minLength(1),
        },
        quantity_out: {
          required,
          minLength: minLength(1),

          numeric

        },
        quantity_in: {
          required,
          minLength: minLength(1),

          numeric

        },
        action: {
          required,
          minLength: minLength(1),


        }, number_internal_active1: {
          required,
          minLength: minLength(1),

        }, number_internal_active2: {
          required,
          minLength: minLength(1),

        }, process: {
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
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogInfo = true;

    },

    async initialize() {
      this.loading = true;
      this.records = [];

      let requests = [
        this.getDataFromApi(),
        backendApi.get('/typeAction', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/user', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/equipment', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/processState', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/historyChange', {
          params: { itemsPerPage: -1 },
        }),
      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });


      if (responses) {

        this.typeAction = responses[1].data.data;
        this.users = responses[2].data.data;
        this.equipment = responses[3].data.data;
        this.processState = responses[4].data.data;
        this.historyChange = responses[5].data.data

      }

      this.loading = false;
    },

    editItem(item) {
      console.log(item)
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
        const { data } = await backendApi.delete(`/historyUserDetail/${this.editedItem.id}`, {
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
          const { data } = await backendApi.get('/historyUserDetail', {
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