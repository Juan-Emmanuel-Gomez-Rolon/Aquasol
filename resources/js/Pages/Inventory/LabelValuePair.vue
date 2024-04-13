<script setup>
const props = defineProps({
    label: {
        required: true,
    },
    value: {
        required: true,
    },
});
</script>

<template>
    <div
        class="flex flex-col gap-2 bg-blue-50 p-4 border-blue-100 border-2 rounded-md shadow-md"
    >
        <div class="flex gap-1 font-semibold">
            <label>{{ label }}</label>
        </div>

        <div class="flex flex-wrap gap-1 max-w-xl text-justify">
            <span>{{ value }}</span>
        </div>
    </div>
</template>
