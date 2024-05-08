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
          <v-icon icon="fa:fas fa-search"></v-icon>
          <font-awesome-icon :icon="['fas', 'file-invoice']" />
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


              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-left"> <b>Detalles del usuario </b>
                </p>
                <hr>
              </v-col>


              <!-- name -->

              <v-col cols="6" sm="12" md="12">
                <base-select label="Usuarios" :items="this.users" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.user.$model" :rules="v$.editedItem.user">
                </base-select>
              </v-col>
              <!-- name -->




              <!-- location -->
              <v-col cols="6" sm="12" md="6">

                <base-select label="Ubicación del dispositivo asignado" :items="this.location" item-title="name"
                  v-model.trim="v$.editedItem.location_id.$model" :rules="v$.editedItem.location_id">
                </base-select>
              </v-col>
              <!-- location -->


              <!-- name -->

              <v-col cols="6" sm="12" md="6">
                <base-select label="Dependencia" :items="this.dependency" item-title="name"
                  v-model.trim="v$.editedItem.dependency_id.$model" :rules="v$.editedItem.dependency_id">
                </base-select>
              </v-col>
              <!-- name -->

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-left"> <b>Registro de movimientos y equipos </b>
                </p>
                <hr>
              </v-col>

              <!-- accion realizada -->
              <v-col cols="4" sm="12" md="12">
                <base-select label="Movimiento realizado" :items="this.typeAction" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.action.$model" :rules="v$.editedItem.action">
                </base-select>

              </v-col>
              <!-- Accion realizada -->

              <!-- Fecha de inicio de movimiento -->

              <v-col cols="12" sm="12" md="12">
                <base-input label="Fecha de inicio" v-model="v$.editedItem.start_date.$model"
                  :rules="v$.editedItem.start_date" type="date" />
              </v-col>

              <!-- Fecha de inicio de movimiento -->

              <v-col cols="12" sm="12" md="12">
                <template
                  v-if="v$.editedItem.action.$model == 'préstamo' || v$.editedItem.action.$model == 'mantenimiento'">
                  <div>
                    <!-- Fecha de finalización de movimiento -->

                    <base-input label="Fecha de finalización" v-model="v$.editedItem.end_date.$model"
                      :rules="v$.editedItem.end_date" type="date" />

                    <!-- Fecha de finalización de movimiento -->

                  </div>
                </template>
              </v-col>

              <!-- Numero de activo fijo 1 -->


              <v-col cols="4" sm="12" md="12">

                <base-select :value="v$.editedItem.number_active1.$model" label="Equipo asignado principal"
                  :items="this.equipment" item-title="number_active" item-value="number_active"
                  v-model.trim="v$.editedItem.number_active1.$model" :rules="v$.editedItem.number_active1">
                </base-select>
              </v-col>


              <!-- Numero de activo fijo 1 -->



              <v-col cols="4" sm="12" md="12">
                <p class="text-grey-darken-4 text-h10 "><b class="text-black-darken-4">Seleccione el checkbox para
                    agregar
                    un dispositivo complementario: </b></p>
                <v-checkbox-btn v-model="enabled" class="pe-2"></v-checkbox-btn>
              </v-col>


              <!-- Numero de activo fijo 2 -->

              <v-col cols="4" sm="12" md="12">

                <template v-if="enabled">
                  <div>
                    <base-select label="Equipo asignado complementario" :items="this.equipment"
                      item-title="number_active" item-value="number_active"
                      v-model.trim="v$.editedItem.number_active2.$model" :rules="v$.editedItem.number_active2"
                      :disabled="!enabled">
                    </base-select>

                  </div>
                </template>

              </v-col>
              <!-- Numero de activo fijo 2 -->




              <!-- Cantidad de salida -->
              <v-col cols="4" sm="12" md="6" v-if="v$.editedItem.action.$model == 'Mantenimiento'">
                <base-input label="Cantidad de equipos que entregan: " v-model="v$.editedItem.quantity_out.$model"
                  :rules="v$.editedItem.quantity_out" type="number" min="0" max="100" />
              </v-col>

              <!-- Cantidad de salida -->

              <!-- Cantidad de entrada -->
              <v-col cols="4" sm="12" md="6" v-if="v$.editedItem.action.$model == 'Mantenimiento'">
                <base-input label="Cantidad de equipos que se reciben: " v-model="v$.editedItem.quantity_in.$model"
                  :rules="v$.editedItem.quantity_in" type="number" min="0" max="100" />
              </v-col>



              <!-- Cantidad de entrada -->






              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-left"> <b>Gestión de acciones y estados </b>
                </p>
                <hr>
              </v-col>



              <!-- Estado de proceso -->

              <v-col cols="4" sm="12" md="12">
                <base-select label="Estado del proceso" :items="this.processState" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.process.$model" :rules="v$.editedItem.process">
                </base-select>
              </v-col>
              <!-- Estado de proceso -->


              <!-- technician -->

              <v-col cols="6" sm="12" md="12">
                <base-select label="Técnico asignado" :items="this.userTech" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.technician.$model" :rules="v$.editedItem.technician">
                </base-select>
              </v-col>



              <!-- technician -->


              <!-- Descripcion -->

              <v-col cols="12" sm="12" md="12">
                <base-text-area label="Descripción" v-model="v$.editedItem.description.$model"
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


    <v-row justify="center">
      <v-dialog v-model="dialogInfo" width="1024">

        <v-card>
          <v-card-title>
            <h2 class="mx-auto pt-3 p  text-center black-secondary">
              <b>Datos de equipo que se realizo: <i class="text-indigo-darken-4">{{
                this.editedItem.action }} </i></b>
            </h2>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="12" md="12">
                  <hr>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <p class="text-grey-darken-6 text-h5 text-left"> <b>Detalles del usuario </b></p>
                </v-col>


                <!-- Nuevo Formato -->
                <v-col cols="12" sm="12" md="12">
                  <div class="w-100">
                    <table class="table w-100">
                      <thead>
                        <tr>
                          <td><b>Equipo asignado a</b></td>
                          <td><b>Dependencia</b></td>
                          <td><b>Ubicación</b></td>

                        </tr>
                      </thead>
                      <tbody>
                        <tr style="height: 60px;">

                          <td>{{ this.editedItem.user }}</td>
                          <td>{{ this.editedItem.dependency_id }}</td>
                          <td>{{ this.editedItem.location_id }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </v-col>

                <!-- Nuevo Formato -->

                <v-col cols="12" sm="12" md="12">
                  <hr>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <p class="text-grey-darken-6 text-h5 text-left"> <b>Detalles del equipo </b></p>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <table id="equipos">

                    <tr>
                      <th colspan="4" class="text-h6 text-center"><b>Principal</b></th>
                      <th colspan="4" class="text-h6 text-center" v-if="this.editedItem.number_active2 != ''">
                        <b>Complementario</b>
                      </th>
                    </tr>

                    <tr>
                      <th>Tipo</th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Serial</th>

                      <th v-if="this.editedItem.type2 != ''">Tipo</th>
                      <th v-if="this.editedItem.brand2 != ''">Marca</th>
                      <th v-if="this.editedItem.model2 != ''">Modelo</th>
                      <th v-if="this.editedItem.number_active2 != ''">Serial</th>
                    </tr>
                    <tr>
                      <td>{{ this.editedItem.type1 }}</td>
                      <td>{{ this.editedItem.brand1 }}</td>
                      <td>{{ this.editedItem.model1 }}</td>
                      <td>{{ this.editedItem.number_active1 }}</td>



                      <td v-if="this.editedItem.type2 != ''">{{ this.editedItem.type2 }}</td>
                      <td v-if="this.editedItem.brand2 != ''">{{ this.editedItem.brand2 }}</td>
                      <td v-if="this.editedItem.model2 != ''">{{ this.editedItem.model2 }}</td>
                      <td v-if="this.editedItem.number_active2 != ''">{{
                        this.editedItem.number_active2 }}
                      </td>
                    </tr>


                  </table>

                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <hr>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <p class="text-grey-darken-4 text-h6 text-right"> <b>Etapa de {{ this.editedItem.action }}: </b>{{
                    this.editedItem.process }}</p>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <p class="text-grey-darken-4 text-h6 text-right"> <b>Técnico que Realizo el {{ this.editedItem.action
                      }}:</b> <i>{{ this.editedItem.technician }} </i>
                  </p>
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
      enabled: false,
      headers: [

        { title: "Dependencia", key: "dependency_id" },
        { title: "Ubicación", key: "location_id" },
        { title: "Usuario", key: "user" },
        { title: "Equipo principal", key: "type1" },
        { title: "Modelo", key: "model1" },
        { title: "Estado", key: "process" },
        { title: "Movimiento", key: "action" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Movimientos",
      total: 0,
      options: {},

      editedItem: {
        location_id: "", dependency_id: "", technician: "", user: "", description: "", quantity_out: 0,
        quantity_in: 1, action: "", number_active1: "", number_active2: "", process: "", start_date: "", end_date: "",
      },
      defaultItem: {
        location_id: "", dependency_id: "", technician: "", user: "", description: "", quantity_out: 0,
        quantity_in: 1, action: "", number_active1: "", number_active2: "", process: "", start_date: "", end_date: ""
      },

      loading: false,

      debounce: 0,

      typeAction: [],
      users: [],
      equipment: [],
      available_equipment: [],
      processState: [],

      location: [],

      userTech: [],

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
        location_id: {
          required,
          minLength: minLength(1),
        },
        dependency_id: {
          required,
          minLength: minLength(1),
        },
        user: {
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


        }, number_active1: {
          required,
          minLength: minLength(1),

        }, number_active2: {

          minLength: minLength(1),

        }, process: {
          required,
          minLength: minLength(1),

        },
        start_date: {
          required,
          minLength: minLength(1)
        },
        end_date: {
          minLength: minLength(1)
        }

      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo movimiento" : "Editar movimiento";
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

      var user = JSON.parse(window.localStorage.getItem("user"));



      let requests = [
        this.getDataFromApi(),
        backendApi.get('/typeAction', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/user', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/equipment-available', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/processState', {
          params: { itemsPerPage: -1 },
        }),


        backendApi.get('/location', {
          params: { itemsPerPage: -1 },
        }),

        backendApi.get('/dependency', {
          params: { itemsPerPage: -1 },
        }),

      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });


      if (responses) {




        this.typeAction = responses[1].data.data;

        this.users = responses[2].data.data;

        this.equipment = responses[3].data;
        this.processState = responses[4].data.data;


        this.location = responses[5].data.data;

        this.dependency = responses[6].data.data;



        for (let i = 0; i < this.users.length; i++) {
          if (this.users[i].role === "Tecnico")
            this.userTech.push(this.users[i].name);
        }

 

        console.log(this.equipment);







      }

      this.loading = false;
    },

    infoItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogInfo = true;

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

      if (this.editedItem.action != 'préstamo' || this.editedItem.action != 'mantenimiento') {
        this.editedItem.end_date = this.editedItem.start_date

      }


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
          const { data } = await backendApi.put(`/historyChange/${edited.id}`, edited);

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
        const { data } = await backendApi.post('/historyChange', this.editedItem);

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
        const { data } = await backendApi.delete(`/historyChange/${this.editedItem.id}`, {
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
          const { data } = await backendApi.get('/historyChange', {
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

<style scoped>
table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  margin: 1.562rem 0;
  width: 100%;
  text-align: left;
}

td,
th {
  border: 1px solid #7b84e467;
  padding: 0.75rem 0.9375rem;
}

tbody,
tr {
  border-bottom: 1px solid #fce8e8;

}

tbody tr:nth-of-type(even) {
  background-color: #f3f3f3e6;
}

tbody tr:last-of-type {
  border-bottom: 2px solid #6856dbc7;
}
</style>