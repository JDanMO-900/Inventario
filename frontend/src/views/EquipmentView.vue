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

      <!-- Nueva tabla -->
      <v-progress-linear v-if="loading" indeterminate color="indigo-accent-3"></v-progress-linear>
      <v-data-table :headers="headers" :items="records" item-key="name" class="elevation-1" :search="search">
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" title="Editar"/>
          <v-icon size="20" class="mr-2" @click="deleteItem(item.raw)" icon="mdi-delete" title="Eliminar"/>
          <v-icon size="20" class="mr-2" @click="infoItem(item.raw)" icon="mdi-information" title="Detalles"/>
          <v-icon v-if="item.raw.availability != 'Disponible' " size="20" class="mr-2" @click="availabilityItem(item.raw)" icon="mdi-swap-horizontal-bold" title="Recibir equipo"/>
          <v-icon icon="fa:fas fa-search"></v-icon>
          
          <!-- <font-awesome-icon :icon="['fas', 'file-invoice']" /> -->
        </template>
        <template v-slot:no-data>
          <v-icon @click="initialize" icon="mdi-refresh" />
        </template>
      </v-data-table>
      <!-- Nueva tabla -->

    </v-card>

    <!-- Agregar y editar -->
    <v-dialog v-model="dialog" max-width="800px" persistent>
      <v-card>
        <v-card-title>
          <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">
            {{ formTitle }}
          </h2>
        </v-card-title>

        <v-card-text>
          <v-container>
            <!-- tabs -->
            <v-tabs v-model="tab" grow color="blue-accent-4">
              <v-tab value="1" prepend-icon="mdi-laptop">Equipo</v-tab>
              <v-tab value="2" prepend-icon="mdi-application-brackets">Licencias</v-tab>
              <v-tab value="3" prepend-icon="mdi-account-tie">Proveedor</v-tab>
            </v-tabs>

            <v-card-text>
              <v-window v-model="tab" class="mt-2 mb-6">
                <!-- tab de equipo -->
                <v-window-item value="1">
                  <v-row>
                    <v-col cols="12">
                      <v-chip color="primary" variant="flat" label>
                        <v-icon icon="mdi-numeric-1-circle" start></v-icon>
                        Información general
                      </v-chip>
                      <v-divider class="mt-2"></v-divider>
                    </v-col>

                    <!-- number_active -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de activo fijo" v-model="v$.editedItem.number_active.$model"
                        :rules="v$.editedItem.number_active" clearable/>
                    </v-col>
                    <!-- number_active -->

                    <!-- number_internal_active -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de registro interno"
                        v-model="v$.editedItem.number_internal_active.$model"
                        :rules="v$.editedItem.number_internal_active" clearable/>
                    </v-col>
                    <!-- number_internal_active -->

                    <!-- brand -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-select label="Marca" :items="brand" item-title="name" item-value="name"
                        v-model.trim="v$.editedItem.brand.$model" :rules="v$.editedItem.brand" clearable/>
                    </v-col>
                    <!-- brand -->

                    <!-- model -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Modelo" v-model="v$.editedItem.model.$model" :rules="v$.editedItem.model" clearable/>
                    </v-col>
                    <!-- model -->

                    <!-- serial_number -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de serie" v-model="v$.editedItem.serial_number.$model"
                        :rules="v$.editedItem.serial_number" clearable/>
                    </v-col>
                    <!-- serial_number -->

                    <!-- equipment state -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-select label="Estado del equipo" :items="equipmentstate" item-title="name" item-value="name"
                        v-model.trim="v$.editedItem.state.$model" :rules="v$.editedItem.state" clearable/>
                    </v-col>
                    <!-- equipment state -->

                    <!-- tipo de equipo -->
                    <v-col cols="12" sm="12" md="12" lg="12">
                      <base-select label="Tipo de equipo" v-model.trim="v$.editedItem.equipment_type_id.$model"
                        :items="this.equipmenttype" item-title="name" item-value="name"
                        :rules="v$.editedItem.equipment_type_id" clearable/>
                    </v-col>
                    <!-- tipo de equipo -->

                    <v-col cols="12">
                      <v-chip color="primary" variant="flat" label>
                        <v-icon icon="mdi-numeric-2-circle" start></v-icon>
                        Especificaciones técnicas
                      </v-chip>
                      <v-divider class="mt-2"></v-divider>
                    </v-col>

                    <!-- característica -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-select label="Característica" :items="this.technicalDescrip" item-title="name"
                        item-value="name" v-model.trim="v$.editedItem.technicalDescription.$model"
                        :rules="v$.editedItem.technicalDescription" clearable/>
                    </v-col>
                    <!-- característica -->

                    <!-- valor -->
                    <v-col cols="4" sm="12" md="6" lg="6">
                      <base-input label="Capacidad" v-model="v$.editedItem.attribute.$model"
                        :rules="v$.editedItem.attribute" type="number" min="0" max="100" clearable/>
                    </v-col>
                    <!-- valor -->

                    <v-col cols="12" sm="12" md="12" lg="12">
                      <base-button color="blue-accent-1" type="primary" density="comfortable" title="Agregar"
                        @click="addTechnicalAttributes" block prepend-icon="mdi-plus-thick" />
                    </v-col>

                    <v-col cols="12" sm="12" md="12">
                      <div class="w-100">
                        <v-table density="compact">
                          <thead>
                            <tr>
                              <td><b>Característica</b></td>
                              <td><b>Capacidad</b></td>
                              <td><b>Acción</b></td>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="datos in this.editedItem.technicalAttributes">
                              <td>{{ datos.technicalDescription }}</td>
                              <td>{{ datos.attribute }}</td>
                              <td>
                                <v-icon size="20" class="mr-2"
                                  @click="deleteTechnicalAttributes(datos.technicalDescription)" color="red-darken-4"
                                  icon="mdi-delete" />
                              </td>
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
                  </v-row>
                </v-window-item>
                <!-- tab de equipo -->

                <!-- tab de licencias -->
                <v-window-item value="2">
                  <v-col cols="12">
                    <v-chip color="primary" variant="flat" label>
                      <v-icon icon="mdi-numeric-3-circle" start></v-icon>
                      Licencias instaladas
                    </v-chip>
                    <v-divider class="mt-2"></v-divider>
                  </v-col>

                  <!-- licencias -->
                  <v-col cols="12" sm="12" md="12">
                    <base-select label="Licencias" :items="this.license" item-title="name" item-value="name"
                      v-model.trim="v$.editedItem.license.$model" :rules="v$.editedItem.license" />
                  </v-col>

                  <v-col cols="12" sm="12" md="12">
                    <base-button color="blue-accent-1" type="primary" density="comfortable" title="Agregar"
                      @click="addLicense" block prepend-icon="mdi-plus-thick" />
                  </v-col>

                  <v-col cols="12" sm="12" md="12">
                    <div class="w-100">
                      <v-table density="compact">
                        <thead>
                          <tr>
                            <td><b>Licencia</b></td>
                            <td><b>Acción</b></td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="Licencia in this.editedItem.licenses">
                            <td>{{ Licencia }}</td>
                            <td>
                              <v-icon size="20" class="mr-2" @click="deleteLicenses(Licencia)" icon="mdi-delete"
                                color="red-darken-4" />
                            </td>
                          </tr>
                          <tr v-if="this.editedItem.licenses == 0">
                            <td colspan="4">
                              <p class="text-center py-3">Sin datos que mostrar</p>
                            </td>
                          </tr>
                        </tbody>
                      </v-table>
                    </div>
                  </v-col>
                </v-window-item>
                <!-- tab de licencias -->

                <!-- tab de proveedor -->
                <v-window-item value="3">
                  <v-row>
                    <v-col cols="12">
                      <v-chip color="primary" variant="flat" label>
                        <v-icon icon="mdi-numeric-4-circle" start></v-icon>
                        Información del proveedor
                      </v-chip>
                      <v-divider class="mt-2"></v-divider>
                    </v-col>

                    <!-- invoice_number -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de factura" v-model="v$.editedItem.invoice_number.$model"
                        :rules="v$.editedItem.invoice_number" />
                    </v-col>
                    <!-- invoice_number -->

                    <!-- adquisition_date -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Fecha de adquisición" v-model="v$.editedItem.adquisition_date.$model"
                        :rules="v$.editedItem.adquisition_date" type="date" />
                    </v-col>
                    <!-- adquisition_date -->

                    <!-- proveedor -->
                    <v-col cols="12" sm="12" md="12" lg="12">
                      <base-select label="Nombre" :items="this.provider" item-title="name"
                        v-model.trim="v$.editedItem.provider.$model" :rules="v$.editedItem.provider">
                      </base-select>
                    </v-col>
                    <!-- proveedor -->
                  </v-row>
                </v-window-item>
                <!-- tab de proveedor -->
              </v-window>
            </v-card-text>
            <!-- tabs -->
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
    <!-- Agregar y editar -->

    <!-- Eliminar equipo -->
    <v-dialog v-model="dialogDelete" max-width="400px">
      <v-card class="h-100">
        <v-container>
          <h1 class="black-secondary text-center mt-3 mb-3 pb-3">
            ¿Eliminar equipo?
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
    <!-- Eliminar equipo -->

    <!-- Cambio de disponibilidad -->
    <v-dialog v-model="dialogAvailability" max-width="45rem">
      <v-card class="h-100">
        <v-container>
          <h2 class="black-secondary text-center mt-3 mb-3">
            <b>Confirmar Devolución del Equipo</b>
            
          </h2>
          <br/>
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="changeAvailabilityItemConfirm" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeAvailability" />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- Cambio de disponibilidad -->
  </div>

  <!-- Detalles del equipos -->
  <v-dialog v-model="dialogInfo" width="1024" @click:outside="closeDetails">
    <v-card>
      <v-card-title>
        <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">Datos del equipo</h2>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-col cols="12" sm="12" md="12" lg="12" class="d-flex justify-content-center">
            <base-button 
            class="ms-1" type="secondary" 
            title="Generar reporte de equipo" 
            @click="generateIndividualReport(this.equipmentData)" 
            prepend-icon="mdi-file-pdf-box"/>
          </v-col> 
                   
          <v-col cols="12" sm="12" md="12">
            <p class="text-grey-darken-6 text-center"><b>Disponibilidad del equipo </b>
              <v-chip>
                <span :class="{ 'green-text': this.allowable.toLowerCase() == 'disponible', 'red-text': this.allowable.toLowerCase() == 'en uso' }">
                  {{ this.allowable }}
                </span>
              </v-chip>
            </p>
          </v-col>
          <v-row class="pt-1">
            <!-- Información del equipo -->
            <v-col cols="12" sm="12" md="12">
              <v-expansion-panels v-model="panel.one">
                <v-expansion-panel>
                  <v-expansion-panel-title>
                    <p class="text-indigo-darken-4 text-center">Información del equipo</p>
                  </v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <v-col cols="12" sm="12" md="12">
                      <v-table density="compact">
                        <tbody class="tbl-info">
                          <tr>
                            <td>N° de activo fijo</td>
                            <td>{{ this.equipmentData.number_active }}</td>
                          </tr>
                          <tr>
                            <td>N° de registro interno</td>
                            <td>{{ this.equipmentData.number_internal_active }}</td>
                          </tr>
                          <tr>
                            <td>Tipo de equipo</td>
                            <td>{{ this.equipmentData.equipment_type_id }}</td>
                          </tr>
                          <tr>
                            <td>Marca</td>
                            <td>{{ this.equipmentData.brand }}</td>
                          </tr>
                          <tr>
                            <td>Modelo</td>
                            <td>{{ this.equipmentData.model }}</td>
                          </tr>
                          <tr>
                            <td>Serie</td>
                            <td>{{ this.equipmentData.serial_number }}</td>
                          </tr>
                          <tr>
                            <td>Estado</td>
                            <td>{{ this.equipmentData.state }}</td>
                          </tr>
                        </tbody>
                      </v-table>
                    </v-col>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-col>
            <!-- Información del equipo -->

            <!-- Historial de movimientos -->
            <v-col cols="12" sm="12" md="12">
              <v-expansion-panels v-model="panel.two">
                <v-expansion-panel>
                  <v-expansion-panel-title>
                    <p class="text-indigo-darken-4 text-center">Historial de movimientos</p>
                  </v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <v-col cols="12" sm="12" md="12">
                      <v-table density="compact">
                        <thead class="tbl-info">
                          <tr>
                            <th style="width: 25% !important;">Tipo de movimiento</th>
                            <th style="width: 25% !important;">Responsable</th>
                            <th style="width: 25% !important;">Fecha de inicio del movimiento</th>
                            <th style="width: 25% !important;">Fecha de retorno del equipo</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="data in this.historyData">
                            <td>{{ data.type_action }}</td>
                            <td>{{ data.users }}</td>
                            <td>{{ data.start_date }}</td>
                            <td v-if="data.end_date != null">{{ data.end_date }}</td>
                            <td v-else>Actividad sin terminar</td>
                          </tr>
                          <tr v-if="this.historyData == 0">
                            <td colspan="6">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                          </tr>
                        </tbody>
                      </v-table>
                    </v-col>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-col>
            <!-- Historial de movimientos -->

            <!-- Especificaciones técnicas-->
            <v-col cols="12" sm="12" md="12">
              <v-expansion-panels>
                <v-expansion-panel>
                  <v-expansion-panel-title>
                    <p class="text-indigo-darken-4 text-center">Especificaciones técnicas</p>
                  </v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <v-col cols="12" sm="12" md="12">
                      <div class="w-100">
                        <v-table density="compact">
                          <thead class="tbl-info">
                            <tr>
                              <th>Característica</th>
                              <th>Capacidad</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="datos in this.equipmentData.technicalAttributes">
                              <td>{{ datos.technicalDescription }}</td>
                              <td>{{ datos.attribute }}</td>
                            </tr>
                            <tr v-if="this.equipmentData.technicalAttributes == 0">
                              <td colspan="2">
                                <p class="text-center">Sin datos que mostrar</p>
                              </td>
                              
                            </tr>
                          </tbody>
                        </v-table>
                      </div>
                    </v-col>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-col>
            <!-- Especificaciones técnicas -->

            <!-- Licencias instaladas -->
            <v-col cols="12" sm="12" md="12">
              <v-expansion-panels>
                <v-expansion-panel>
                  <v-expansion-panel-title>
                    <p class="text-indigo-darken-4 text-center">Licencias instaladas</p>
                  </v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <v-col cols="12" sm="12" md="12">
                      <v-table density="compact">
                        <thead class="tbl-info">
                          <tr>
                            <!-- <th style="width: 5% !important;">N°</th> -->
                            <th>Aplicación</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(license, index) in this.equipmentData.licenses" :key="index">
                            <!-- <td class="text-center">{{ index + 1 }}</td> -->
                            <td class="text-center">{{ license }}</td>
                          </tr>
                          <tr v-if="this.equipmentData.licenses == 0">
                            <td colspan="2">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                          </tr>
                        </tbody>
                      </v-table>
                    </v-col>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-col>
            <!-- Licencias instaladas -->

            <!-- Proveedor -->
            <v-col cols="12" sm="12" md="12">
              <v-expansion-panels>
                <v-expansion-panel>
                  <v-expansion-panel-title>
                    <p class="text-indigo-darken-4 text-center">Proveedor</p>
                  </v-expansion-panel-title>
                  <v-expansion-panel-text>
                    <v-col cols="12" sm="12" md="12">
                      <v-table density="compact">
                        <tbody class="tbl-info">
                          <tr>
                            <td>Nombre</td>
                            <td v-if="this.equipmentData.provider == null">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                            <td  v-else>{{ this.equipmentData.provider }}</td>
 
                          </tr>

                          <tr>
                            <td>Código de factura</td>
                            <td v-if="this.equipmentData.invoice_number == null">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                            <td  v-else>{{ this.equipmentData.invoice_number }}</td>
 
                          </tr>
                          <tr>
                            <td>Fecha de adquisición</td>

                            <td v-if="this.equipmentData.adquisition_date == null">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                            <td  v-else>{{ this.equipmentData.adquisition_date }}</td>


                          </tr>
                          <tr>
                            <td>Contacto directo</td>
  
                            <td v-if="this.equipmentData.contact_name == null">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                            <td  v-else>{{ this.equipmentData.contact_name }}</td>
                          </tr>
                          <tr>
                            <td>Número de contacto</td>
 
                            <td v-if="this.equipmentData.contact_phone == null">
                              <p class="text-center">Sin datos que mostrar</p>
                            </td>
                            <td  v-else>{{ this.equipmentData.contact_phone }}</td>
                          </tr>
                        </tbody>
                      </v-table>
                    </v-col>
                  </v-expansion-panel-text>
                </v-expansion-panel>
              </v-expansion-panels>
            </v-col>
            <!-- Proveedor -->
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-col cols="12" align="center">
          <base-button class="ms-1" type="secondary" title="Cerrar" @click="closeDetails" />
        </v-col>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <!-- Detalles del equipos -->
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { messages } from "@/utils/validators/i18n-validators";
import { minLength, required, email, numeric, maxLength } from "@/lang/i18n";
import backendApi from "@/services/backendApi";
import BaseButton from "../components/base-components/BaseButton.vue";
import BaseInput from "../components/base-components/BaseInput.vue";
import BaseSelect from "../components/base-components/BaseSelect.vue";
import BaseMultiSelect from "../components/base-components/BaseMultiSelect.vue";
import BaseTextArea from "../components/base-components/BaseTextArea.vue";
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
      tab: null,
      enabled: false,
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      dialogInfo: false,
      dialogAvailability: false,
      headers: [
        { title: "Equipo", key: "equipment_type_id" },
        { title: "Modelo", key: "model" },
        { title: "Estado", key: "state" },
        { title: "# de serie", key: "serial_number" },
        { title: "# de activo fijo", key: "number_active" },
        { title: "# de registro interno", key: "number_internal_active" },
        { title: "Disponibilidad", key: "availability" },
        { title: "Acciones", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Inventario",
      total: 0,
      options: {},
      editedItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "", license: "",
        state: "", equipment_type_id: "", brand: "", provider: "", licenses: [], technicalDescription: "", attribute: "", technicalAttributes: [], availability: true
      },
      defaultItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "", license: "",
        state: "", equipment_type_id: "", brand: "", provider: "", license: "", licenses: [], technicalDescription: "", attribute: "", technicalAttributes: [], availability: true
      },
      loading: false,
      debounce: 0,
      equipmentstate: [],
      equipmenttype: [],
      brand: [],
      provider: [],
      license: [],
      equipmentData: [],
      historyData: [],
      allowable: "",
      panel: {
        one: 0,
        two: 0,
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
        number_active: {
          required,
          minLength: minLength(1),
        }, number_internal_active: {
          required,
          minLength: minLength(1),
        }, model: {
          required,
          minLength: minLength(1),
        }, serial_number: {
          required,
          minLength: minLength(1),
        }, adquisition_date: {
          minLength: minLength(1),
        }, invoice_number: {
          minLength: minLength(1),
        }, state: {
          required,
          minLength: minLength(1),
        }, equipment_type_id: {
          required,
          minLength: minLength(1),
        }, brand: {
          required,
          minLength: minLength(1),
        }, provider: {
          minLength: minLength(1),
        }, license: {
          minLength: minLength(1),
        }, technicalDescription: {
          minLength: minLength(1),
        }, attribute: {
          numeric,
          minLength: minLength(1),
        },
      },
    };
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Agregar equipo" : "Editar equipo";
    },
    allowableColor() {
      if (this.allowable.toLowerCase() == "disponible") {
        return 'green';
      }
      else {
        return 'red';
      }
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
    // Prueba cambiar estado
    availabilityItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogAvailability = true;
    },

    closeAvailability() {
      this.dialogAvailability = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    async changeAvailabilityItemConfirm() {
      const edited = Object.assign(
        this.records[this.editedIndex],
        this.editedItem
      );

      try {
        const availabilityStatus = await backendApi.put(`/available/`, edited);
        alert.success(availabilityStatus.data.message);
        
      } catch (error) {

        this.closeAvailability();
      }
      finally{
        await this.$nextTick();
        this.initialize();
        this.closeAvailability();
      }



    },

    // Prueba cambiar estado
    addLicense() {
      var isInArray = false;
      if (this.editedItem.license != "") {
        this.editedItem.licenses.forEach(item => {
          if (item == this.editedItem.license) {
            this.editedItem.license = ''
            isInArray = true;
          }
        });

        if (!isInArray) {          
          this.editedItem.licenses.push(this.editedItem.license);
          this.editedItem.license = ''
        }
      }

    },
    deleteLicenses(item) {
      this.editedItem.licenses = this.editedItem.licenses.filter(
        function (obj) {
          return obj !== item
        }
      )
    },

    addTechnicalAttributes() {
      var isInArray = false;
      if (this.editedItem.attribute != "" && this.editedItem.technicalDescription != "" && this.editedItem.attribute != undefined) {
        this.editedItem.technicalAttributes.forEach(item => {
          if (item.technicalDescription == this.editedItem.technicalDescription) {
            this.editedItem.technicalDescription = ''
            this.editedItem.attribute = ''
            isInArray = true;
          }
        });

        if (!isInArray) {
          this.editedItem.technicalAttributes.push({ "technicalDescription": this.editedItem.technicalDescription, "attribute": this.editedItem.attribute });
          this.editedItem.technicalDescription = ''
          this.editedItem.attribute = ''
        }
      }
    },
    deleteTechnicalAttributes(item) {
      this.editedItem.technicalAttributes = this.editedItem.technicalAttributes.filter(
        function (obj) {
          return obj.technicalDescription !== item
        }
      )
    },

    async infoItem(item) {
      this.editedIndex = this.records.indexOf(item);
      this.equipmentData = Object.assign({}, item);
      const equipment_history = await backendApi.get(`/equipment/${item.serial_number}`);
      this.historyData = equipment_history.data;
      this.allowable = item.availability;
      this.dialogInfo = true;
    },

    async generateIndividualReport(item){
      const generate_report = await backendApi.get(`/individual-reportpdf/${item.serial_number}`, {
                    // blob: This retrieve the data as binary as information
                    responseType: 'blob',
                });
                // This line tells the computer is a pdf and translate the binary information to get the url
                const report_data = new Blob([generate_report.data], { type: 'application/pdf' })
                const url_report = window.URL.createObjectURL(report_data);
                window.open(url_report);
    },

    closeDetails() {
      this.panel.one = 0
      this.panel.two = 0
      this.dialogInfo = false
    },

    async initialize() {
      this.loading = true;
      this.records = [];
      let requests = [
        this.getDataFromApi(),
        backendApi.get('/equipmentState', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/equipmentType', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/brand', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/provider', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/license', {
          params: { itemsPerPage: -1 },
        }),
        backendApi.get('/technicalDescription', {
          params: { itemsPerPage: -1 },
        }),
      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });

      if (responses) {
        this.equipmentstate = responses[1].data.data;
        this.equipmenttype = responses[2].data.data;
        this.brand = responses[3].data.data;
        this.provider = responses[4].data.data;
        this.license = responses[5].data.data;
        this.technicalDescrip = responses[6].data.data;
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
      });

       if(this.formTitle == "Agregar equipo"){

        this.editedItem.licenses.length = 0;
        this.editedItem.technicalAttributes.length = 0;
        
       }
       else{
        this.initialize();
       }
        
      



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
          const { data } = await backendApi.put(`/equipment/${edited.id}`, edited);
          alert.success(data.message);

          this.editedItem.licenses.length = 0;
          this.editedItem.technicalAttributes.length = 0;
          this.tab = null
        } catch (error) {
          alert.error("No fue posible actualizar el registro.");
        }

        this.close();
        this.initialize();
        return;
      }

      //Creating record
      try {
        const { data } = await backendApi.post('/equipment', this.editedItem);
        alert.success(data.message);
        this.tab = null
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }

      this.close();

      this.editedItem.licenses.length = 0;
      this.editedItem.technicalAttributes.length = 0;
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
        const { data } = await backendApi.delete(`/equipment/${this.editedItem.id}`, {
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
          const { data } = await backendApi.get('/equipment', {
            params: { ...options, search: this.search },
          });

          // var user = JSON.parse(window.localStorage.getItem("user"));
          // const rol = await backendApi.get(`/user/${user.name}`);
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

.green-text {
  color: green;
}

.red-text {
  color: red;
}
</style>