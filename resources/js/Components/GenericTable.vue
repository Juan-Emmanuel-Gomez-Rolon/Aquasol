<script setup>
import PaginationButtons from "./PaginationButtons.vue";
const props = defineProps({
    headers: {
        required: true,
    },
    data: {
        required: true,
    },
    clickable: {
        required: false,
        default: false,
    },
    callback: {
        required: false,
    },
    custom_missing: {
        required: false,
        default: "No se encontraron elementos.",
    },
});

const formatData = (header, obj) => {
    if (header.sub && obj[header.value]) {
        return obj[header.value][header.sub] ?? "Sin asignar";
    } // if looks like a float, show it with 2 decimals
    else if (typeof obj[header.value] === "number") {
        if (Number.isInteger(obj[header.value])) {
            return obj[header.value];
        } else {
            return obj[header.value].toFixed(2);
        }
    } else if (header.date) {
        let date = new Date(obj[header.value]);
        // show date as yyyy-mm-dd hh:mm:ss (x ago)
        return date.toLocaleString("es-ES", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        });
    } else if (header.count) {
        return obj[header.value]?.length;
    } else if (header.currency) {
        let formatter = new Intl.NumberFormat("es-MX", {
            style: "currency",
            currency: "MXN",
        });
        return formatter.format(obj[header.value]);
    } else {
        return obj[header.value];
    }
};
</script>

<template>
    <!-- Show a message if there are no elements to show -->
    <div v-if="data.data.length === 0" class="p-4">
        <p class="text-center text-gray-500">
            {{ custom_missing }}
        </p>
    </div>
    <div class="overflow-x-auto" v-else>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th
                        class="py-2 px-4 bg-blue-300 font-semibold border-b"
                        v-for="(header, index) in headers"
                    >
                        {{ header.text }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(obj, index) in props.data.data"
                    :key="obj.id"
                    :class="{
                        'border-b cursor-pointer': true,
                        'bg-blue-200 text-gray-700': index % 2 === 0,
                        'hover:bg-blue-500 hover:text-white': true,
                    }"
                    @click="props.clickable ? props.callback(obj) : null"
                >
                    <td
                        class="font-bold py-2 px-4 text-gray-700 text-center"
                        v-for="(header, index) in headers"
                    >
                        <span> {{ formatData(header, obj) }} </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <PaginationButtons
            :links="data.links"
            :current_page="data.current_page"
            :prev_page_url="data.prev_page_url"
            :next_page_url="data.next_page_url"
        />
    </div>
</template>
