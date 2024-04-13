<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import ProductCard from "./ProductCard.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";

import Notifier from "@/notifier";
const notifier = new Notifier();

const props = defineProps({
    csrf: String,
});

const barcode = ref("");

const cart_items = ref([]);
const cart_total = ref(0);

const uploading = ref(false);

const formErrors = ref({});

const client = ref({
    name: "Público en general",
    address: "",
    phone: "",
    email: "",
});

onMounted(() => {
    // if no input is focused, focus the barcode input
    window.addEventListener("keydown", (e) => {
        let focused = document.activeElement;

        // if key is escape, focus the barcode input
        if (e.key === "Escape") {
            document.getElementById("barcode").focus();
        }

        if (focused.tagName === "BODY") {
            document.getElementById("barcode").focus();
        }
    });

    // load cart items from local storage
    let storedItems = localStorage.getItem("entry_items");
    if (storedItems) {
        cart_items.value = JSON.parse(storedItems);
        callculateTotal();
    }
});

function submitBarcode() {
    let barcodeValue = barcode.value;

    // check if barcode is empty
    if (barcodeValue === "") {
        return;
    }

    // before fetching, check if the barcode is already in the cart
    for (let item of cart_items.value) {
        if (item.barcode === barcodeValue) {
            processProduct(item);
            return; // exit the function
        }
    }

    fetch(`/api/barcode/${barcodeValue}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.status == "success" && data.products.length > 0) {
                data.products.forEach((product) => {
                    processProduct(product);
                });
            } else {
                notifier.notify({
                    message: "Código de barras no registrado.",
                    type: "error",
                });
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Ocurrió un error al buscar el producto.",
                type: "error",
            });
            console.error("Error: ", error);
        });
    barcode.value = "";
}

const callculateTotal = () => {
    let total = 0;
    cart_items.value.forEach((item) => {
        total += item.price * item.purchase_quantity;
    });
    cart_total.value = total;

    // save cart items to local storage
    localStorage.setItem("entry_items", JSON.stringify(cart_items.value));
};

const processProduct = (product) => {
    let existing_product = cart_items.value.find(
        (item) => item.id === product.id
    );

    if (existing_product) {
        existing_product.purchase_quantity++;
    } else {
        product.purchase_quantity = 1;
        cart_items.value.push(product);
    }
    barcode.value = "";
    callculateTotal();
};

const cancelCallback = (product) => {
    let index = cart_items.value.findIndex((item) => item.id === product.id);
    cart_items.value.splice(index, 1);
    callculateTotal();
};

const submitSale = () => {
    if (cart_items.value.length === 0) {
        return;
    }
    if (uploading.value) {
        return;
    }
    uploading.value = true;
    let sale = {
        client: client.value,
        products: cart_items.value,
        _token: props.csrf,
    };

    setTimeout(() => {
        fetch("/api/entries", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(sale),
        })
            .then((response) => {
                uploading.value = false;
                return response.json(); // Parse response as JSON
            })
            .then((data) => {
                // Handle both success and error responses
                if (data.status === "success") {
                    clearCart();
                    notifier.notify({
                        message: "Entrada guardada exitosamente.",
                        type: "success",
                    });
                } else {
                    let unpackedErrors = {};
                    for (let key in data.errors) {
                        unpackedErrors[key] = data.errors[key][0];
                    }
                    formErrors.value = unpackedErrors;
                    console.log("formErrors", formErrors.value);
                    notifier.notify({
                        message: "Ocurrió un error al guardar la entrada.",
                        type: "error",
                    });
                }
            })
            .catch((error) => {
                notifier.notify({
                    message: "Ocurrió un error al guardar la entrada.",
                    type: "error",
                });
                uploading.value = false;
                console.error("Error: ", error);
            });
    }, 1000);
};

const clearCart = () => {
    cart_items.value = [];
    cart_total.value = 0;
    client.value = {
        name: "Público en general",
        address: "",
        phone: "",
        email: "",
    };
    formErrors.value = {};
    localStorage.removeItem("entry_items");
};
</script>

<template>
    <AppLayout title="Entrada de producto">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template> -->

        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-10 grid-rows-5 shadow-xl sm:rounded-lg h-[90dvh] w-full"
            >
                <!-- Items list 3/5 width and 5/5 height -->
                <div
                    class="col-span-10 row-span-5 bg-blue-50 block border-2 rounded-lg p-4"
                >
                    <div
                        class="flex flex-col justify-between items-center w-full gap-4"
                    >
                        <input
                            type="text"
                            id="barcode"
                            placeholder="Escanear código de barras"
                            v-model="barcode"
                            @keydown.enter="submitBarcode"
                            class="w-1/2 p-2 border-2 border-gray-300 rounded-lg"
                        />

                        <div
                            class="w-full max-h-[80vh] overflow-y-auto"
                            id="items-container"
                        >
                            <div v-for="item in cart_items" :key="item.id">
                                <ProductCard
                                    :product="item"
                                    :cancelCallback="cancelCallback"
                                />
                            </div>
                        </div>
                        <button
                            class="bg-blue-500 text-white p-2 rounded-lg flex justify-center items-center gap-3"
                            @click="submitSale"
                        >
                            <span v-if="!uploading">Guardar entrada</span>
                            <span v-else>Guardando...</span>
                            <InlineSpinner :active="uploading" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
