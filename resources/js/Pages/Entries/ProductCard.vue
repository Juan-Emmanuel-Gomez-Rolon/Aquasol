<script setup>
const props = defineProps({
    product: Object,
    cancelCallback: Function,
    readonly: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div
        class="bg-blue-100 border-blue-200 border-2 border-b shadow-md p-1 w-full"
    >
        <div class="flex justify-between items-center w-full">
            <div class="flex items-center">
                <!-- <img
                    :src="`/storage/${product.image}`"
                    alt="product image"
                    class="w-16 h-16 object-cover "
                /> -->
                <div class="ml-4">
                    <div class="flex flex-row items-center gap-4">
                        <h2 class="text-lg font-semibold text-blue-900">
                            {{ product.name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            #{{ product.barcode }}
                        </p>
                    </div>
                    <span class="text-sm text-gray-500">
                        Stock actual: {{ product.stock }} - ${{ product.price }}
                        c/u
                    </span>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="flex flex-col gap-2" v-if="!readonly">
                    <label>Cantidad a ingresar</label>
                    <input
                        type="number"
                        class="p-2 border-2 border-gray-300 rounded-lg"
                        v-model="product.purchase_quantity"
                    />
                </div>
                <div class="flex flex-col gap-2 px-4" v-else>
                    <label>Cantidad ingresada</label>
                    <p class="text-lg font-semibold mr-4 text-blue-900">
                        {{ product.pivot.quantity }}
                    </p>
                </div>
                <button
                    class="text-red-500"
                    @click="cancelCallback(product)"
                    v-if="cancelCallback"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
