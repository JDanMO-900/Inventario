<template>
    <div data-app>
        <v-card class="p-3 mt-3">
            <v-container>
                <h2>{{ title }}</h2>
                <v-row>
                    <v-col cols="12" lg="6" md="8" sm="12">
                        <BaseSelect 
                        label='Marca'
                        :items="brands" 
                        item-title='name'
                        v-model.trim="v$.editedItem.brand.$model" 
                        :rules="v$.editedItem.brand"
                        clearable>
                        </BaseSelect>
                    </v-col>
                    <v-col cols="12" lg="3" md="4" sm="12">
                        <base-button type="primary" title="Generar reporte" class="mt-4 mb-3" block/>
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
            title: 'Reporte general de inventario',
            brands: [
                { id: null, name: 'TODAS LAS MARCAS'}
            ],
            editedItem: {
                brand: '',
            },
            defaultItem: {
                brand: '',
            },
        }
    },
    validations() {
        return {
            editedItem: {
                brand: { required },
            }
        }
    },
    created() {
        this.initialize()
    },
    methods: {
        async initialize() {
            let requests = [
            backendApi.get('/brand', {
                params: { itemsPerPage: -1 },
            })
            ];

            const responses = await Promise.all(requests).catch((error) => {
                alert.error("No fue posible obtener el registro.")
            });

            if (responses) {
                this.brands = this.selectBrands(responses[0].data.data)
            }
        },
        selectBrands(data) {
            let brandList = this.brands
            data.forEach(function(item) {
                brandList.push(item)
            })
            return brandList
        }
        
    }
}
</script>