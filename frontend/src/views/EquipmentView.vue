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
          <v-icon size="20" class="mr-2" @click="availabilityItem(item.raw)" icon="mdi-calendar" />
          <v-icon icon="fa:fas fa-search"></v-icon>
          <font-awesome-icon :icon="['fas', 'file-invoice']" />
        </template>
        <template v-slot:no-data>
          <v-icon @click="initialize" icon="mdi-refresh" />
        </template>
      </v-data-table-server>
    </v-card>

    <v-dialog v-model="dialog" max-width="800px" persistent>
      <v-card>
        <v-card-title>
          <h2 class="mx-auto pt-3 mb-3 text-center black-secondary">
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
                        :rules="v$.editedItem.number_active" />
                    </v-col>
                    <!-- number_active -->

                    <!-- number_internal_active -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de registro interno"
                        v-model="v$.editedItem.number_internal_active.$model"
                        :rules="v$.editedItem.number_internal_active" />
                    </v-col>
                    <!-- number_internal_active -->

                    <!-- brand -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-select label="Marca" :items="brand" item-title="name" item-value="name"
                        v-model.trim="v$.editedItem.brand.$model" :rules="v$.editedItem.brand" />
                    </v-col>
                    <!-- brand -->

                    <!-- model -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Modelo" v-model="v$.editedItem.model.$model" :rules="v$.editedItem.model" />
                    </v-col>
                    <!-- model -->

                    <!-- serial_number -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-input label="Número de serie" v-model="v$.editedItem.serial_number.$model"
                        :rules="v$.editedItem.serial_number" />
                    </v-col>
                    <!-- serial_number -->

                    <!-- equipment state -->
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <base-select label="Estado del equipo" :items="equipmentstate" item-title="name" item-value="name" 
                       v-model.trim="v$.editedItem.state.$model"
                        :rules="v$.editedItem.state" />

              
                    </v-col>
                    <!-- equipment state -->

                    <!-- tipo de equipo -->
                    <v-col cols="12" sm="12" md="12" lg="12">
                      <base-select label="Tipo de equipo" v-model.trim="v$.editedItem.equipment_type_id.$model"
                        :items="this.equipmenttype" item-title="name" item-value="name"
                        :rules="v$.editedItem.equipment_type_id" />
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
                        item-value="name"  v-model.trim="v$.editedItem.technicalDescription.$model"
                        :rules="v$.editedItem.technicalDescription" />
                    </v-col>
                    <!-- característica -->

                    <!-- valor -->
                    <v-col cols="4" sm="12" md="6" lg="6">
                      <base-input label="Capacidad" v-model="v$.editedItem.attribute.$model"
                        :rules="v$.editedItem.attribute" type="number" min="0" max="100" />
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


    <!-- Probando cambio de disponibilidad -->
    <v-dialog v-model="dialogAvailability" max-width="45rem">
      <v-card class="h-100">
        <v-container>
          <h1 class="black-secondary text-center mt-3 mb-3">

            <b>¿Desea cambiar el estado de disponibilidad del equipo de </b>
            <span
              :class="{ 'green-text': this.editedItem.availability == 'Disponible', 'red-text': this.editedItem.availability == 'En uso' }">
              '{{ typeof this.editedItem.availability === 'string' ? this.editedItem.availability.toLowerCase() :
                this.editedItem.availability }}'
            </span>

            <span v-if="this.editedItem.availability == 'Disponible'"> a 'en uso'?</span>
            <span v-if="this.editedItem.availability == 'En uso'"> a 'disponible'?</span>

          </h1>
          <v-row>
            <v-col align="center">
              <base-button type="primary" title="Confirmar" @click="changeAvailabilityItemConfirm" />
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeAvailability" />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>

  </div>


  <!-- Aqui ira el dialogo de visualizar datos totales del equipo -->

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
            <v-col cols="12" sm="12" md="12">
              <hr>
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <p class="text-grey-darken-6 text-h6 text-left"><b>Disponibilidad del equipo:</b> <span
                  :class="{ 'green-text': this.allowable == 'Disponible', 'red-text': this.allowable == 'En uso' }">{{
                    this.allowable }}</span></p>

            </v-col>
            <v-row class="pt-3">


              <v-col cols="12" sm="12" md="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Registro del equipo </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-col cols="12" sm="12" md="12">
                        <div class="w-100">
                          <v-table>
                            <thead>
                              <tr>
                                <th class="text-left text-grey-darken-4">
                                  Numero de activo fijo
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Numero de registro interno
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Codigo de factura
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Fecha de adquisición
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>{{ this.equipmentData.number_active }}</td>
                                <td>{{ this.equipmentData.number_internal_active }}</td>
                                <td>{{ this.equipmentData.invoice_number }}</td>
                                <td>{{ this.equipmentData.adquisition_date }}</td>
                              </tr>
                            </tbody>
                          </v-table>

                        </div>
                      </v-col>
                    </v-expansion-panel-text>
                  </v-expansion-panel>

                </v-expansion-panels>

              </v-col>


              <!-- Nuevo Formato -->
              <v-col cols="12" sm="12" md="12">


                <v-expansion-panels>

                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Generalidades del equipo </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-col cols="12" sm="12" md="12">
                        <div class="w-100">
                          <v-table>
                            <thead>
                              <tr>
                                <th class="text-left text-grey-darken-4">
                                  Tipo de equipo
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Marca
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Modelo
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Serie
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Estado del equipo
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>{{ this.equipmentData.equipment_type_id }}</td>
                                <td>{{ this.equipmentData.brand }}</td>
                                <td>{{ this.equipmentData.model }}</td>
                                <td>{{ this.equipmentData.serial_number }}</td>
                                <td>{{ this.equipmentData.state }}</td>
                              </tr>
                            </tbody>
                          </v-table>

                        </div>
                      </v-col>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>

              <!-- Nuevo Formato -->

              <!-- Nuevo Formato -->
              <v-col cols="12" sm="12" md="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Especificaciones del equipo </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-col cols="12" sm="12" md="12">
                        <div class="w-100">
                          <v-table>
                            <thead>
                              <tr>
                                <th class="text-left text-grey-darken-4">
                                  Especificación
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Capacidad
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="datos in this.equipmentData.technicalAttributes" style="height: 60px;">
                                <td>{{ datos.technicalDescription }}</td>
                                <td>{{ datos.attribute }}</td>
                              </tr>
                              <tr v-if="this.equipmentData.technicalAttributes == 0">
                                <td colspan="2">
                                  <p class="text-center py-3">Sin datos que mostrar</p>
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

              <!-- Nuevo Formato -->


              <!-- Nuevo Formato -->
              <v-col cols="12" sm="12" md="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Licencias del equipo </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-col cols="12" sm="12" md="12">
                        <div class="w-100">


                          <v-table>
                            <thead>
                              <tr>
                                <th class="text-left text-grey-darken-4">
                                  N
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Nombre licencia
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(license, index) in this.equipmentData.licenses" :key="index"
                                style="height: 60px;">
                                <td>{{ index + 1 }}</td>
                                <td>{{ license }}</td>
                              </tr>
                              <tr v-if="this.equipmentData.licenses == 0">
                                <td colspan="2">
                                  <p class="text-center py-3">Sin datos que mostrar</p>
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

              <!-- Nuevo Formato -->

              <v-col cols="12" sm="12" md="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Datos proveedor </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-col cols="12" sm="12" md="12">
                        <div class="w-100">

                          <v-table>
                            <thead>

                              <tr>
                                <th class="text-left text-grey-darken-4">
                                  Proveedor
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Contacto del proveedor
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Numero de contacto del proveedor
                                </th>
                                <th class="text-left text-grey-darken-4">
                                  Fecha de adquisición
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <template v-if="this.equipmentData.provider != null">
                                <tr>
                                  <td>{{ this.equipmentData.provider }}</td>
                                  <td>{{ this.equipmentData.contact_name }}</td>
                                  <td>{{ this.equipmentData.contact_phone }}</td>
                                  <td>{{ this.equipmentData.adquisition_date }}</td>
                                </tr>

                              </template>
                              <template v-else>
                                <tr>
                                  <td colspan="4">
                                    <p class="text-center py-3">Sin datos que mostrar</p>
                                  </td>
                                </tr>

                              </template>


                            </tbody>
                          </v-table>
                        </div>
                      </v-col>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
              <!-- Nuevo Formato -->

              <!-- Nuevo Formato -->

              <v-col cols="12" sm="12" md="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-title>
                      <p class="text-grey-darken-4 text-h6 text-center"> <b>Historial del equipo </b></p>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <div class="w-100">
                        <v-table height="300px" fixed-header>
                          <thead>
                            <tr>
                              <th>Numero de activo interno</th>
                              <th>Numero de activo fijo</th>
                              <th>Usuarios que han tenido el equipo</th>
                              <th>Movimientos del equipo</th>
                              <th>Fecha de inicio de </th>
                              <th>Fecha de finalización el movimiento</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="data in this.historyData">
                              <td>{{ data.number_active }}</td>
                              <td>{{ data.number_internal_active }}</td>
                              <td>{{ data.users }}</td>
                              <td>{{ data.type_action }}</td>
                              <td>{{ data.start_date }}</td>
                              <td v-if="data.end_date != null">{{ data.end_date }}</td>
                              <td v-else>Actividad sin terminar</td>
                            </tr>
                            <tr v-if="this.historyData == 0">
                              <td colspan="6">
                                <p class="text-center py-3">Sin datos que mostrar</p>
                              </td>
                            </tr>
                          </tbody>
                        </v-table>
                      </div>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
              <!-- Nuevo Formato -->


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
        { title: "Tipo de equipo", key: "equipment_type_id" },
        { title: "Disponibilidad", key: "availability" },
        { title: "Número de activo fijo", key: "number_active" },
        { title: "Número de registro interno", key: "number_internal_active" },
        { title: "Serial", key: "serial_number" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Inventario",
      total: 0,
      options: {},
      editedItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "",
        state: "", equipment_type_id: "", brand: "", provider: "", licenses: [], technicalDescription: "", attribute: "", technicalAttributes: [], availability: true

      },
      defaultItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "",
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
        },
        equipment_type_id: {
          required,
          minLength: minLength(1),
        },
        brand: {
          required,
          minLength: minLength(1),
        },
        provider: {

          minLength: minLength(1),
        },
        license: {

          minLength: minLength(1),
        },
        technicalDescription: {

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
      if (this.allowable == "Disponible") {
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
        this.close();
      }

      this.initialize();
      this.closeAvailability();
    },

    // Prueba cambiar estado
    addLicense() {
      var isInArray = false;


      if (this.editedItem.license != "") {

        this.editedItem.licenses.forEach(item => {


          if (item == this.editedItem.license) {
            isInArray = true;
          }
        });

        if (!isInArray) {
          this.editedItem.licenses.push(this.editedItem.license);
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
            isInArray = true;
          }
        });

        if (!isInArray) {
          this.editedItem.technicalAttributes.push({ "technicalDescription": this.editedItem.technicalDescription, "attribute": this.editedItem.attribute });
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

      const equipment_history = await backendApi.get(`/equipment/${item.number_internal_active}`);
      this.historyData = equipment_history.data;
      this.allowable = item.availability;

      this.dialogInfo = true;



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

        this.editedItem.licenses.length = 0;

        this.editedItem.technicalAttributes.length = 0;


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
          const { data } = await backendApi.put(`/equipment/${edited.id}`, edited);


          alert.success(data.message);
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
      this.initialize();
      this.editedItem.licenses.length = 0;
      this.editedItem.technicalAttributes.length = 0;
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

.green-text {
  color: green;
}

.red-text {
  color: red;
}
</style>