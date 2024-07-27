<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>

                    <v-col cols="12" lg="12" md="12" sm="12">
                        <BaseSelect label='Ubicación' :items="locations" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.location.$model" :rules="v$.editedItem.location" clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Tipo de equipo' :items="types" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.type.$model" :rules="v$.editedItem.type" clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect label='Marca' :items="brands" item-title='name' item-value="id"
                            v-model.trim="v$.editedItem.brand.$model" :rules="v$.editedItem.brand" clearable>
                        </BaseSelect>
                    </v-col>
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
import useAlert from "@/composables/useAlert";
import backendApi from "../../services/backendApi";
import { useVuelidate } from "@vuelidate/core";
import { minLength, required } from "@/lang/i18n";
const { alert } = useAlert();


export default {
    components: { BaseSelect, BaseButton },
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            title: 'Reporte de equipos',
            locations: [
                { id: -1, name: "TODAS LAS UBICACIONES" }
            ],
            types: [
                { id: -1, name: 'TODOS LOS TIPOS' }
            ],
            brands: [
                { id: -1, name: 'TODAS LAS MARCAS' }
            ],
            editedItem: {
                brand: '',
                type: '',
                location: '',
            },
            defaultItem: {
                brand: '',
                type: '',
                location: '',
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
            }
        }
    },
    created() {
        this.initialize()
    },
    methods: {
        async initialize() {
            let requests = [
                backendApi.get('/location', {
                    params: { itemsPerPage: -1 },
                }),
                backendApi.get('/equipmentType', {
                    params: { itemsPerPage: -1 },
                }),
                backendApi.get('/brand', {
                    params: { itemsPerPage: -1 },
                })
            ];

            const responses = await Promise.all(requests).catch((error) => {
                alert.error("No fue posible obtener el registro.")
            });

            if (responses) {
                this.locations = this.selectLocations(responses[0].data.data);
                this.types = this.selectTypes(responses[1].data.data)
                this.brands = this.selectBrands(responses[2].data.data)
            }
        },
        selectLocations(data) {
            let locationList = this.locations
            data.forEach(function(item) {
                locationList.push(item)
            })
            return locationList
        },
        selectTypes(data) {
            let typesList = this.types
            data.forEach(function (item) {
                typesList.push(item)
            })
            return typesList
        },
        selectBrands(data) {
            let brandList = this.brands
            data.forEach(function (item) {
                brandList.push(item)
            })
            return brandList
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