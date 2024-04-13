<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import ProductCard from "./ProductCard.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";

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
    let storedItems = localStorage.getItem("cart_items");
    if (storedItems) {
        cart_items.value = JSON.parse(storedItems);
        callculateTotal();
    }
});

function submitBarcode() {
    let barcodeValue = barcode.value;

    // check if barcode is empty
    if (barcodeValue === "") {
        notifier.notify({
            message: "El campo de código de barras está vacío",
            type: "warning",
            duration: 1000,
        });
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
                    message: `No se encontró ${barcodeValue}`,
                    type: "error",
                    duration: 3000,
                });
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Error al buscar el producto",
                type: "error",
                duration: 3000,
            });
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
    localStorage.setItem("cart_items", JSON.stringify(cart_items.value));
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
    if (!confirm(`¿Desea eliminar ${product.name} del carrito?`)) {
        return;
    }
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

    fetch("/api/sales", {
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
                notifier.notify({
                    message: "Venta realizada con éxito",
                    type: "success",
                    duration: 5000,
                });
                clearCart();
            } else {
                notifier.notify({
                    message: "Error al realizar la venta",
                    type: "error",
                    duration: 5000,
                });
                let unpackedErrors = {};
                for (let key in data.errors) {
                    unpackedErrors[key] = data.errors[key][0];
                }
                formErrors.value = unpackedErrors;
                console.log("formErrors", formErrors.value);
            }
        })
        .catch((error) => {
            uploading.value = false;
            console.error("Error: ", error);
        });
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
    localStorage.removeItem("cart_items");
};
</script>

<template>
    <AppLayout title="Caja">
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
                    class="col-span-7 row-span-5 bg-blue-50 block border-2 rounded-lg p-4"
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
                    </div>
                </div>

                <!-- Client information 2/5 width and 4/5 height -->
                <div
                    class="col-span-3 row-span-4 bg-blue-50 text-blue-900 block border-2 rounded-lg"
                >
                    <div class="flex flex-col gap-4 p-4">
                        <h3 class="text-xl font-semibold">Datos del cliente</h3>
                        <LabelInputPair
                            label="Nombre"
                            v-model="client.name"
                            placeholder="Nombre del cliente"
                            :error="formErrors['client.name']"
                            required
                        />
                        <LabelInputPair
                            label="Teléfono"
                            v-model="client.phone"
                            placeholder="3121231234"
                            :error="formErrors['client.phone']"
                        />
                        <LabelInputPair
                            label="Correo"
                            v-model="client.email"
                            placeholder="fulanito@correo.com"
                            :error="formErrors['client.email']"
                        />
                        <LabelInputPair
                            label="Dirección"
                            placeholder="Av. Siempre viva 123"
                            v-model="client.address"
                            :error="formErrors['client.address']"
                        />
                    </div>
                </div>

                <!-- Checkout 2/5 width and 1/5 height -->
                <div
                    class="col-span-3 row-span-1 bg-blue-50 text-blue-600 block border-2 rounded-lg"
                >
                    <div
                        class="flex flex-col justify-center items-center gap-2 w-full h-full"
                    >
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-600">
                                Total a pagar
                            </h2>
                        </div>
                        <div class="flex items-center">
                            <p class="text-3xl font-semibold mr-4">
                                $
                                {{
                                    cart_total

                                        .toFixed(2)
                                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }}
                            </p>
                        </div>
                        <button
                            class="bg-blue-100 text-black px-4 py-2 rounded-lg"
                            @click="submitSale"
                        >
                            Cerrar venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
