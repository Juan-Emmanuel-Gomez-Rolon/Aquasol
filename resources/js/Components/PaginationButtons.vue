<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: {
        required: true,
    },
    current_page: {
        required: true,
    },
    prev_page_url: {
        required: true,
    },
    next_page_url: {
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-row items-center justify-center mt-4 mb-4 gap-1">
        <div
            v-for="link in getLinksToRender"
            :key="link.url"
            class="flex flex-row items-center justify-center"
        >
            <!-- Check if link.url is not null before rendering the link -->
            <template v-if="link.url !== null">
                <Link
                    :href="link.url"
                    :class="{
                        'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded':
                            link.active,
                        'bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow':
                            !link.active,
                    }"
                    v-html="link.label"
                ></Link>
            </template>
            <template v-else>
                <!-- Render a placeholder or alternative content when link.url is null -->
                <a class="flex flex-row items-center justify-center">
                    <span
                        :class="{
                            'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded':
                                link.active,
                            'bg-gray-400  text-gray-600 font-semibold py-2 px-4 border border-gray-400 rounded shadow':
                                !link.active,
                        }"
                        v-html="link.label"
                    ></span>
                </a>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        getLinksToRender() {
            // Check if the screen size is small
            if (window.innerWidth < 1024) {
                // grab first and last link
                let firstLink = this.links[0];
                let lastLink = this.links[this.links.length - 1];

                return [
                    firstLink,
                    {
                        label: this.current_page,
                        url: null,
                        active: true,
                    },
                    lastLink,
                ];
            }

            // Render all links
            return this.links;
        },
    },
};
</script>
