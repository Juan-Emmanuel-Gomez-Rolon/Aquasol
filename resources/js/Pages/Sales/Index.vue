<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GenericTable from "@/Components/GenericTable.vue";

const props = defineProps({
    csrf: String,
    sales: Object,
});

const handleClick = (sale) => {
    // redirect to the sale detail page
    window.location.href = `/sales/${sale.id}`;
};
</script>

<template>
    <AppLayout title="Ventas">
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
                        class="font-semibold text-xl text-gray-800 leading-tight mb-8"
                    >
                        Ventas
                    </h3>
                    <GenericTable
                        :headers="[
                            { text: 'id', value: 'id' },
                            { text: 'Cliente', value: 'client_name' },
                            { text: 'Vendedor', value: 'user', sub: 'name' },
                            { text: 'Total', value: 'total', currency: true },
                            {
                                text: 'Última actualización',
                                value: 'updated_at',
                                date: true,
                            },
                        ]"
                        :data="sales"
                        :clickable="true"
                        :callback="handleClick"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
