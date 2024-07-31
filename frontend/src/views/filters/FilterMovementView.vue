<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>

                    <v-col cols="12" lg="12" md="12" sm="12">
                        <BaseSelect label='Estados' :items="processStates" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.location.$model" :rules="v$.editedItem.location" clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Tipo de equipo' :items="types" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.type.$model" :rules="v$.editedItem.type" clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Tipo de movimiento' :items="typeMovements" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.brand.$model" :rules="v$.editedItem.brand" clearable>
                        </BaseSelect>
                    </v-col>

                    <!-- fecha del movimiento -->
                    <v-col cols="12" sm="12" md="12">
                        <base-input label="Fecha" v-model="v$.editedItem.start_date.$model"
                            :rules="v$.editedItem.start_date" type="date" clearable />
                    </v-col>
                    <!-- fecha del movimiento -->


                    <!-- fecha del movimiento -->
                    <v-col cols="12" sm="12" md="12">
                        <base-input label="Fecha" v-model="v$.editedItem.end_date.$model"
                            :rules="v$.editedItem.end_date" type="date" clearable />
                    </v-col>
                    <!-- fecha del movimiento -->


                    <v-col cols="12" lg="12" md="12" sm="12" class="d-flex flex-column align-center justify-center">
                        <base-button type="primary" title="Generar reporte" @click="generateReport"
                            :loading="btnLoading" />
                    </v-col>
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
            title: 'Reporte de equipos',
            processStates: [
                { id: -1, name: "TODAS LOS ESTADOS" }
            ],
            types: [
                { id: -1, name: 'TODOS LOS TIPOS' }
            ],
            typeMovements: [
                { id: -1, name: 'TODOS LOS MOVIMIENTOS' }
            ],
            editedItem: {
                brand: '',
                type: '',
                location: '',
                start_date: "",
                end_date: "",
            },
            defaultItem: {
                brand: '',
                type: '',
                location: '',
                start_date: "",
                end_date: "",
            },
            btnLoading: false
        }
    },
    validations() {
        return {
            editedItem: {
                brand: { required, minLength: minLength(1), },
                type: { required, minLength: minLength(1), },
                location: { required, minLength: minLength(1), },
                start_date: {

                    minLength: minLength(1),

                },
                end_date: {
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
        async generateReport() {
            this.v$.editedItem.$validate();
            if (this.v$.editedItem.$invalid) {
                alert.error("Campos obligatorios");
                return;
            }

            try {
                this.btnLoading = true;
                const reportData = await backendApi.post(`/typepdf`, this.editedItem, { responseType: 'blob' });
                console.log(this.editedItem)
                const report_data = new Blob([reportData.data], { type: 'application/pdf' })
                const url_report = window.URL.createObjectURL(report_data);
                window.open(url_report);
                this.btnLoading = false
            } catch (error) {
                alert.error("Ocurrió un error al generar el reporte.");
                this.btnLoading = false
            }
        }
    }
}
</script>