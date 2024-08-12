<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>
                    <v-col cols="12" sm="6" md="6">
                        <base-input label="Fecha de ingreso (Desde)" v-model="v$.editedItem.start_date.$model"
                            :rules="v$.editedItem.start_date" type="date" clearable />
                    </v-col>

                    <v-col cols="12" sm="6" md="6">
                        <base-input label="Fecha de ingreso (Hasta)" v-model="v$.editedItem.end_date.$model"
                            :rules="v$.editedItem.end_date" type="date" clearable />
                    </v-col>

                    <v-col cols="12" lg="12" md="12" sm="12" class="d-flex flex-column align-center justify-center">
                        <base-button type="primary" title="Generar reporte" @click="generateReport" :loading="btnLoading" />
                    </v-col>
                </v-row>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import BaseInput from "@/components/base-components/BaseInput.vue";
import BaseButton from "@/components/base-components/BaseButton.vue";
import useAlert from "@/composables/useAlert";
import backendApi from "../../services/backendApi";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@/lang/i18n";
const { alert } = useAlert();

export default {
    components: { BaseInput, BaseButton },
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            title: 'Reporte de equipos en bodega',
            editedItem: {
                start_date: '',
                end_date: ''
            },
            defaultItem: {
                start_date: '',
                end_date: ''
            },
            btnLoading: false
        }
    },
    validations() {
        return {
            editedItem: {
                start_date: { required },
                end_date: { required }
            }
        }
    },
    methods: {
        async generateReport() {
            this.v$.editedItem.$validate();
            if (this.v$.editedItem.$invalid) {
                alert.error("Campos obligatorios");
                return;
            }

            try {
                this.btnLoading = true;
                const reportData = await backendApi.post(`/availableEquipment`, this.editedItem, { responseType: 'blob' });
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