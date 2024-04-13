<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";

import { ref } from "vue";
import Pencil from "@/Icons/Pencil.vue";

const updating = ref(false);
const errors = ref({});

const props = defineProps({
    csrf: String,
    product: {
        required: true,
    },
});

const updateProduct = async () => {
    updating.value = true;
    errors.value = {};

    let s_product = { ...props.product, _token: props.csrf };

    fetch(`/api/products/${s_product.id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(s_product),
    })
        .then((response) => {
            updating.value = false;
            return response.json(); // Parse response as JSON
        })
        .then((data) => {
            // Handle both success and error responses
            if (data.status === "success") {
                // redirect to the product detail page
                window.location.href = `/products/${s_product.id}`;
            } else {
                let unpackedErrors = {};
                for (let key in data.errors) {
                    unpackedErrors[key] = data.errors[key][0];
                }
                errors.value = unpackedErrors;
                console.log("errors", errors.value);
            }
        });
};

const formatCurrency = (value) => {
    let formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
};

const formatDate = (date) => {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("es-MX");
};
</script>

<template>
    <AppLayout title="Detalles de producto">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ventas
            </h2>
        </template> -->

        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lgw-full">
                <!-- Items list 3/5 width and 5/5 height -->
                <div
                    class="grid grid-cols-5 grid-rows-1 shadow-xl sm:rounded-lg h-[90dvh] w-full"
                >
                    <div
                        class="col-span-1 row-span-1 bg-blue-100 border-blue-200 border-2 shadow-lg p-4"
                    >
                        <div class="flex flex-col gap-4 pt-2">
                            <a
                                class="flex justify-between items-center"
                                :href="`/products/${product.id}`"
                            >
                                <h3 class="font-bold text-xl text-gray-800">
                                    Producto {{ product.id }}
                                </h3>
                                <!-- Edit button -->
                                <Pencil class="w-6 h-6 text-blue-600" />
                            </a>
                            <!-- <div class="flex justify-center items-center">
                                <img :src="product.image" alt="product image" />
                            </div> -->
                        </div>
                    </div>
                    <div
                        class="col-span-4 row-span-1 border-blue-200 border-t-2 border-r-2 border-b-2"
                    >
                        <div class="grid grid-cols-2 px-8 py-4 gap-4">
                            <LabelInputPair
                                label="Nombre del producto"
                                v-model="product.name"
                                :error="errors.name"
                            />
                            <LabelInputPair
                                label="Precio de venta"
                                v-model="product.price"
                                :error="errors.price"
                            />
                            <LabelInputPair
                                label="Cantidad en inventario"
                                disabled
                                v-model="product.stock"
                            />
                            <LabelInputPair
                                label="Código de barras"
                                v-model="product.barcode"
                                :error="errors.barcode"
                            />
                            <LabelInputPair
                                class="col-span-2"
                                label="Descripción"
                                v-model="product.description"
                                :error="errors.description"
                            />
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex justify-center items-center gap-4"
                                :class="{
                                    'opacity-50 cursor-wait': updating,
                                    ' cursor-pointer': !updating,
                                }"
                                @click="updateProduct"
                                :disabled="updating"
                            >
                                <span v-if="!updating">Guardar cambios</span>
                                <span v-else>Guardando...</span>
                                <InlineSpinner :active="updating" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
