<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="'/'">
        <div
            class="flex items-center justify-center p-4 rounded-lg bg-secondary"
        >
            <img src="/images/aquasol.png" class="w-50" alt="Logo" />
        </div>
    </Link>
</template>
