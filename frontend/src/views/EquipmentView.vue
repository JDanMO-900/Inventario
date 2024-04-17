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

    <v-dialog v-model="dialog" max-width="800px" persistent>
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

              <!-- number_active -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Numero de activo fijo" v-model="v$.editedItem.number_active.$model"
                  :rules="v$.editedItem.number_active" />
              </v-col>
              <!-- number_active -->


              <!-- number_internal_active -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Numero de registro interno" v-model="v$.editedItem.number_internal_active.$model"
                  :rules="v$.editedItem.number_internal_active" />
              </v-col>
              <!-- number_internal_active -->

            </v-row>


            <v-row class="pt-3">

              <!-- invoice_number -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Numero de factura" v-model="v$.editedItem.invoice_number.$model"
                  :rules="v$.editedItem.invoice_number" />
              </v-col>
              <!-- invoice_number -->

              <!-- adquisition_date -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Adquisition Date" v-model="v$.editedItem.adquisition_date.$model"
                  :rules="v$.editedItem.adquisition_date" type="date" />
              </v-col>
              <!-- adquisition_date -->


            </v-row>


            <v-row class="pt-3">
              <!-- model -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Modelo" v-model="v$.editedItem.model.$model" :rules="v$.editedItem.model" />
              </v-col>
              <!-- model -->




              <!-- serial_number -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Serial Number" v-model="v$.editedItem.serial_number.$model"
                  :rules="v$.editedItem.serial_number" />
              </v-col>
              <!-- serial_number -->
            </v-row>
            <v-row class="pt-3">

              <!-- equipment state -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Estado" :items="this.equipmentstate" item-title="name" item-value="name"
                  v-model.trim="v$.editedItem.state.$model" :rules="v$.editedItem.state" />
              </v-col>
              
              <!-- equipment state -->

              <!-- equipment state -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Dependencia" :items="this.dependency" item-title="name" item-value="name"
                  v-model.trim="v$.editedItem.dependency.$model" :rules="v$.editedItem.dependency" />
              </v-col>
              <!-- equipment state -->

              <!-- Tipo de equipo -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Tipo de equipo" :items="this.equipmenttype" item-title="name"
                  item-value="name" v-model.trim="v$.editedItem.type.$model"
                  :rules="v$.editedItem.type" />
              </v-col>
              <!-- Tipo de equipo -->
            </v-row>

            <v-row class="pt-3">
              <!-- brand -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Marcas" :items="this.brand" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.brand.$model" :rules="v$.editedItem.brand" />
              </v-col>
              <!-- brand -->

              <!-- Proveedor -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Proveedor" :items="this.provider" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.provider.$model" :rules="v$.editedItem.provider">
                </base-select>
              </v-col>

              <!-- Proveedor -->

              <!-- Licencias -->
              <v-col cols="12" sm="12" md="4">
                <base-select placeholder="Licencias" :items="this.license" item-title="name" :value="name"
                  v-model.trim="v$.editedItem.licenses.$model" :rules="v$.editedItem.licenses"   multiple>
                </base-select>
              </v-col>

              <!-- Licencias -->


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
            <v-row class="pt-3">
              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Registro del equipo </b></p>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 "><b class="text-indigo-darken-4">Dependencia: </b>{{
                  this.equipmentData.dependency }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de activo fijo: </b>{{
                  this.equipmentData.number_internal_active }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de registro interno: </b>{{
                  this.equipmentData.number_internal_active }}</p>

              </v-col>


              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de factura: </b>{{
                  this.equipmentData.invoice_number
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Fecha de
                    adquisición: </b>{{ this.equipmentData.adquisition_date }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Especificaciones del equipo </b></p>
              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Modelo del equipo: </b>{{
                  this.equipmentData.model
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de serie del equipo: </b>{{
                  this.equipmentData.serial_number
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Tipo de equipo: </b>{{
                  this.equipmentData.type
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Marca: </b>{{
                  this.equipmentData.brand
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Licencias del equipo </b></p>
              </v-col>
              

                <v-col cols="3" v-for="(license, index) in this.equipmentData.licenses" :key="index">
                  <p class="text-grey-darken-4 text-h6">{{ license }}</p>

                 

                </v-col>

                


              <v-col cols="12" sm="12" md="12">
                <hr>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <p class="text-grey-darken-4 text-h6 text-center"> <b>Datos proveedor </b></p>
              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Proveedor: </b>{{
                  this.equipmentData.provider
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Contacto del proveedor: </b>{{
                  this.equipmentData.contact_name
                }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Numero de contacto del proveedor:
                  </b>{{ this.equipmentData.contact_phone
                  }}</p>

              </v-col>

              <v-col cols="12" sm="12" md="6">
                <p class="text-grey-darken-4 text-h6"><b class="text-indigo-darken-4">Dirección: </b>{{
                  this.equipmentData.address
                }}</p>

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
      dialogInfo: false,
      headers: [

        { title: "Numero de activo fijo", key: "number_active" },
        { title: "Numero de registro interno", key: "number_internal_active" },
        { title: "Modelo", key: "model" },
        { title: "Serial", key: "serial_number" },
        { title: "Fecha de adquisición", key: "adquisition_date" },
        { title: " Código de factura", key: "invoice_number" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Listado de equipos",
      total: 0,
      options: {},
      editedItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "",
        state: "", dependency: "", type: "", brand: "", provider: "", licenses: "",

      },
      defaultItem: {
        number_active: "", number_internal_active: "", model: "", serial_number: "", adquisition_date: "", invoice_number: "",
        state: "", dependency: "", type: "", brand: "", provider: "", licenses: "",

      },
      loading: false,
      debounce: 0,
      equipmentstate: [],
      dependency: [],
      equipmenttype: [],
      brand: [],
      provider: [],
      license: [],
      equipmentData: []

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
          required,
          minLength: minLength(1),
        }, invoice_number: {
          required,
          minLength: minLength(1),
        }, state: {
          required,
          minLength: minLength(1),
        },

        dependency: {
          required,
          minLength: minLength(1),
        },
        type: {
          required,
          minLength: minLength(1),
        },
        brand: {
          required,
          minLength: minLength(1),
        },
        provider: {
          required,
          minLength: minLength(1),
        },
        licenses: {
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
      this.equipmentData = Object.assign({}, item);
      this.dialogInfo = true;

    },

    async initialize() {
      this.loading = true;
      this.records = [];

      let requests = [
        this.getDataFromApi(),
        backendApi.get('/equipmentState', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/dependency', {
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
      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });

      if (responses) {

        this.equipmentstate = responses[1].data.data;
        this.dependency = responses[2].data.data;
        this.equipmenttype = responses[3].data.data;
        this.brand = responses[4].data.data;
        this.provider = responses[5].data.data;
        this.license = responses[6].data.data;


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
      console.log(this.editedItem)

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
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }

      this.close();
      this.initialize();
      return;
    },

    deleteItem(item) {
      console.log(item)
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


          var user = JSON.parse(window.localStorage.getItem("user"));


          const rol = await backendApi.get(`/user/${user.name}`);







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