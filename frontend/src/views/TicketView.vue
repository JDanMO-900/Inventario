<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>

        <h2>{{ title }} <base-button type="primary" title="Agregar" @click="addRecord()" /></h2>
        <!-- <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />

        </div> -->

        <v-row class="mt-2 mb-2">
          <v-col cols="12" lg="6" md="6" sm="12">
            <BaseSelect label='Estados' :items="this.processStatesFilter" item-title='name' item-value="id"
              v-model.trim="v$.filterItem.processStateFilter.$model" :rules="v$.filterItem.processStateFilter"
              clearable>
            </BaseSelect>
          </v-col>

          <v-col cols="12" lg="6" md="6" sm="12">
            <BaseSelect label='Tipo de movimiento' :items="this.typeMovements" item-title='name' item-value="id"
              v-model.trim="v$.filterItem.typeMovement.$model" :rules="v$.filterItem.typeMovement" clearable>
            </BaseSelect>
          </v-col>

          <!-- fecha del movimiento -->
          <v-col cols="12" sm="6" md="6">
            <base-input label="Rango inicial" v-model="v$.filterItem.start_date.$model"
              :rules="v$.filterItem.start_date" type="datetime-local" clearable />
          </v-col>
          <!-- fecha del movimiento -->

          <!-- fecha del movimiento -->
          <v-col cols="12" sm="6" md="6">
            <base-input label="Rango final" v-model="v$.filterItem.end_date.$model" :rules="v$.filterItem.end_date"
              type="datetime-local" clearable />
          </v-col>
          <!-- fecha del movimiento -->

          <v-col cols="12" lg="12" md="12" sm="12" class="d-flex flex-column align-center justify-center">
            <base-button type="primary" title="Filtrar" @click="filterByMovement" :loading="isLoading" />
          </v-col>
        </v-row>


        <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>

      <v-progress-linear v-if="loading" indeterminate color="indigo-accent-3"></v-progress-linear>
      <v-data-table :headers="headers" :items="records" item-key="name" class="elevation-1" :search="search">
        <template v-slot:[`item.actions`]="{ item }">

          <v-icon
            v-if="item.raw.process_id == 1 && item.raw.process_id != 4 && item.raw.process_id != 3 && item.raw.internal != 1"
            size="20" class="mr-2" @click="movementCancelStatusItem(item.raw)" icon="mdi-cancel"
            title="Cancelar proceso" />
          <v-icon size="20" class="mr-2" @click="infoItem(item.raw)" icon="mdi-information" title="Información" />

          <v-icon icon="fa:fas fa-search"></v-icon>
          <!-- <font-awesome-icon :icon="['fas', 'file-invoice']" /> -->
        </template>
        <template v-slot:no-data>
          <v-icon @click="initialize" icon="mdi-refresh" />
        </template>

      </v-data-table>

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
                <v-chip color="primary" variant="flat" label>
                  <v-icon icon="mdi-numeric-1-circle" start></v-icon>
                  Usuario: {{ this.editedItem.users }}
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>

              <!-- location -->
              <v-col cols="6" sm="12" md="6">
                <base-select label="Lugar donde es requerida la asistencia" :items="this.location" item-title="name"
                  v-model.trim="v$.editedItem.location_id.$model" :rules="v$.editedItem.location_id" clearable>
                </base-select>
              </v-col>
              <!-- location -->

              <!-- name -->


              <v-col cols="6" sm="12" md="6">
                <base-select label="Dependencia" :items="this.dependency" item-title="name"
                  v-model="v$.editedItem.dependency_id.$model" :rules="v$.editedItem.dependency_id" disabled>
                </base-select>

              </v-col>




              <!-- name -->

              <v-col cols="12" sm="12" md="12">
                <v-chip color="primary" variant="flat" label>
                  <v-icon icon="mdi-numeric-2-circle" start></v-icon>
                  Movimiento a realizar
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>


              <!-- accion realizada -->
              <v-col cols="4" sm="12" md="12">
                <base-select label="Movimiento" :items="filterTypeAction" item-title="name"
                  v-model.trim="v$.editedItem.type_action_id.$model" :rules="v$.editedItem.type_action_id" clearable>
                </base-select>
              </v-col>

              <!-- Accion realizada -->


              <!-- Fecha de inicio de movimiento -->
              <v-col cols="12" sm="12" md="12">
                <base-input label="Fecha de inicio" v-model="v$.editedItem.start_date.$model"
                  :rules="v$.editedItem.start_date" type="datetime-local" clearable />
              </v-col>

              <!-- Fecha de inicio de movimiento -->

              <template v-if="currentAction == 4 && v$.editedItem.type_action_id.$model != ''">

                <!-- Numero de activo fijo 1 -->
                <v-col cols="4" sm="12" md="12">
                  <base-select label="Equipos" :items="this.equipment" item-title="format" item-value="serial_number"
                    v-model.trim="v$.editedItem.equipment.$model" :rules="v$.editedItem.equipment" clearable>
                  </base-select>
                </v-col>


                <v-col cols="12" sm="12" md="12">
                  <base-button color="blue-accent-1" type="primary" density="comfortable" title="Agregar" @click="addEquipment
                    " block prepend-icon="mdi-plus-thick" />
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <div class="w-100">
                    <v-table density="compact">
                      <thead>
                        <tr>
                          <td><b>Equipo</b></td>
                          <td><b>Acción</b></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="equipo in this.editedItem.equipment_id">
                          <td>{{ equipo }}</td>
                          <td>
                            <v-icon size="20" class="mr-2" @click="deleteEquipment(equipo)" icon="mdi-delete"
                              color="red-darken-4" />
                          </td>
                        </tr>
                        <tr v-if="this.editedItem.equipment_id == 0">
                          <td colspan="4">
                            <p class="text-center py-3">Sin datos que mostrar</p>
                          </td>
                        </tr>
                      </tbody>
                    </v-table>
                  </div>
                </v-col>
              </template>

              <template v-if="currentAction != 4 && v$.editedItem.type_action_id.$model != ''">
                <v-col cols="4" sm="12" md="12">
                  <base-select label="Equipos asignados a tu persona" :items="filterUserEquipment" item-title="format"
                    item-value="serial_number" v-model.trim="v$.editedItem.equipment.$model"
                    :rules="v$.editedItem.equipment" clearable>
                  </base-select>
                </v-col>


                <v-col cols="12" sm="12" md="12">
                  <base-button color="blue-accent-1" type="primary" density="comfortable" title="Agregar" @click="addEquipment
                    " block prepend-icon="mdi-plus-thick" />
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <div class="w-100">
                    <v-table density="compact">
                      <thead>
                        <tr>
                          <td><b>Equipo</b></td>
                          <td><b>Acción</b></td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="equipo in this.editedItem.equipment_id">
                          <td>{{ equipo }}</td>
                          <td>
                            <v-icon size="20" class="mr-2" @click="deleteEquipment(equipo)" icon="mdi-delete"
                              color="red-darken-4" />
                          </td>
                        </tr>
                        <tr v-if="this.editedItem.equipment_id == 0">
                          <td colspan="4">
                            <p class="text-center py-3">Sin datos que mostrar</p>
                          </td>
                        </tr>
                      </tbody>
                    </v-table>
                  </div>
                </v-col>
                <!-- Mis equipos -->

              </template>

              <!-- Numero de activo fijo 1 -->

              <!-- Cantidad de entrada -->
              <v-col cols="12" sm="12" md="12">
                <v-chip color="primary" variant="flat" label>
                  <v-icon icon="mdi-numeric-3-circle" start></v-icon>
                  Comentarios
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>



              <!-- Descripcion -->
              <v-col cols="12" sm="12" md="12">
                <base-text-area label="Comentarios(Opcional)" v-model="v$.editedItem.description.$model"
                  :rules="v$.editedItem.description" clearable />
              </v-col>
              <!-- Descripcion -->

            </v-row>


            <!-- Form -->
            <v-row>
              <v-col align="center">
                <base-button type="primary" title="Guardar" @click="save" :loading="isLoading" />
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
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeDelete" :loading="isLoading" />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>

  </div>

  <!-- Dialogo para cancelar solicitud -->
  <v-dialog v-model="cancelMovement" max-width="45rem">
    <v-card>
      <v-card-title>
        <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
          Cancelar solicitud de {{ this.editedItem.type_action.toLowerCase() }}

        </h2>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="changeToCancelStatus" :loading="isLoading" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeCancelMovement" />
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>

  <v-dialog v-model="dialogInfo" width="1024">
    <v-card>
      <v-card-title>
        <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">Información referente al ticket</h2>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>

            <v-col cols="12" sm="12" md="12">
              <v-chip color="primary" variant="flat" label>
                <v-icon icon="mdi-numeric-3-circle" start></v-icon>Detalles del movimiento
              </v-chip>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-table density="compact">
                <tbody class="tbl-info">

                  <tr>
                    <td>Tipo de movimiento</td>
                    <td>{{ editedItem.type_action }}</td>
                  </tr>
                  <tr>
                    <td>Ubicación</td>
                    <td>{{ editedItem.location }}</td>
                  </tr>
                  <tr>
                    <td>Fecha que se inicio del movimiento</td>
                    <td>{{ editedItem.start_date }}</td>

                  </tr>

                  <tr>
                    <td>Descripción</td>
                    <td v-if="this.editedItem.description != null"></td>
                    <td v-else>No hay datos disponibles</td>
                  </tr>
                </tbody>
              </v-table>
            </v-col>


            <v-col cols="12" sm="12" md="12">
              <v-chip color="primary" variant="flat" label>
                <v-icon icon="mdi-numeric-2-circle" start></v-icon>Detalles del equipo
              </v-chip>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <v-table density="compact">
                <tbody class="tbl-info">
                  <tr>
                    <td>Tipo de equipo</td>
                    <td>{{ editedItem.equipment_type }} </td>

                  </tr>
                  <tr>
                    <td>Marca</td>
                    <td>{{ editedItem.brand }}</td>
                  </tr>
                  <tr>
                    <td>Modelo</td>
                    <td>{{ editedItem.model }}</td>
                  </tr>
                  <tr>
                    <td>Serial</td>
                    <td>{{ editedItem.equipment_id }}</td>
                  </tr>

                </tbody>
              </v-table>
            </v-col>



            <v-col cols="12" sm="12" md="12">
              <v-chip color="primary" variant="flat" label>
                <v-icon icon="mdi-numeric-4-circle" start></v-icon>Detalles del movimiento
              </v-chip>
            </v-col>

            <v-col cols="12" sm="12" md="12">
              <div class="w-100">
                <v-table density="compact">
                  <thead class="tbl-info">
                    <tr>
                      <th style="width: 33% !important;" class="text-center">Personal que realizo el movimiento</th>

                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="tech in this.editedItem.technician">
                      <td>{{ tech }}</td>
                    </tr>

                    <tr v-if="this.editedItem.technician == 0">
                      <td colspan="4">
                        <p class="text-center py-3">Sin personal asignado</p>
                      </td>
                    </tr>
                  </tbody>
                </v-table>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-col cols="12" align="center">
          <base-button class="ms-1" type="secondary" title="Cerrar" @click="dialogInfo = false" />
        </v-col>
      </v-card-actions>
    </v-card>
  </v-dialog>



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
import BaseMultiSelect from "../components/base-components/BaseMultiSelect.vue";
import useAlert from "../composables/useAlert";

const { alert } = useAlert();
const langMessages = messages["es"].validations;

export default {
  components: { BaseButton, BaseInput, BaseSelect, BaseMultiSelect, BaseTextArea },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      dialogInfo: false,
      defaultDependency: "",
      isLoading: false,
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      cancelMovement: false,
      enabled: false,
      headers: [
        { title: "Equipo", key: "equipment_type" },
        { title: "Serial", key: "serial_number" },
        { title: 'Movimiento', key: "type_action" },
        { title: 'Fase', key: "process_state" },
        { title: "Fecha de inicio del movimiento", key: "start_date" },
        { title: "Acciones", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Mis tickets",
      total: 0,
      options: {},
      editedItem: {
        location_id: "", dependency_id: "", technician: [], users: JSON.parse(window.localStorage.getItem("user")).name, description: "", quantity_out: 0,
        quantity_in: 1, type_action_id: "", equipment_id: [], equipment: "", state_id: "", start_date: "", end_date: "",
      },
      defaultItem: {
        location_id: "", dependency_id: "", technician: [], users: JSON.parse(window.localStorage.getItem("user")).name, description: "", quantity_out: 0,
        quantity_in: 1, type_action_id: "", equipment_id: [], equipment: "", state_id: "", start_date: "", end_date: ""
      },
      filterItem: {
        typeMovement: '',
        processStateFilter: '',
        start_date: "",
        end_date: "",
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
      endProcessDate: {
        finishProcess: ""
      },
      finishMovement: {
        id: "",
        equipment_id: "",
      },
      processStatesFilter: [
        { id: -1, name: "TODOS LOS ESTADOS" }
      ],

      typeMovements: [
        { id: -1, name: 'TODOS LOS MOVIMIENTOS' }
      ],
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
      filterItem: {
        typeMovement: { required, minLength: minLength(1), },
        processStateFilter: { required, minLength: minLength(1), },
        start_date: {
          required,
          minLength: minLength(1),
        },
        end_date: {
          required,
          minLength: minLength(1)
        },
      },
      editedItem: {
        location_id: {
          required,
          minLength: minLength(1),
        },
        dependency_id: {
        },
        users: {
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
        type_action_id: {
          required,
          minLength: minLength(1),
        }, equipment_id: {
          required,
          minLength: minLength(1),
        },
        equipment: {
          minLength: minLength(1),
        },
        start_date: {
          required,
          minLength: minLength(1)
        },
        end_date: {
          minLength: minLength(1)
        },
        description: {

        }
      },
      finishMovement: {

      }
    };
  },

  // rulesChangeStatus: {}
  computed: {
    formTitle() {
      this.editedItem.users = JSON.parse(window.localStorage.getItem("user")).name;
      return this.editedIndex === -1 ? "Nuevo movimiento" : "Editar movimiento";
    },
    filterEquipment() {
      if (!this.editedItem.equipment_id) {
        return [];
      }
      else {
        return this.equipment.filter(item => item.serial_number !== this.editedItem.equipment_id);
      }
    },
    filterUserEquipment() {
      return this.userEquipment = this.userEquipment.filter((item, index, self) =>
        index === self.findIndex((t) => t.serial_number === item.serial_number)
      );

    },

    filterTypeAction() {
      return this.typeAction.filter(action => action.is_internal.toLowerCase() === "personal externo")
    },
    currentAction() {
      return this.getCurrentAction(this.editedItem.type_action_id);
    },
    currentProcess() {

      return this.getCurrentProcess(this.editedItem.state_id);
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
    currentAction(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.editedItem.equipment = "";

        if (this.editedItem.equipment_id.length != 0 && typeof this.editedItem.equipment_id !== 'string') {
          this.editedItem.equipment_id.length = 0;
        }


      }
    },

    'v$.editedItem.type_action_id.$model': function (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.editedItem.equipment = "";
        if (this.editedItem.equipment_id.length != 0 && typeof this.editedItem.equipment_id !== 'string') {
          this.editedItem.equipment_id.length = 0;
        }
      }
    }


  },

  created() {
    this.initialize();
  },

  beforeMount() {
    this.getDataFromApi({ page: 1, itemsPerPage: 10, sortBy: [], search: "" });
  },

  methods: {

    async filterByMovement() {
      this.isLoading = true;

      this.records = [];
      this.v$.filterItem.$validate();
      if (this.v$.filterItem.$invalid) {
        alert.error("Campos obligatorios");
        this.isLoading = false;

        return;
      }

      try {

          const { data } = await backendApi.get(`/historyUser/${JSON.parse(window.localStorage.getItem("user")).name}`, {
          params: { filter: this.filterItem },
        });
        this.records = data.data;



      } catch (error) {
        alert.error("Ocurrió un error al generar el historial.");

      } finally {
        setTimeout(() => (this.isLoading = false), 800)
      }
    },

    infoItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogInfo = true;
    },

    getCurrentAction(actionName) {
      for (let element of this.typeAction) {
        if (actionName == element.name) {
          return element.id;
        }
      }
    },
    getCurrentProcess(processName) {
      for (let element of this.processState) {
        if (processName == element.name) {
          return element.id;
        }
      }
    },
    movementCancelStatusItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.cancelMovement = true;
    },
    closeCancelMovement() {
      this.cancelMovement = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    async changeToCancelStatus() {

      this.finishMovement.id = this.editedItem.id;
      this.finishMovement.equipment_id = this.editedItem.equipment_id;
      this.finishMovement.history_change = this.editedItem.history_change_id;
      this.finishMovement.type_action_id = this.editedItem.type_action_id;
      this.v$.finishMovement.$validate();
      if (this.v$.finishMovement.$invalid) {
        alert.error("Campos obligatorios");
        return;
      }

      this.finishMovement.state_id = this.processState.find(item => item.id == 4)['name'];
      this.isLoading = true;

      try {
        const endStatus = await backendApi.put(`/cancelMovement/`, this.finishMovement);
        alert.success(endStatus.data.message);
      } catch (error) {
        this.closeCancelMovement();
      }
      finally {
        await this.$nextTick();
        setTimeout(() => (this.isLoading = false), 800);
        this.initialize();
        this.closeCancelMovement();
      }


    },

    getCurrentDateTime() {
      const now = new Date();
      const year = now.getFullYear();
      const month = (now.getMonth() + 1).toString().padStart(2, '0');
      const day = now.getDate().toString().padStart(2, '0');
      const hours = now.getHours().toString().padStart(2, '0');
      const minutes = now.getMinutes().toString().padStart(2, '0');

      // Format: YYYY-MM-DDThh:mm (datetime-local format)
      this.editedItem.start_date = `${year}-${month}-${day}T${hours}:${minutes}`;
    },

    addEquipment() {
      var isInArray = false;
      if (this.editedItem.equipment != "") {
        this.editedItem.equipment_id.forEach(item => {
          if (item == this.editedItem.equipment) {
            isInArray = true;
          }
        });

        if (!isInArray) {
          this.editedItem.equipment_id.push(this.editedItem.equipment);
        }
      }
    },
    deleteEquipment(item) {
      this.editedItem.equipment_id = this.editedItem.equipment_id.filter(
        function (obj) {
          return obj !== item
        }
      )
    },




    // Cambiando fecha de prestamo
    async initialize() {
      this.loading = true;
      this.records = [];



      let requests = [
        this.getDataFromApi(),
        backendApi.get('/typeAction', {
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
        backendApi.get(`/equipment-user/${JSON.parse(window.localStorage.getItem("user")).name}`, {
          params: { itemsPerPage: -1 },
        }),



      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });


      if (responses) {
        this.typeAction = responses[1].data.data;
        this.equipment = responses[2].data;
        this.processState = responses[3].data.data;
        this.location = responses[4].data.data;
        this.dependency = responses[5].data.data;
        this.userEquipment = responses[6].data.data;

        this.typeMovements = this.selectTypeMovements(responses[1].data.data);
        this.processStatesFilter = this.selectProcessStates(responses[3].data.data);

      }
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
        this.editedItem.equipment_id.length = 0;
      });
    },

    selectProcessStates(data) {
      let processStateList = this.processStatesFilter
      data.forEach(function (item) {
        processStateList.push(item)
      })
      return processStateList
    },

    selectTypeMovements(data) {
      let typeMovementList = this.typeMovements
      data.forEach(function (item) {
        typeMovementList.push(item)
      })
      return typeMovementList
    },

    async save() {
      this.editedItem.state_id = "Pendiente"
      this.v$.editedItem.$validate();
      if (this.v$.editedItem.$invalid) {
        alert.error("Campos obligatorios");
        return;
      }
      this.isLoading = true;

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
        finally {
          setTimeout(() => (this.isLoading = false), 800);
          this.close();
          this.initialize();
          return;
        }


      }

      //Creating record
      try {
        const { data } = await backendApi.post('/historyChange', this.editedItem);
        alert.success(data.message);
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }
      finally {

        setTimeout(() => (this.isLoading = false), 800);
        this.close();
        this.initialize();
        this.editedItem.equipment_id.length = 0;
        return;

      }

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
        this.isLoading = true
        const { data } = await backendApi.delete(`/historyChange/${this.editedItem.id}`, {
          params: {
            id: this.editedItem.id,
          },
        });

        alert.success(data.message);
      } catch (error) {
        this.close();
      }
      finally {
        setTimeout(() => (this.isLoading = false), 800)
        this.initialize();
        this.closeDelete();

      }


    },

    getDataFromApi(options) {
      this.loading = true;
      this.records = [];

      //debounce
      clearTimeout(this.debounce);
      this.debounce = setTimeout(async () => {
        try {

          const { data } = await backendApi.get(`/historyUser/${JSON.parse(window.localStorage.getItem("user")).name}`, {
            params: { ...options, search: this.search, filter: this.filterItem },
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

      this.dependency.forEach(element => {
        if (element.id == 1) {
          this.editedItem.dependency_id = element.name;
        }
      });
      this.getCurrentDateTime();
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