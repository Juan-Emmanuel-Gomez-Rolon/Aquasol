<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import LabelInputPair from "@/Components/LabelInputPair.vue";
import InlineSpinner from "@/Components/InlineSpinner.vue";

import Notifier from "@/notifier";
const notifier = new Notifier();

import { ref } from "vue";
import Pencil from "@/Icons/Pencil.vue";

const new_user = ref({
    name: "",
    email: "",
    password: "",
    role: "user",
});

const updating = ref(false);
const errors = ref({});

const props = defineProps({
    csrf: String,
    db_users: {
        required: true,
    },
});

const users = ref(props.db_users);

const updateUser = async (user) => {
    let data = {
        _token: props.csrf,
        name: user.name,
        email: user.email,
        role: user.role,
    };

    fetch(`/api/users/${user.id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if (data.status === "success" && data.data) {
                notifier.notify({
                    message: `Usuario ${user.name} actualizado`,
                    type: "success",
                });
            } else {
                notifier.notify({
                    message: "Hubo un error al actualizar el usuario",
                    type: "error",
                });
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Hubo un error al actualizar el usuario",
                type: "error",
            });
        });
};

const createUser = async (e) => {
    // Prevent the form from submitting
    e.preventDefault();
    updating.value = true;
    let data = {
        _token: props.csrf,
        name: new_user.value.name,
        email: new_user.value.email,
        password: new_user.value.password,
        role: new_user.value.role,
    };

    fetch(`/api/users`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            return response.json();
        })
        .then((res) => {
            if (res.status === "success" && res.data) {
                notifier.notify({
                    message: `Usuario creado`,
                    type: "success",
                });
                // append data to users
                users.value = [...users.value, res.data];
                clearForm();
            } else {
                notifier.notify({
                    message: "Hubo un error al crear el usuario",
                    type: "error",
                });
                errors.value = unpackErrors(res.errors);
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Hubo un error al crear el usuario",
                type: "error",
            });
            console.error(error);
        })
        .finally(() => {
            updating.value = false;
        });
};

const deleteUser = (user) => {
    let data = {
        _token: props.csrf,
    };

    fetch(`/api/users/${user.id}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            return response.json();
        })
        .then((res) => {
            if (res.status === "success") {
                notifier.notify({
                    message: `Usuario ${user.name} deshabilitado`,
                    type: "success",
                });
                // set deleted_at to current date
                user.deleted_at = new Date().toISOString();
            } else {
                notifier.notify({
                    message: "Hubo un error al deshabilitar el usuario",
                    type: "error",
                });
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Hubo un error al deshabilitar el usuario",
                type: "error",
            });
            console.error(error);
        });
};

const enableUser = (user) => {
    let data = {
        _token: props.csrf,
    };

    fetch(`/api/users/${user.id}/enable`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            return response.json();
        })
        .then((res) => {
            if (res.status === "success") {
                notifier.notify({
                    message: `Usuario ${user.name} habilitado`,
                    type: "success",
                });
                // set deleted_at to null
                user.deleted_at = null;
            } else {
                notifier.notify({
                    message: "Hubo un error al habilitar el usuario",
                    type: "error",
                });
            }
        })
        .catch((error) => {
            notifier.notify({
                message: "Hubo un error al habilitar el usuario",
                type: "error",
            });
            console.error(error);
        });
};

const clearForm = () => {
    new_user.value = {
        name: "",
        email: "",
        password: "",
        role: "user",
    };
    errors.value = {};
};

const unpackErrors = (errors) => {
    let processed_errors = {};
    for (let key in errors) {
        processed_errors[key] = errors[key][0];
    }
    return processed_errors;
};

const formatCurrency = (value) => {
    let formatter = new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    });
    return formatter.format(value);
};

const formatDate = (date) => {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("es-MX");
};
</script>

<template>
    <AppLayout title="Usuarios">
        <div class="max-w-7xl mt-4 mx-auto sm:px-6 lg:px-8">
            <div class="shadow-xl sm:rounded-lgw-full">
                <!-- Items list 3/5 width and 5/5 height -->
                <div
                    class="grid grid-cols-5 grid-rows-1 shadow-xl sm:rounded-lg h-[90dvh] w-full"
                >
                    <div
                        class="col-span-1 row-span-1 bg-blue-100 border-blue-200 border-2 shadow-lg p-4"
                    >
                        <div class="flex flex-col gap-4 pt-2">
                            <a class="flex justify-between items-center">
                                <h3 class="font-bold text-xl text-gray-800">
                                    Usuarios
                                </h3>
                                <!-- Edit button -->
                                <!-- <Pencil class="w-6 h-6 text-blue-600" /> -->
                            </a>
                            <!-- New user form -->
                            <form
                                class="flex flex-col gap-4"
                                @submit="createUser"
                            >
                                <h3 class="font-bold text-xl text-gray-800">
                                    Nuevo usuario
                                </h3>
                                <LabelInputPair
                                    label="Nombre"
                                    v-model="new_user.name"
                                    required
                                    :error="errors.name"
                                />
                                <LabelInputPair
                                    label="Correo"
                                    v-model="new_user.email"
                                    required
                                    :error="errors.email"
                                />
                                <LabelInputPair
                                    label="Contraseña"
                                    v-model="new_user.password"
                                    :error="errors.password"
                                    required
                                    type="password"
                                />
                                <LabelInputPair
                                    label="Rol"
                                    v-model="new_user.role"
                                    type="select"
                                    :options="['admin', 'user']"
                                    required
                                    :error="errors.role"
                                />
                                <div class="flex justify-center items-center">
                                    <button
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md"
                                    >
                                        Crear
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div
                        class="col-span-4 row-span-1 bg-blue-100 border-blue-200 border-2 shadow-lg p-4 overflow-y-scroll"
                    >
                        <h3 class="font-bold text-xl text-gray-800 mb-4">
                            Usuarios registrados ({{ users.length }})
                        </h3>
                        <div v-for="(user, index) in users" :key="user.id">
                            <div
                                class="flex flex-row gap-4 px-4 py-2 w-full border-b-2 border-blue-200"
                                :class="{
                                    'bg-blue-50': !user.deleted_at,
                                    'bg-gray-200 text-red-900': user.deleted_at,
                                }"
                            >
                                <LabelInputPair
                                    label="Nombre"
                                    v-model="user.name"
                                    @change="updateUser(user)"
                                    :disabled="user.deleted_at"
                                />
                                <LabelInputPair
                                    label="Correo"
                                    v-model="user.email"
                                    @change="updateUser(user)"
                                    :disabled="user.deleted_at"
                                />
                                <LabelInputPair
                                    label="Rol"
                                    v-model="user.role"
                                    type="select"
                                    :options="['admin', 'user']"
                                    @change="updateUser(user)"
                                    :disabled="
                                        user.deleted_at ||
                                        user.id === $page.props.auth.user.id
                                    "
                                />
                                <!-- Delete button -->
                                <div
                                    class="flex justify-center items-center"
                                    v-if="!user.deleted_at"
                                >
                                    <button
                                        type="button"
                                        class="bg-red-500 text-white px-4 py-2 rounded-md"
                                        @click="deleteUser(user)"
                                    >
                                        Deshabilitar
                                    </button>
                                </div>
                                <!-- Enable button -->
                                <div
                                    class="flex justify-center items-center"
                                    v-if="user.deleted_at"
                                >
                                    <button
                                        type="button"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md"
                                        @click="enableUser(user)"
                                    >
                                        Habilitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
