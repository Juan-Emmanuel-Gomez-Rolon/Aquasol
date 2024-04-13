<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";

const props = defineProps({
    csrf: String,
    products: Object,
});

const handleClick = (sale) => {
    // redirect to the sale detail page
    window.location.href = `/products/${sale.id}`;
};
</script>

<template>
    <AppLayout title="Inventario">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ventas
            </h2>
        </template> -->

        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lgw-full">
                <!-- Items list 3/5 width and 5/5 height -->
                <div class="bg-blue-50 block border-2 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="font-semibold text-xl text-gray-800">
                            Inventario
                        </h3>
                        <div class="flex gap-4">
                            <a
                                href="/products/create"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Registrar nuevo producto
                            </a>
                            <a
                                :href="route('entries.create')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Ingreso de productos
                            </a>
                        </div>
                    </div>
                    <GenericTable
                        :headers="[
                            { text: 'id', value: 'id' },
                            { text: 'Nombre', value: 'name' },
                            { text: 'Código de barras', value: 'barcode' },
                            { text: 'Stock', value: 'stock' },
                            { text: 'Última entrada', value: 'last_entry' },
                            { text: 'Última venta', value: 'last_sale' },

                            {
                                text: 'Última actualización',
                                value: 'updated_at',
                                date: true,
                            },
                        ]"
                        :data="products"
                        :clickable="true"
                        :callback="handleClick"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
