<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>

        <h2>{{ title }} </h2>
        <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />

        </div>
        <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>

      <v-progress-linear v-if="loading" indeterminate color="indigo-accent-3"></v-progress-linear>
      <v-data-table :headers="headers" :items="records" item-key="name" class="elevation-1" :search="search">

        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" title="Editar"
            v-if="item.raw.process_state_id != 3 || userRol == 2" />
          <v-icon size="20" class="mr-2" @click="deleteItem(item.raw)" icon="mdi-delete" title="Eliminar"
            v-if="item.raw.process_state_id != 3 || userRol == 2" />
          <v-icon size="20" class="mr-2" @click="infoItem(item.raw)" icon="mdi-information" title="Información" />
          <template v-if="((item.raw.action_id == 1 || item.raw.action_id == 4) && item.raw.process_state_id != 3
            && item.raw.process_state_id != 4)">
            <v-icon size="20" class="mr-2" @click="movementFinishDateItem(item.raw)" icon="mdi-swap-horizontal"
              title="Terminar movimiento" />
          </template>

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
          <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">
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
                  Responsable(s) del equipo
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>
              <!-- name -->
              <v-col cols="6" sm="12" md="12">
                <base-multi-select label="Usuarios" :items="this.users" item-title="name"
                  v-model="v$.editedItem.users.$model" :rules="v$.editedItem.users" clearable>
                </base-multi-select>
              </v-col>
              <!-- name -->

              <v-col cols="12" sm="12" md="12">
                <v-chip color="primary" variant="flat" label>
                  <v-icon icon="mdi-numeric-2-circle" start></v-icon>
                  Detalles del movimiento
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>
              <!-- acción realizada -->
              <v-col cols="4" sm="12" md="12">
                <base-select label="Tipo de movimiento" :items="filterTypeAction" item-title="name"
                  v-model.trim="v$.editedItem.type_action_id.$model" :rules="v$.editedItem.type_action_id" clearable>
                </base-select>
              </v-col>
              <!-- acción realizada -->

              <!-- fecha del movimiento -->
              <v-col cols="12" sm="12" md="12">
                <base-input label="Fecha" v-model="v$.editedItem.start_date.$model" :rules="v$.editedItem.start_date"
                  type="datetime-local" clearable />
              </v-col>
              <!-- fecha del movimiento -->

              <!-- location -->
              <v-col cols="6" sm="12" md="6">
                <base-select label="Área o ubicación" :items="this.location" item-title="name"
                  v-model.trim="v$.editedItem.location_id.$model" :rules="v$.editedItem.location_id" clearable>
                </base-select>
              </v-col>
              <!-- location -->

              <!-- name -->
              <v-col cols="6" sm="12" md="6">
                <base-select label="Dependencia" :items="this.dependency" item-title="name"
                  v-model.trim="v$.editedItem.dependency_id.$model" :rules="v$.editedItem.dependency_id" clearable>
                </base-select>
              </v-col>
              <!-- name -->


              <!-- Numero de activo fijo 1 -->
              <v-col cols="4" sm="12" md="12">
                <base-select v-if="this.formTitle == 'Nuevo movimiento'" label="Equipo(s)" :items="this.equipment"
                  item-title="format" item-value="serial_number" v-model.trim="v$.editedItem.equipment_serial.$model"
                  :rules="v$.editedItem.equipment_serial" clearable>
                </base-select>


                <base-select v-else label="Equipo" :items="this.equipment" item-title="format"
                  item-value="serial_number" v-model.trim="v$.editedItem.equipment_id.$model"
                  :rules="v$.editedItem.equipment_id" clearable>
                </base-select>
              </v-col>

              <v-col cols="12" sm="12" md="12" v-if="this.formTitle == 'Nuevo movimiento'">
                <base-button color="blue-accent-1" type="primary" density="comfortable" title="Agregar"
                  @click="addEquipment" block prepend-icon="mdi-plus-thick" />
              </v-col>

              <v-col cols="12" sm="12" md="12" v-if="this.formTitle == 'Nuevo movimiento'">
                <div class="w-100">
                  <v-table density="compact">
                    <thead>
                      <tr>
                        <td><b>Serial del equipo</b></td>
                        <td><b>Acción</b></td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="equipo in this.editedItem.equipment_id" v-if="this.formTitle == 'Nuevo movimiento'">
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
              <!-- Numero de activo fijo 1 -->

              <!-- Cantidad de salida -->
              <v-col cols="4" sm="12" md="6" v-if="currentAction == 1">
                <base-input label="Cantidad de equipos que entregan: " v-model="v$.editedItem.quantity_out.$model"
                  :rules="v$.editedItem.quantity_out" type="number" min="0" max="100" clearable />
              </v-col>
              <v-col cols="4" sm="12" md="6">

              </v-col>
              <!-- Cantidad de salida -->

              <!-- Cantidad de entrada -->
              <v-col cols="4" sm="12" md="6" v-if="currentAction == 1">
                <base-input label="Cantidad de equipos que se reciben: " v-model="v$.editedItem.quantity_in.$model"
                  :rules="v$.editedItem.quantity_in" type="number" min="0" max="100" clearable />
              </v-col>
              <!-- Cantidad de entrada -->

              <v-col cols="12" sm="12" md="12">
                <v-chip color="primary" variant="flat" label>
                  <v-icon icon="mdi-numeric-3-circle" start></v-icon>
                  Técnico responsable del movimiento
                </v-chip>
                <v-divider class="mt-2"></v-divider>
              </v-col>

              <!-- técnico responsable -->
              <v-col cols="6" sm="12" md="12">
                <base-multi-select label="Técnico(s)" :items="this.userTech" item-title="name"
                  v-model.trim="v$.editedItem.technician.$model" :rules="v$.editedItem.technician" clearable>
                </base-multi-select>
              </v-col>
              <!-- técnico responsable -->

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
              <base-button type="primary" title="Confirmar" @click="deleteItemConfirm" :loading="isLoading" />
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
            <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">Detalles del movimiento</h2>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="12" md="12">
                  <v-chip color="primary" variant="flat" label>
                    <v-icon icon="mdi-numeric-1-circle" start></v-icon>Responsable(s) del equipo
                  </v-chip>
                </v-col>
                <v-col cols="12" sm="12" md="12">
                  <div class="w-100">
                    <v-table density="compact">
                      <thead class="tbl-info">
                        <tr>
                          <td>Usuario(s) del equipo</td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="user in this.editedItem.users">
                          <td>{{ user }}</td>
                        </tr>
                        <tr v-if="this.editedItem.technicalAttributes == 0">
                          <td colspan="4">
                            <p class="text-center py-3">Sin datos que mostrar</p>
                          </td>
                        </tr>
                      </tbody>
                    </v-table>
                  </div>
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
                        <td>Código de activo fijo</td>
                        <td>{{ this.editedItem.number_active }}</td>
                      </tr>
                      <tr>
                        <td>Tipo de equipo</td>
                        <td>{{ this.editedItem.type1 }}</td>
                      </tr>
                      <tr>
                        <td>Marca</td>
                        <td>{{ this.editedItem.brand1 }}</td>
                      </tr>
                      <tr>
                        <td>Modelo</td>
                        <td>{{ this.editedItem.model1 }}</td>
                      </tr>
                      <tr>
                        <td>Serial</td>
                        <td>{{ this.editedItem.equipment_id }}</td>
                      </tr>

                    </tbody>
                  </v-table>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <v-chip color="primary" variant="flat" label>
                    <v-icon icon="mdi-numeric-3-circle" start></v-icon>Detalles del movimiento
                  </v-chip>
                </v-col>
                <v-col cols="12" sm="12" md="12">
                  <v-table density="compact">
                    <tbody class="tbl-info">
                      <tr>
                        <td>Dependencia</td>
                        <td>{{ this.editedItem.dependency_id }}</td>
                      </tr>
                      <tr>
                        <td>Ubicación</td>
                        <td>{{ this.editedItem.location_id }}</td>
                      </tr>
                      <tr>
                        <td>Fecha que se inició el movimiento</td>
                        <td>{{ this.editedItem.start_date }}</td>
                      </tr>
                      <tr>
                        <td>Observaciones</td>
                        <td v-if="this.editedItem.description != null">{{ this.editedItem.description }}</td>
                        <td v-else>No hay datos disponibles</td>
                      </tr>
                    </tbody>
                  </v-table>
                </v-col>

                <v-col cols="12" sm="12" md="12">
                  <v-chip color="primary" variant="flat" label>
                    <v-icon icon="mdi-numeric-4-circle" start></v-icon>Técnico responsable del movimiento
                  </v-chip>
                </v-col>
                <template v-if="(currentProcess == 1) && userRol == 2" class="d-flex justify-content-center">
                  <v-col cols="4" sm="12" md="12" class="d-flex justify-content-center">                  
                    <base-button class="ms-1 bg-green-lighten-1" title="Cambiar el estado del proceso"
                      @click="processStanteChangeItem(this.editedItem)" prepend-icon="mdi-sync-circle" />                  
                  </v-col>
                </template>
                <v-col cols="12" sm="12" md="12">
                  <div class="w-100">
                    <v-table density="compact">
                      <thead class="tbl-info">
                        <tr>
                          <th style="width: 33% !important;">Nombre</th>
                          <th style="width: 33% !important;">Tipo de movimiento</th>
                          <th style="width: 33% !important;">Etapa del movimiento</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="tech in this.editedItem.technician">
                          <td>{{ tech }}</td>
                          <td>{{ this.editedItem.type_action_id }}</td>
                          <td>{{ this.editedItem.state_id }}</td>
                        </tr>

                        <tr v-if="this.editedItem.technician == 0">
                          <td colspan="4">
                            <p class="text-center py-3">Sin datos que mostrar</p>
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
    </v-row>
  </div>

  <v-dialog v-model="dialogMovementFinishDate" max-width="45rem">
    <v-card>
      <v-card-title>
        <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
          Finalizar {{ this.editedItem.type_action_id }}
        </h2>
      </v-card-title>

      <v-card-text>
        <v-container>
          <!-- Form -->
          <v-row class="pt-3">
            <v-col cols="12" sm="12" md="12">
              <template
                v-if="currentAction == 4 || currentAction == 1">
                <div>
                  <!-- Fecha de finalización de movimiento -->
                  <base-input label="Fecha de finalización del movimiento"
                    v-model="v$.finishMovement.finish_date.$model" :rules="v$.finishMovement.finish_date"
                    type="datetime-local" />

                  <!-- Fecha de finalización de movimiento -->
                </div>
              </template>
            </v-col>

            <v-col cols="12" sm="12" md="12">
              <base-text-area label="Comentarios" v-model="v$.finishMovement.description.$model"
                :rules="v$.finishMovement.description" />
            </v-col>
          </v-row>

          <!-- Form -->
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="changeMovementFinishDate" :loading="isLoading" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeMovementFinishDate" />
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>


  <v-dialog v-model="dialogChangeProcessState" max-width="45rem">
    <v-card>
      <v-card-title>
        <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
          Cambiar estado de {{ this.editedItem.type_action_id }}
        </h2>
      </v-card-title>

      <v-card-text>
        <v-container>
          <!-- Form -->
          <v-row class="pt-3">

            <v-col cols="4" sm="12" md="12">
              <base-select label="Estado del proceso" :items="this.processState" item-title="name"
                v-model.trim="v$.changeStatusProccess.state_id.$model" :rules="v$.changeStatusProccess.state_id">
              </base-select>

            </v-col>
          </v-row>

          <!-- Form -->
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="changeProcessStanteChange" :loading="isLoading" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeProcessStanteChange" />
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-dialog>



</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { messages } from "@/utils/validators/i18n-validators";
import { minLength, required, numeric } from "@/lang/i18n";
import backendApi from "@/services/backendApi";
import BaseButton from "../components/base-components/BaseButton.vue";
import BaseInput from "../components/base-components/BaseInput.vue";
import BaseSelect from "../components/base-components/BaseSelect.vue";
import BaseTextArea from "../components/base-components/BaseTextArea.vue";
import BaseMultiSelect from "../components/base-components/BaseMultiSelect.vue";
import useAlert from "../composables/useAlert";
const { alert } = useAlert();

export default {
  components: { BaseButton, BaseInput, BaseSelect, BaseMultiSelect, BaseTextArea },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      search: "",
      selected: [],
      userRol: JSON.parse(window.localStorage.getItem("user")).rol,
      dialog: false,
      dialogDelete: false,
      dialogInfo: false,
      dialogMovementFinishDate: false,
      dialogChangeProcessState: false,
      enabled: false,
      isLoading: false,
      headers: [
        { title: "Tipo de movimiento", key: "type_action_id" },
        { title: 'Fecha', key: 'start_date'},
        { title: "Equipo", key: "type1" },
        { title: "# de activo", key: "number_active" },
        { title: "Ubicación", key: "location_id" },
        { title: "Estado", key: "state_id" },
        { title: "Acciones", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Movimientos",
      total: 0,
      options: {},
      editedItem: {
        location_id: "", dependency_id: "", technician: [], users: [], description: "", quantity_out: 0,
        quantity_in: 1, type_action_id: "", equipment_id: [], equipment_serial: "", state_id: "", start_date: "", end_date: "",
      },
      defaultItem: {
        location_id: "", dependency_id: "", technician: [], users: [], description: "", quantity_out: 0,
        quantity_in: 1, type_action_id: "", equipment_id: [], equipment_serial: "", state_id: "", start_date: "", end_date: ""
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
        finish_date: "",
        equipment_id: "",
        description: ""
      },
      changeStatusProccess: {
        id: "",
        state_id: "",
        serial_number: ""
      }
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
        users: {
          required,
          minLength: minLength(1),
        },

        technician: {
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
        type_action_id: {
          required,
          minLength: minLength(1),
        }, equipment_id: {
          required,
          minLength: minLength(1),
        },
        equipment_serial: {
          minLength: minLength(1),
        },
        start_date: {
          required,
          minLength: minLength(1),
        },
        end_date: {
          minLength: minLength(1)
        },
        description: {
        }

      },
      finishMovement: {
        finish_date: {
          required,
        },
        description: {
          required
        }
      },
      changeStatusProccess: {
        state_id: {
          required
        }
      }
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo movimiento" : "Editar movimiento";
    },
    filterEquipment() {
      if (!this.editedItem.equipment_id) {
        return [];
      }
      else {
        return this.equipment.filter(item => item.serial_number !== this.editedItem.equipment_id);
      }
    }, filterTypeAction() {
      return this.typeAction.filter(action => action.is_internal.toLowerCase() === "personal interno")
    },
    currentAction() {
      return this.getCurrentAction(this.editedItem.type_action_id);
    },
    currentProcess() {   
      return this.getCurrentProcess(this.editedItem.state_id);
    }
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
    getCurrentAction(actionName) {
      for (let element of this.typeAction) {
        if (actionName == element.name) {
          return element.id;
        }
      }
    },
    getCurrentProcess(processName){
      for (let element of this.processState) {
        if (processName == element.name) {
          return element.id;
        }
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
      if (this.editedItem.equipment_serial != "") {
        this.editedItem.equipment_id.forEach(item => {
          if (item == this.editedItem.equipment_serial) {
            this.editedItem.equipment_serial = ''
            isInArray = true;
          }
        });

        if (!isInArray) {
          this.editedItem.equipment_id.push(this.editedItem.equipment_serial);
          this.editedItem.equipment_serial = ''
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

    movementFinishDateItem(item) {
      this.finishMovement.description = item.description;
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogMovementFinishDate = true;
    },

    closeMovementFinishDate() {
      this.dialogMovementFinishDate = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async changeMovementFinishDate() {
      this.finishMovement.id = this.editedItem.id;
      this.finishMovement.equipment_id = this.editedItem.equipment_id;
      this.v$.finishMovement.$validate();
      if (this.v$.finishMovement.$invalid) {
        alert.error("Campos obligatorios");
        return;
      }
      this.isLoading = true;

      try {
        if (this.finishMovement.finish_date != null) {
          this.finishMovement.state_id = "Finalizado";
          const endStatus = await backendApi.put(`/changeStatus/`, this.finishMovement);
          alert.success(endStatus.data.message);
        }
      } catch (error) {
        this.closeMovementFinishDate();
      }
      finally {
        setTimeout(() => (this.isLoading = false), 800);
        this.finishMovement.state_id = "";
        this.finishMovement.finish_date = "";
        this.finishMovement.description = "";
        this.initialize();
        this.closeMovementFinishDate();
      }
    },

    // Aqui va el nuevo elemento
    processStanteChangeItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogChangeProcessState = true;
    },

    closeProcessStanteChange() {
      this.dialogChangeProcessState = false;
    },

    async changeProcessStanteChange() {
      this.changeStatusProccess.id = this.editedItem.id;
      this.changeStatusProccess.serial_number = this.editedItem.equipment_id;
      this.v$.changeStatusProccess.$validate();
      if (this.v$.changeStatusProccess.$invalid) {
        alert.error("Campos obligatorios");
        return;
      }
      this.isLoading = true;
      try {
        if (this.changeStatusProccess.state_id != null) {
          const endStatus = await backendApi.put(`/updateProcessState/`, this.changeStatusProccess);
          alert.success(endStatus.data.message);
        }
      } catch (error) {
        this.closeProcessStanteChange();
      }
      finally {
        setTimeout(() => (this.isLoading = false), 800)
        this.closeProcessStanteChange();
        this.dialogInfo = false;
        this.initialize();
      }
    },
    // Aqui va el nuevo elemento
    async initialize() {
      this.loading = true;
      this.records = [];
      let requests = [
        this.getDataFromApi(),
        backendApi.get('/typeAction', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/userData', {
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
        let uniqueTechNames = new Set();
        for (let i = 0; i < this.users.length; i++) {
          if (this.users[i].role_id.toLowerCase() === "tecnico")
            uniqueTechNames.add(this.users[i].name);
          }
          this.userTech = Array.from(uniqueTechNames)
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
      this.isLoading = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
        this.editedItem.equipment_id.length = 0;
      });
    },

    async save() {
      
      if (this.editedItem.type_action_id == this.typeAction.find(item=>item.id ==4)['name'] || this.editedItem.type_action_id == this.typeAction.find(item=>item.id ==1)['name']) {
        this.editedItem.state_id = this.processState.find(item=>item.id ==2)['name'];
      }
      else {
        this.editedItem.state_id = this.processState.find(item=>item.id ==3)['name'];
      }

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
          setTimeout(() => (this.isLoading = false), 800)
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
      this.close();
      this.initialize();
      this.editedItem.equipment_id.length = 0;
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
        this.isLoading = true
        const { data } = await backendApi.delete(`/historyChange/${this.editedItem.id}`, {
          params: {
            id: this.editedItem.id,
          },
        });
        alert.success(data.message);
      } catch (error) {
        this.closeDelete();
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
  border: 1px solid #C5CAE9;
  padding: 0.75rem 0.9375rem;
}

tbody,
tr {
  border-bottom: 1px solid #fce8e8;
}

.tbl-info td:nth-child(1) {
  background-color: #E8EAF6;
  color: #1A237E;
  width: 50%;
}

.tbl-info tr th {
  background-color: #E8EAF6;
  color: #1A237E !important;
  text-align: center !important;
  width: 50%;
}

tbody tr:last-of-type {
  border-bottom: 2px solid #6856dbc7;
}
</style>