<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";
import ProductCard from "./ProductCard.vue";

import Notifier from "@/notifier";
const notifier = new Notifier();

import { ref } from "vue";

const updating = ref(false);

const errors = ref({});

const props = defineProps({
    csrf: String,
    sale: {
        required: true,
    },
});

const formatCurrency = (value) => {
    let formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-MX");
};

const updateSale = () => {
    if (updating.value) {
        return;
    }
    updating.value = true;

    setTimeout(() => {
        let sale = props.sale;
        sale._token = props.csrf;
        fetch(`/sales/${props.sale.id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(sale),
        })
            .then((response) => response.json())
            .then((data) => {
                updating.value = false;
                if (data.status && data.status === "success") {
                    notifier.notify({
                        type: "success",
                        message: "Los cambios se guardaron correctamente",
                    });
                    errors.value = {};
                } else {
                    notifier.notify({
                        type: "error",
                        message: "Hubo un error al guardar los cambios",
                    });
                    let unpackedErrors = {};
                    for (let key in data.errors) {
                        unpackedErrors[key] = data.errors[key][0];
                    }
                    errors.value = unpackedErrors;
                }
            })
            .catch((error) => {
                notifier.notify({
                    type: "error",
                    message: "Hubo un error al guardar los cambios",
                });
                updating.value = false;
                console.error("Error:", error);
            });
    }, 500);
};
</script>

<template>
    <AppLayout title="Resumen de venta">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ventas
            </h2>
        </template> -->

        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lgw-full">
                <!-- Items list 3/5 width and 5/5 height -->
                <div class="bg-blue-50 block border-2 rounded-lg p-4">
                    <h3
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Venta {{ sale.id }}
                    </h3>
                    <div class="mt-4">
                        <h4 class="font-semibold text-lg text-gray-800">
                            Detalles de la venta
                        </h4>
                        <div class="flex w-full gap-2">
                            <div class="w-1/2">
                                <div class="font-semibold text-gray-800">
                                    Vendedor
                                </div>
                                <div>{{ sale.user.name }}</div>
                            </div>
                            <div class="w-1/2">
                                <div class="font-semibold text-gray-800">
                                    Fecha
                                </div>
                                <div>{{ formatDate(sale.created_at) }}</div>
                            </div>
                            <div class="w-1/2">
                                <div>Total</div>
                                <div
                                    class="text-xl font-semibold text-gray-800"
                                >
                                    {{ formatCurrency(sale.total) }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="font-semibold text-lg text-gray-800 mt-8 mb-3"
                            >
                                Datos del cliente
                            </h4>
                            <div class="flex w-full gap-2">
                                <LabelInputPair
                                    label="Nombre"
                                    class="w-1/2"
                                    v-model="sale.client_name"
                                    @keyup.enter="updateSale"
                                    :error="errors['client_name']"
                                />
                                <LabelInputPair
                                    label="Email"
                                    class="w-1/2"
                                    v-model="sale.client_email"
                                    @keyup.enter="updateSale"
                                    :error="errors['client_email']"
                                />
                            </div>
                            <div class="flex w-full gap-2">
                                <LabelInputPair
                                    label="Teléfono"
                                    class="w-1/2"
                                    v-model="sale.client_phone"
                                    @keyup.enter="updateSale"
                                    :error="errors['client_phone']"
                                />
                                <LabelInputPair
                                    label="Dirección"
                                    class="w-1/2"
                                    v-model="sale.client_address"
                                    @keyup.enter="updateSale"
                                    :error="errors['client_address']"
                                />
                            </div>
                            <div class="flex w-full justify-end gap-2">
                                <div>
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-full mt-4 flex items-center gap-2"
                                        @click="updateSale"
                                    >
                                        <span v-if="!updating"
                                            >Guardar cambios</span
                                        >
                                        <span v-else>Guardando...</span>
                                        <InlineSpinner :active="updating" />
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="font-semibold text-lg text-gray-800 mb-3 mt-8"
                            >
                                Productos
                            </h4>
                            <div
                                v-for="product in sale.products"
                                :key="product.id"
                                class="flex w-full gap-2"
                            >
                                <ProductCard
                                    :product="product"
                                    :cancelCallback="() => {}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
