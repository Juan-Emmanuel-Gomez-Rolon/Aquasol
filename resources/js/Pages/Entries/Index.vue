<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";

const props = defineProps({
    csrf: String,
    entries: Object,
});

const handleClick = (sale) => {
    // redirect to the sale detail page
    window.location.href = `/entries/${sale.id}`;
};
</script>

<template>
    <AppLayout title="Entradas a inventario">
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
                            Entradas a inventario
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
                            { text: 'Encargado', value: 'user', sub: 'name' },
                            {
                                text: 'Productos únicos agregados',
                                value: 'products',
                                count: true,
                            },

                            {
                                text: 'Fecha de operación',
                                value: 'created_at',
                                date: true,
                            },
                        ]"
                        :data="entries"
                        :clickable="true"
                        :callback="handleClick"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
