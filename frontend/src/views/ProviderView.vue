<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>
        <h2>{{ title }}</h2>
        <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />
        </div>
        <v-col cols="12" sm="12" md="4" lg="12" xl="12" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>
      
      <v-data-table :headers="headers" :items-length="total" :items="records" :loading="loading" item-title="id"
        item-value="id" @update:options="getDataFromApi">
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" title="Editar"/>
          <v-icon size="20" class="mr-2" @click="deleteItem(item.raw)" icon="mdi-delete" title="Eliminar"/>
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

              <!-- name -->
              <v-col cols="12" sm="12" md="12">
                <base-input label="Nombre del proveedor" v-model="v$.editedItem.name.$model"
                  :rules="v$.editedItem.name" />
              </v-col>
              <!-- name -->

              <!-- contact_name -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Nombre del contacto" v-model="v$.editedItem.contact_name.$model"
                  :rules="v$.editedItem.contact_name" />
              </v-col>
              <!-- contact_name -->

              <!-- contact_phone -->
              <v-col cols="12" sm="12" md="6">
                <base-input label="Numero del contacto" v-model="v$.editedItem.contact_phone.$model"
                  :rules="v$.editedItem.contact_phone" @input="phoneNumberFormat(v$.editedItem.contact_phone.$model)"
                  :value="phoneNumberFormat(v$.editedItem.contact_phone.$model)" />

              </v-col>
              <!-- contact_phone -->

              <!-- address -->
              <v-col cols="12" sm="12" md="12">


                <base-text-area placeholder="Dirección del proveedor" v-model="v$.editedItem.address.$model"
                  :rules="v$.editedItem.address" />

              </v-col>
              <!-- address -->

            </v-row>
            <!-- Form -->
            <v-row>
              <v-col align="center">
                <base-button type="primary" title="Guardar" @click="save" :disable="isSubmitedDisable" :loading="isLoading"/>
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
              <base-button type="primary" title="Confirmar" @click="deleteItemConfirm" :loading="isLoading"/>
              <base-button class="ms-1" type="secondary" title="Cancelar" @click="closeDelete" />
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
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

// Atol


export default {



  components: { BaseButton, BaseInput, BaseSelect, BaseTextArea },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      isLoading: false,
      value: null,
      search: "",
      selected: [],
      dialog: false,
      dialogDelete: false,
      headers: [
        { title: "Proveedor", key: "name" },
        { title: "Nombre del contacto", key: "contact_name" },
        { title: "Número de contacto", key: "contact_phone" },
        { title: "Dirección del proveedor", key: "address" },
        { title: "Acciones", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "Lista de proveedores",
      total: 0,
      options: {},
      editedItem: {
        name: "", contact_name: "", contact_phone: "", address: "",
      },
      defaultItem: {
        name: "", contact_name: "", contact_phone: "", address: "",
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
        }, contact_name: {
          required,
          minLength: minLength(1),

        }, contact_phone: {
          required,

          minLength: minLength(9),
          maxLength: maxLength(15),



        }, address: {
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

    phoneNumberFormat(phoneNumber) {
      console.log(phoneNumber.length)


      var x = phoneNumber.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})/);
      this.value = !x[2] ? x[1] :  x[1] + (x[2] ? '-' + x[2] : '');
    




      return this.value;

    },

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


      // Validate only the contact_phone field

      if (this.v$.$invalid) {
        alert.error("Campos obligatorios o formato incorrecto");
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
          const { data } = await backendApi.put(`/provider/${edited.id}`, edited);

          alert.success(data.message);
        } catch (error) {
          alert.error("No fue posible actualizar el registro.");
        }
        setTimeout(() => (this.isLoading = false), 800);
        this.close();
        this.initialize();
        return;
      }

      //Creating record
      try {
        const { data } = await backendApi.post('/provider', this.editedItem);

        alert.success(data.message);
      } catch (error) {
        alert.error("No fue posible crear el registro.");
      }
      setTimeout(() => (this.isLoading = false), 800);
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
      this.isLoading = true;
      try {
        const { data } = await backendApi.delete(`/provider/${this.editedItem.id}`, {
          params: {
            id: this.editedItem.id,
          },
        });

        alert.success(data.message);
      } catch (error) {
        this.close();
      }
      setTimeout(() => (this.isLoading = false), 800);
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
          const { data } = await backendApi.get('/provider', {
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