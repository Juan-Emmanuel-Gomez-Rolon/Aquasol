<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";

import Notifier from "@/notifier";
const notifier = new Notifier();

import { ref } from "vue";
import Pencil from "@/Icons/Pencil.vue";

const loading = ref(false);
const errors = ref({});

const props = defineProps({
    csrf: String,
});

const product = ref({
    id: 0,
    name: "",
    price: "",
    stock: "",
    barcode: "",
    description: "",
    image: "https://via.placeholder.com/150",
    _token: props.csrf,
});

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

const submitForm = () => {
    loading.value = true;

    fetch("/api/products", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(product.value),
    })
        .then((response) => {
            loading.value = false;
            return response.json(); // Parse response as JSON
        })
        .then((data) => {
            // Handle both success and error responses
            if (data.status === "success") {
                notifier.notify({
                    message: "El producto ha sido registrado exitosamente.",
                    type: "success",
                });
                // redirect to the product detail page
                // window.location.href = `/products/${data.product.id}`;
                clearForm();
            } else {
                notifier.notify({
                    message: "Ocurrió un error al registrar el producto.",
                    type: "error",
                });
                let unpackedErrors = {};
                for (let key in data.errors) {
                    unpackedErrors[key] = data.errors[key][0];
                }
                errors.value = unpackedErrors;
                // console.log("errors", errors.value);
            }
        });
};

const clearForm = () => {
    errors.value = {};
    product.value = {
        id: 0,
        name: "",
        price: "",
        stock: "",
        barcode: "",
        description: "",
        image: "https://via.placeholder.com/150",
        _token: props.csrf,
    };
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
                            <a class="flex justify-between items-center">
                                <h3 class="font-bold text-xl text-gray-800">
                                    Nuevo producto
                                </h3>
                                <!-- Edit button -->
                            </a>
                        </div>
                    </div>
                    <div
                        class="col-span-4 row-span-1 border-blue-200 border-t-2 border-r-2 border-b-2"
                    >
                        <form
                            class="grid grid-cols-2 px-8 py-4 gap-4"
                            action="/products"
                            method="POST"
                            @submit.prevent="submitForm"
                        >
                            <LabelInputPair
                                label="Nombre del producto"
                                placeholder="Pastillas de cloro"
                                v-model="product.name"
                                :error="errors.name"
                                required
                            />
                            <LabelInputPair
                                label="Precio de venta"
                                v-model="product.price"
                                placeholder="100.00"
                                :error="errors.price"
                                required
                            />
                            <LabelInputPair
                                label="Cantidad en inventario"
                                placeholder="15"
                                v-model="product.stock"
                                :error="errors.stock"
                                required
                            />
                            <LabelInputPair
                                label="Código de barras"
                                v-model="product.barcode"
                                placeholder="#########"
                                :error="errors.barcode"
                                required
                            />
                            <LabelInputPair
                                class="col-span-2"
                                label="Descripción"
                                placeholder="Pastillas de cloro para piscina, 1kg."
                                v-model="product.description"
                                :error="errors.description"
                                required
                            />
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex justify-center items-center gap-4"
                                :class="{
                                    'opacity-50 cursor-wait': loading,
                                    ' cursor-pointer': !loading,
                                }"
                                @click="updateProduct"
                                :disabled="loading"
                            >
                                <span v-if="!loading"
                                    >Registrar nuevo producto</span
                                >
                                <span v-else>Guardando...</span>
                                <InlineSpinner :active="loading" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
