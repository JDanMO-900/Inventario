<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>
                    <v-col cols="12" lg="12" md="12" sm="12">
                        <BaseSelect 
                        label='Ubicación'
                        :items="locations" 
                        item-title='name'
                        v-model.trim="v$.editedItem.location.$model" 
                        :rules="v$.editedItem.location"
                        clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect 
                        label='Tipo de equipo'
                        :items="types" 
                        item-title='name'
                        v-model.trim="v$.editedItem.type.$model" 
                        :rules="v$.editedItem.type"
                        clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="6" md="6" sm="12">
                        <BaseSelect 
                        label='Marca'
                        :items="brands" 
                        item-title='name'
                        v-model.trim="v$.editedItem.brand.$model" 
                        :rules="v$.editedItem.brand"
                        clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="12" md="12" sm="12" class="d-flex flex-column align-center justify-center">
                        <base-button type="primary" title="Generar reporte"/>
                    </v-col>                    
                </v-row>
            </v-container>
        </v-card>
    </div>
</template>
<script>
import BaseSelect from "@/components/base-components/BaseSelect.vue";
import BaseButton from "@/components/base-components/BaseButton.vue";
import backendApi from "../../services/backendApi";
import { useVuelidate } from "@vuelidate/core";
import { required } from "@/lang/i18n";


export default {
    components: { BaseSelect, BaseButton },
    setup() {
        return { v$: useVuelidate() }
    },
    data() {
        return {
            title: 'Reporte de equipos por ubicación',
            locations: [],
            types: [],
            brands: [],
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
        }
    },
    validations() {
        return {
            editedItem: {
                brand: { required },
                type: { required },
                location: { required },
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
                this.locations = responses[0].data.data
                this.types = responses[1].data.data
                this.brands = responses[2].data.data
            }
        },
    }
}
</script>