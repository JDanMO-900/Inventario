<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>

                <v-row class="p-2 mt-2">
                    <template v-for="(equipments, index) in this.records" :key="index">
                        <v-col cols="12" sm="12" md="12" lg="4" xl="4" class="pl-2 pb-0 pr-2">
                            <v-card max-width="50rem" rel="noopener" color="grey-lighten-5"
                                @click="infoItem(equipments.number_active)">
                                <v-card-title><v-icon icon="mdi-desktop-classic" color="primary"
                                        size="small"></v-icon>{{ equipments.tipo }}</v-card-title>
                                <v-card-subtitle>{{ equipments.marca + ' ' + equipments.model + ' ' +
                                    equipments.number_active }}</v-card-subtitle>
                            </v-card>
                        </v-col>
                    </template>
                </v-row>
            </v-container>
        </v-card>

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
                                <!-- <v-col cols="12" sm="12" md="12">
                                    <v-chip color="primary" variant="flat" label>
                                        <v-icon icon="mdi-numeric-1-circle" start></v-icon>Responsable(s) del equipo
                                    </v-chip>
                                </v-col> -->
                                <!-- <v-col cols="12" sm="12" md="12">
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
                                </v-col> -->
                                <v-col cols="12" sm="12" md="12">
                                    <v-chip color="primary" variant="flat" label>
                                        <v-icon icon="mdi-numeric-2-circle" start></v-icon>Detalles
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
                                        <v-icon icon="mdi-numeric-4-circle" start></v-icon>Técnico
                                        responsable del movimiento
                                    </v-chip>
                                </v-col>
                                <template v-if="currentProcess == 1 && userRol == 2"
                                    class="d-flex justify-content-center">
                                    <v-col cols="4" sm="12" md="12" class="d-flex justify-content-center">
                                        <base-button class="ms-1 bg-green-lighten-1"
                                            title="Cambiar el estado del proceso"
                                            @click="processStanteChangeItem(this.editedItem)"
                                            prepend-icon="mdi-sync-circle" />
                                    </v-col>
                                </template>
                                <v-col cols="12" sm="12" md="12">
                                    <div class="w-100">
                                        <v-table density="compact">
                                            <thead class="tbl-info">
                                                <tr>
                                                    <th style="width: 33% !important">Nombre</th>
                                                    <th style="width: 33% !important">
                                                        Tipo de movimiento
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="tech in this.editedItem.technician">
                                                    <td>{{ tech }}</td>
                                                    <td>{{ this.editedItem.type_action_id }}</td>
                                                </tr>

                                                <tr v-if="this.editedItem.technician == 0">
                                                    <td colspan="4">
                                                        <p class="text-center py-3">
                                                            Sin datos que mostrar
                                                        </p>
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



console.log("userLogged: ", userLogged);

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
            equipment: "",
            brand: "",
            model: "",
            total: 0,
            options: {},
            editedItem: {
                
            },
            defaultItem: {
               
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
        /* formTitle() {
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
            } */
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
        /* getCurrentAction(actionName) {
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
    
            closeFinishIncompleteActivity() {
                this.dialogFinishIncompleteActivity = false;
    
            },
            finishIncompleteActivityItem(item) {
    
                this.finishActivity = item;
                this.dialogFinishIncompleteActivity = true;
            },
    
            async finishIncompleteActivityItemConfirm() {
    
                this.isLoading = true;
    
                try {
                    const finishStatus = await backendApi.put(`/finishIncompleteMovement/`, this.finishActivity);
                    alert.success(finishStatus.data.message);
    
                } catch (error) {
    
                    this.closeFinishIncompleteActivity();
                }
                finally {
                    setTimeout(() => (this.isLoading = false), 800);
                    await this.$nextTick();
                    this.initialize();
                    this.closeFinishIncompleteActivity();
    
                }
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
            }, */

        /*  async changeMovementFinishDate() {
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
             }, */

        // Aqui va el nuevo elemento
        /* processStanteChangeItem(item) {
                this.editedIndex = this.records.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialogChangeProcessState = true;
            },
    
            closeProcessStanteChange() {
                this.dialogChangeProcessState = false;
            }, */

        /* async changeProcessStanteChange() {
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
            }, */
        // Aqui va el nuevo elemento
        async initialize() {
            this.loading = true;
            this.records = [];
            let requests = [
                this.getDataFromApi() /* 
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
                backendApi.get('/equipmentType', {
                    params: { itemsPerPage: -1 },
                }), */,
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

        /* selectProcessStates(data) {
                let processStateList = this.processStatesFilter
                data.forEach(function (item) {
                    processStateList.push(item)
                })
                return processStateList
            }, */

        /* selectTypeMovements(data) {
                let typeMovementList = this.typeMovements
                data.forEach(function (item) {
                    typeMovementList.push(item)
                })
                return typeMovementList
            }, */

        infoItem(item) {
            this.editedIndex = this.records.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogInfo = true;
        },

        /* editItem(item) {
                this.editedIndex = this.records.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true;
            }, */

        close() {
            this.dialog = false;
            this.isLoading = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
                this.editedItem.equipment_id.length = 0;
            });
        },

        /* async save() {
    
                if (this.editedItem.type_action_id == this.typeAction.find(item => item.id == 4)['name'] || this.editedItem.type_action_id == this.typeAction.find(item => item.id == 1)['name']) {
                    this.editedItem.state_id = this.processState.find(item => item.id == 2)['name'];
                }
                else {
                    this.editedItem.state_id = this.processState.find(item => item.id == 3)['name'];
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
            }, */

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
                this.isLoading = true;
                const { data } = await backendApi.delete(
                    `/historyChange/${this.editedItem.id}`,
                    {
                        params: {
                            id: this.editedItem.id,
                        },
                    }
                );
                alert.success(data.message);
            } catch (error) {
                this.closeDelete();
            } finally {
                setTimeout(() => (this.isLoading = false), 800);
                this.initialize();
                this.closeDelete();
            }
        },

        getDataFromApi() {
            this.loading = true;
            this.records = [];

            console.log("options: ", userLogged.name);
            //debounce
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                try {
                    const { data } = await backendApi.get(
                        `/userEquipment/${userLogged.name}`
                    );
                    this.records = data.data;
                    console.log("records: ", this.records);
                    this.equipment = data.tipo;
                    this.brand = data.marca;
                    this.model = data.model;
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
                const { data } = await backendApi.get("/historyChange", {
                    params: { filter: this.filterItem },
                });
                this.records = data.data;
            } catch (error) {
                alert.error("Ocurrió un error al generar el historial.");
            } finally {
                setTimeout(() => (this.isLoading = false), 800);
            }
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
