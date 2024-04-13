<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";
import ProductCard from "./ProductCard.vue";

import { ref } from "vue";

const updating = ref(false);

const errors = ref({});

const props = defineProps({
    csrf: String,
    entry: {
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

const updateentry = () => {
    if (updating.value) {
        return;
    }
    updating.value = true;

    setTimeout(() => {
        let entry = props.entry;
        entry._token = props.csrf;
        fetch(`/entries/${props.entry.id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(entry),
        })
            .then((response) => response.json())
            .then((data) => {
                updating.value = false;
                if (data.status && data.status === "success") {
                    errors.value = {};
                } else {
                    let unpackedErrors = {};
                    for (let key in data.errors) {
                        unpackedErrors[key] = data.errors[key][0];
                    }
                    errors.value = unpackedErrors;
                }
            })
            .catch((error) => {
                updating.value = false;
                console.error("Error:", error);
            });
    }, 500);
};
</script>

<template>
    <AppLayout title="Resumen de entrada">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Entradas
            </h2>
        </template> -->

        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lgw-full">
                <!-- Items list 3/5 width and 5/5 height -->
                <div class="bg-blue-50 block border-2 rounded-lg p-4">
                    <h3
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Entrada {{ entry.id }}
                    </h3>
                    <div class="mt-4">
                        <h4 class="font-semibold text-lg text-gray-800 mb-4">
                            Detalles de la entrada
                        </h4>
                        <div class="flex w-full gap-2">
                            <div class="w-1/2">
                                <div class="font-semibold text-gray-800">
                                    Responsable
                                </div>
                                <div>{{ entry.user.name }}</div>
                            </div>
                            <div class="w-1/2">
                                <div class="font-semibold text-gray-800">
                                    Fecha
                                </div>
                                <div>{{ formatDate(entry.created_at) }}</div>
                            </div>
                        </div>

                        <div>
                            <h4
                                class="font-semibold text-lg text-gray-800 mb-3 mt-8"
                            >
                                Productos ingresados
                            </h4>
                            <div
                                v-for="product in entry.products"
                                :key="product.id"
                                class="flex w-full gap-2"
                            >
                                <ProductCard :product="product" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
