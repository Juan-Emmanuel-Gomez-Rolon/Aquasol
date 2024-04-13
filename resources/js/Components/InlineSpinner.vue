<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    active: {
        required: true,
    },
});

const showCheck = ref(false);

// when active goes from true to false, show the checkmark for a second
watch(
    () => props.active,
    (newVal, oldVal) => {
        if (oldVal === true && newVal === false) {
            showCheck.value = true;
            setTimeout(() => {
                showCheck.value = false;
            }, 2000);
        }
    }
);
</script>

<template>
    <div v-if="active" class="inline-spinner">
        <div class="spinner"></div>
    </div>
    <div v-else-if="showCheck" class="inline-spinner">
        <svg
            class="w-4 h-4 text-green-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
            ></path>
        </svg>
    </div>
</template>

<style scoped>
.inline-spinner {
    display: inline-block;
}

.spinner {
    border: 2px solid rgba(0, 0, 0, 0.6);
    border-left: 2px solid rgb(255, 255, 255);
    border-radius: 50%;
    width: 16px;
    height: 16px;

    animation: spin 0.6s ease infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
