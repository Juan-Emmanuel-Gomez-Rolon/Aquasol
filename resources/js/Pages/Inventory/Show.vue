<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";

import { ref } from "vue";
import LabelValuePair from "./LabelValuePair.vue";
import Pencil from "@/Icons/Pencil.vue";

const updating = ref(false);

const errors = ref({});

const props = defineProps({
    csrf: String,
    product: {
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
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("es-MX");
};

const handleClick = (sale) => {
    // redirect to the sale detail page
    window.location.href = `/sales/${sale.id}`;
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
                                :href="`/products/${product.id}/edit`"
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
                            <LabelValuePair
                                label="Nombre del producto"
                                :value="product.name"
                            />
                            <LabelValuePair
                                label="Precio de venta"
                                :value="formatCurrency(product.price)"
                            />
                            <LabelValuePair
                                label="Cantidad en inventario"
                                :value="product.stock"
                            />
                            <LabelValuePair
                                label="Código de barras"
                                :value="product.barcode"
                            />
                            <LabelValuePair
                                class="col-span-2"
                                label="Descripción"
                                :value="product.description"
                            />
                            <LabelValuePair
                                label="Última venta"
                                :value="formatDate(product.last_sale)"
                            />
                            <LabelValuePair
                                label="Última entrada"
                                :value="formatDate(product.last_entry)"
                            />
                        </div>
                    </div>
                    <div
                        class="col-span-5 row-span-1 bg-gray-50 overflow-y-scroll max-h-[30vh]"
                    >
                        <div class="flex flex-col px-8 py-4 gap-4">
                            <h3 class="font-semibold text-xl text-gray-800">
                                Ventas más recientes
                            </h3>

                            <span
                                v-if="product.sales.length === 0"
                                class="text-gray-600"
                            >
                                No hay ventas registradas
                            </span>

                            <table
                                v-else
                                class="w-full shadow-lg rounded-lg overflow-hidden"
                            >
                                <thead>
                                    <tr class="bg-blue-300 text-left">
                                        <th class="py-2 px-3">Cliente</th>
                                        <th class="py-2 px-3">Vendedor</th>
                                        <th class="py-2 px-3">
                                            Cantidad vendida
                                        </th>
                                        <th class="py-2 px-3">
                                            Precio de venta
                                        </th>
                                        <th class="py-2 px-3">Total</th>
                                        <th class="py-2 px-3">
                                            Fecha de venta
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(sale, index) in product.sales"
                                        :key="sale.id"
                                        @click="handleClick(sale)"
                                        class="hover:bg-blue-500 hover:text-white cursor-pointer"
                                        :class="{
                                            'bg-blue-100': index % 2 === 0,
                                            'bg-blue-200': index % 2 !== 0,
                                        }"
                                    >
                                        <td class="py-2 px-3">
                                            {{ sale.client_name }}
                                        </td>
                                        <td class="py-2 px-3">
                                            {{ sale.user.name }}
                                        </td>
                                        <td class="py-2 px-3">
                                            {{ sale.pivot.quantity }}
                                        </td>
                                        <td class="py-2 px-3">
                                            {{
                                                formatCurrency(sale.pivot.price)
                                            }}
                                        </td>
                                        <td class="py-2 px-3">
                                            {{ formatCurrency(sale.total) }}
                                        </td>
                                        <td class="py-2 px-3">
                                            {{ formatDate(sale.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
