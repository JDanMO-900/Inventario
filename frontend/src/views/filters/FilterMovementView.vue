<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>
                    <v-col cols="12" lg="12" md="12" sm="12">
                        <BaseSelect label='Estados' :items="processStates" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.processState.$model" :rules="v$.editedItem.processState"
                            clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Tipo de equipo' :items="types" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.type.$model" :rules="v$.editedItem.type" clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Tipo de movimiento' :items="typeMovements" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.typeMovement.$model" :rules="v$.editedItem.typeMovement"
                            clearable>
                        </BaseSelect>
                    </v-col>

                    <!-- fecha del movimiento -->
                    <v-col cols="12" sm="6" md="6">
                        <base-input label="Rango inicial" v-model="v$.editedItem.start_date.$model"
                            :rules="v$.editedItem.start_date" type="datetime-local" clearable />
                    </v-col>
                    <!-- fecha del movimiento -->

                    <!-- fecha del movimiento -->
                    <v-col cols="12" sm="6" md="6">
                        <base-input label="Rango final" v-model="v$.editedItem.end_date.$model"
                            :rules="v$.editedItem.end_date" type="datetime-local" clearable />
                    </v-col>
                    <!-- fecha del movimiento -->

                    <v-col cols="12" lg="12" md="12" sm="12" class="d-flex flex-column align-center justify-center">
                        <base-button type="primary" title="Mostrar historial" @click="filterByMovement"
                            :loading="btnLoading" />
                    </v-col>
                </v-row>
            </v-container>
        </v-card>

        <v-card class="p-3 mt-3">
            <v-container>
                <v-col cols="12" sm="12" md="12" lg="12" xl="12" class="pl-0 pb-0 pr-0">
                    <v-text-field class="mt-3" variant="outlined" label="Buscar" type="text"
                        v-model="search"></v-text-field>
                </v-col>
            </v-container>

            <v-container>
                <v-row>
                    <v-progress-linear v-if="loading" indeterminate color="indigo-accent-3"></v-progress-linear>
                    <v-data-table :headers="headers" :items="records" item-key="name" class="elevation-1"
                        :search="search">
                        <template v-slot:no-data>
                            <v-icon @click="initialize" icon="mdi-refresh" />
                        </template>
                    </v-data-table>
                </v-row>
            </v-container>
        </v-card>
    </div>
</template>

<script>
import BaseSelect from "@/components/base-components/BaseSelect.vue";
import BaseButton from "@/components/base-components/BaseButton.vue";
import BaseInput from "@/components/base-components/BaseInput.vue";
import useAlert from "@/composables/useAlert";
import backendApi from "../../services/backendApi";
import { useVuelidate } from "@vuelidate/core";
import { minLength, required } from "@/lang/i18n";
const { alert } = useAlert();


export default {
    components: { BaseSelect, BaseButton, BaseInput },
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            search: "",
            loading: false,
            title: 'Movimientos de inventario',
            processStates: [
                { id: -1, name: "TODOS LOS ESTADOS" }
            ],
            types: [
                { id: -1, name: 'TODOS LOS TIPOS' }
            ],
            typeMovements: [
                { id: -1, name: 'TODOS LOS MOVIMIENTOS' }
            ],
            editedItem: {
                typeMovement: '',
                type: '',
                processState: '',
                start_date: "",
                end_date: "",
            },
            headers: [
                { title: 'Fecha', key: 'start_date'},
                { title: 'Equipo', key: 'type'},
                { title: 'Movimiento', key: 'action_name'},
                { title: "Dependencia", key: "dependency" },
                { title: "Ubicación", key: "location" },
                { title: "# de activo", key: "number_active" },
                { title: "Serial", key: "serial_number" }
            ],
            records: [],

            btnLoading: false
        }
    },
    validations() {
        return {
            editedItem: {
                typeMovement: { required, minLength: minLength(1), },
                type: { required, minLength: minLength(1), },
                processState: { required, minLength: minLength(1), },
                start_date: {
                    required,
                    minLength: minLength(1),

                },
                end_date: {
                    required,
                    minLength: minLength(1)
                },
            }
        }
    },
    created() {
        this.initialize()
    },
    methods: {
        async initialize() {
            let requests = [
                backendApi.get('/processState', {
                    params: { itemsPerPage: -1 },
                }),
                backendApi.get('/equipmentType', {
                    params: { itemsPerPage: -1 },
                }),
                backendApi.get('/typeAction', {
                    params: { itemsPerPage: -1 },
                }),
            ];

            const responses = await Promise.all(requests).catch((error) => {
                alert.error("No fue posible obtener el registro.")
            });

            if (responses) {
                this.processStates = this.selectProcessStates(responses[0].data.data);
                this.types = this.selectTypes(responses[1].data.data)
                this.typeMovements = this.selectTypeMovements(responses[2].data.data)
            }
        },
        selectProcessStates(data) {
            let processStateList = this.processStates
            data.forEach(function (item) {
                processStateList.push(item)
            })
            return processStateList
        },
        selectTypes(data) {
            let typesList = this.types
            data.forEach(function (item) {
                typesList.push(item)
            })
            return typesList
        },
        selectTypeMovements(data) {
            let typeMovementList = this.typeMovements
            data.forEach(function (item) {
                typeMovementList.push(item)
            })
            return typeMovementList
        },
        async filterByMovement() {
            this.loading = true;
            this.btnLoading = true
            this.records = [];
            this.v$.editedItem.$validate();
            if (this.v$.editedItem.$invalid) {
                alert.error("Campos obligatorios");
                this.loading = false;
                this.btnLoading = false
                return;
            }

            try {
                const { data } = await backendApi.get('/getFilterMovement', {
                    params: { search: this.editedItem },
                });
                this.records = data.data;
                this.btnLoading = false
                this.loading = false;
     
            } catch (error) {
                alert.error("Ocurrió un error al generar el historial.");
                this.btnLoading = false
                this.loading = false;
            }
        }
    }
}
</script>