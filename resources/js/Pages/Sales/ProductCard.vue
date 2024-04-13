<script setup>
const props = defineProps({
    product: Object,
    cancelCallback: Function,
});
</script>

<template>
    <div
        class="bg-blue-100 border-blue-200 border-2 border-b shadow-md p-1 w-full hover:bg-blue-300"
    >
        <a
            class="flex justify-between items-center w-full"
            :href="`/products/${product.id}`"
        >
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
                        Stock: {{ product.stock }} - ${{ product.pivot.price }}
                        c/u
                    </span>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="flex flex-col gap-2">
                    <label>Cantidad</label>
                    <p class="text-lg font-semibold">
                        {{ product.pivot.quantity }}
                    </p>
                </div>
                <div class="flex flex-col gap-2">
                    <label>Subtotal</label>
                    <p class="text-lg font-semibold mr-4 text-blue-900">
                        $
                        {{
                            (product.pivot.price * product.pivot.quantity)
                                .toFixed(2)
                                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }}
                    </p>
                </div>
                <!-- <button
                    class="ml-4 bg-red-500 text-white px-4 py-2 rounded-full"
                    @click="cancelCallback(product)"
                >
                    X
                </button> -->
            </div>
        </a>
    </div>
</template>
