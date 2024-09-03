<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>

                <v-row class="p-2 mt-2">
                    <h2 v-if="this.message != ''">{{ this.message }}</h2>

                    <template v-else v-for="(equipments, index) in this.records" :key="index">
                        <v-col cols="12" sm="12" md="12" lg="4" xl="4" class="pl-2 pb-0 pr-2">
                            <v-card max-width="50rem" rel="noopener" color="grey-lighten-5"
                                @click="infoItem(equipments)">
                                <v-card-title><v-icon icon="mdi-desktop-classic" color="primary"
                                        size="small"></v-icon>{{ equipments.tipo }}</v-card-title>
                                <v-card-subtitle>{{ equipments.marca + ' ' + equipments.model }}</v-card-subtitle>
                                <v-card-text>{{ equipments.number_active }}</v-card-text>
                            </v-card>
                        </v-col>
                    </template>

                </v-row>
            </v-container>
        </v-card>
        <!--  -->
        <v-row justify="center">
            <v-dialog v-model="dialogInfo" width="1024">
                <v-card>
                    <v-card-title>
                        <h2 class="mx-auto mt-3 pt-3 text-center black-secondary">
                            Detalles del Equipo
                        </h2>
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-chip color="primary" variant="flat" label>
                                        <v-icon icon="mdi-numeric-1-circle" start></v-icon>Detalles
                                        del equipo
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
                                                <td>{{ this.editedItem.tipo }}</td>
                                            </tr>
                                            <tr>
                                                <td>Marca</td>
                                                <td>{{ this.editedItem.marca }}</td>
                                            </tr>
                                            <tr>
                                                <td>Modelo</td>
                                                <td>{{ this.editedItem.model }}</td>
                                            </tr>
                                            <tr>
                                                <td>Serial</td>
                                                <td>{{ this.editedItem.serial_number }}</td>
                                            </tr>
                                        </tbody>
                                    </v-table>
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
    components: {
        BaseButton,
        BaseInput,
        BaseSelect,
        BaseMultiSelect,
        BaseTextArea,
    },
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            search: "",
            selected: [],
            userLogged: JSON.parse(window.localStorage.getItem("user")).name,
            records: [],
            editedIndex: -1,
            title: "Equipos Asignados",
            total: 0,
            options: {},
            message: "",
            dialogInfo: false,
            editedItem: {
                number_active: "", tipo: "", marca: "", model: "", serial_number: ""
            },
            defaultItem: {
                number_active: "", tipo: "", marca: "", model: "", serial_number: ""
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
                finishProcess: "",
            },
            finishMovement: {
                id: "",
                finish_date: "",
                equipment_id: "",
                description: "",
            },
            changeStatusProccess: {
                id: "",
                state_id: "",
                serial_number: "",
            },
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
                typeMovement: { required, minLength: minLength(1) },
                processStateFilter: { required, minLength: minLength(1) },
                start_date: {
                    required,
                    minLength: minLength(1),
                },
                end_date: {
                    required,
                    minLength: minLength(1),
                },
            },

            editedItem: {
                number_active: {
                    required,
                    minLength: minLength(1),
                },
                tipo: {
                    required,
                    minLength: minLength(1),
                },
                marca: {
                    required,
                    minLength: minLength(1),
                },

                model: {
                    required,
                    minLength: minLength(1),
                },
                serial_number: {
                    required,
                    minLength: minLength(1),
                    numeric
                },
            },
            finishMovement: {
                finish_date: {
                    /*  required, */
                },
                description: {
                    /*  required */
                },
            },
            changeStatusProccess: {
                state_id: {
                    /*  required */
                },
            },
        };
    },

    computed: {

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
        this.getDataFromApi();
    },

    methods: {
        async initialize() {
            this.loading = true;
            this.records = [];
            let requests = [
                this.getDataFromApi() /* 
                backendApi.get('/typeAction', {
                    params: { itemsPerPage: -1 },
                }),*/

            ];

            /* const responses = await Promise.all(requests).catch((error) => {
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
      
                      this.typeMovements = this.selectTypeMovements(responses[1].data.data);
                      this.processStatesFilter = this.selectProcessStates(responses[4].data.data);
      
      
                  }
                  this.loading = false; */
        },

        infoItem(item) {
            console.log("si entra: ", item)

            this.editedItem = Object.assign({}, item);
            this.dialogInfo = true;
        },
        close() {
            this.dialogInfo = false;
            this.isLoading = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
                this.editedItem.equipment_id.length = 0;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        getDataFromApi() {
            this.loading = true;
            this.records = [];
            console.log('records: ', this.records)
            //debounce
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                try {
                    const { data } = await backendApi.get(
                        `/userEquipment/${this.userLogged}`
                    );
                    this.records = data.data;
                    console.log('records: ', this.records)
                    if (this.records.length === 0) {
                        this.message = "¡No hay equipos asignados!"
                    } else {
                        this.message = "";
                    }



                } catch (error) {
                    alert.error("No fue posible obtener los registros.");

                }
            }, 500);
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
    border: 1px solid #c5cae9;
    padding: 0.75rem 0.9375rem;
}

tbody,
tr {
    border-bottom: 1px solid #fce8e8;
}

.tbl-info td:nth-child(1) {
    background-color: #e8eaf6;
    color: #1a237e;
    width: 50%;
}

.tbl-info tr th {
    background-color: #e8eaf6;
    color: #1a237e !important;
    text-align: center !important;
    width: 50%;
}

tbody tr:last-of-type {
    border-bottom: 2px solid #6856dbc7;
}
</style>
