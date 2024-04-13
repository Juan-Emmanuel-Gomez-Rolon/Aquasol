<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    label: String,
    modelValue: {
        required: true,
    },
    required: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: "",
    },
    error: {
        type: String,
        default: "",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: "text",
    },
    options: {
        type: Array,
        default: () => [],
    },
    autocomplete: {
        type: String,
        default: "off",
    },
});

const emits = defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex gap-1">
            <label>{{ label }}</label>
            <span class="text-red-500">
                {{ required ? "*" : "" }}
            </span>
        </div>
        <input
            v-if="
                type === 'text' ||
                type === 'password' ||
                type === 'email' ||
                type === 'number'
            "
            :type="type"
            :placeholder="placeholder"
            class="border-2 border-gray-300 p-2 rounded-md text-black placeholder:text-gray-300"
            :class="{ 'border-red-500': error, 'bg-gray-200': disabled }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :required="required"
            :disabled="disabled"
            :autocomplete="autocomplete"
        />
        <select
            v-else-if="type === 'select'"
            class="border-2 border-gray-300 p-2 rounded-md text-black placeholder:text-gray-300"
            :class="{ 'border-red-500': error, 'bg-gray-200': disabled }"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :required="required"
            :disabled="disabled"
        >
            <option
                v-for="(option, index) in options"
                :key="index"
                :value="option"
            >
                {{ option }}
            </option>
        </select>
        <span class="text-red-500">{{ error }}</span>
    </div>
</template>
