<template>
  <div data-app>
    <v-card class="p-3 mt-3">
      <v-container>
        <h2>{{ title }}</h2>
        <div class="options-table">
          <base-button type="primary" title="Agregar" @click="addRecord()" />
        </div>
        <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="pl-0 pb-0 pr-0">
          <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text" v-model="search"></v-text-field>
        </v-col>
      </v-container>
      <v-data-table-server :headers="headers" :items-length="total" :items="records" :loading="loading" item-title="id"
        item-value="id" @update:options="getDataFromApi">
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon size="20" class="mr-2" @click="editItem(item.raw)" icon="mdi-pencil" />
          <v-icon size="20" class="mr-2" @click="deleteItem(item.raw)" icon="mdi-delete" />
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

              <!-- location -->
              <v-col cols="12" sm="12" md="4">
                <base-input label="Location" v-model="v$.editedItem.location.$model" :rules="v$.editedItem.location" />
              </v-col>
              <!-- location -->


              <!-- description -->
              <v-col cols="12" sm="12" md="4">
                <base-input label="Description" v-model="v$.editedItem.description.$model"
                  :rules="v$.editedItem.description" />
              </v-col>
              <!-- description -->


              <!-- quantity_out -->
              <v-col cols="12" sm="12" md="4">
                <base-input label="Quantity Out" v-model="v$.editedItem.quantity_out.$model"
                  :rules="v$.editedItem.quantity_out" />
              </v-col>
              <!-- quantity_out -->


              <!-- quantity_in -->
              <v-col cols="12" sm="12" md="4">
                <base-input label="Quantity In" v-model="v$.editedItem.quantity_in.$model"
                  :rules="v$.editedItem.quantity_in" />
              </v-col>
              <!-- quantity_in -->


              <!-- name -->
              <v-col cols="12" sm="12" md="4">
                <base-select-search label="Name" v-model.trim="v$.editedItem.name.$model" :items="typeaction"
                  item="name" :rules="v$.editedItem.name" />
              </v-col>
              <!-- name -->


              <!-- number_internal_active -->
              <v-col cols="12" sm="12" md="4">
                <base-select-search label="Number Internal Active"
                  v-model.trim="v$.editedItem.number_internal_active.$model" :items="equipment"
                  item="number_internal_active" :rules="v$.editedItem.number_internal_active" />
              </v-col>
              <!-- number_internal_active -->


              <!-- number_internal_active -->
              <v-col cols="12" sm="12" md="4">
                <base-select-search label="Number Internal Active"
                  v-model.trim="v$.editedItem.number_internal_active.$model" :items="equipment"
                  item="number_internal_active" :rules="v$.editedItem.number_internal_active" />
              </v-col>
              <!-- number_internal_active -->


              <!-- name -->
              <v-col cols="12" sm="12" md="4">
                <base-select-search label="Name" v-model.trim="v$.editedItem.name.$model" :items="processstate"
                  item="name" :rules="v$.editedItem.name" />
              </v-col>
              <!-- name -->


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
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { messages } from "@/utils/validators/i18n-validators";
import { helpers, minLength, required, email } from "@vuelidate/validators";


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
      headers: [

        { title: "Location", key: "location" },
        { title: "Description", key: "description" },
        { title: "Quantity Out", key: "quantity_out" },
        { title: "Quantity In", key: "quantity_in" },
        { title: "Quantity In", key: "quantity_in" },
        { title: "ACCIONES", key: "actions", sortable: false },
      ],
      records: [],
      editedIndex: -1,
      title: "HistoryChange",
      total: 0,
      options: {},
      editedItem: {
        location: "", description: "", quantity_out: "", quantity_in: "", name: "", number_internal_active: "", number_internal_active: "", name: "",
      },
      defaultItem: {
        location: "", description: "", quantity_out: "", quantity_in: "", name: "", number_internal_active: "", number_internal_active: "", name: "",
      },
      loading: false,
      debounce: 0,
      typeaction: [],
      equipment: [],
      processstate: [],

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
        }, description: {
          required,
          minLength: minLength(1),
        }, quantity_out: {
          required,
          minLength: minLength(1),
        }, quantity_in: {
          required,
          minLength: minLength(1),
        }, name: {
          required,
          minLength: minLength(1),
        },
        number_internal_active: {
          required,
          minLength: minLength(1),
        },
        number_internal_active: {
          required,
          minLength: minLength(1),
        },
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
        backendApi.get('/typeAction', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/equipment', {
          params: { itemsPerPage: -1 },
        }), backendApi.get('/processState', {
          params: { itemsPerPage: -1 },
        }),
      ];

      const responses = await Promise.all(requests).catch((error) => {
        alert.error("No fue posible obtener el registro.");
      });
      console.log(responses)

      if (responses) {
        this.typechange = responses[1].data.data;
        this.equipment = responses[2].data.data;
        this.processstate = responses[3].data.data;

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