<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

/** Axios (CSRF + XHR) */
const api = axios.create({ headers: { "X-Requested-With": "XMLHttpRequest" } });
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

/** State */
const messages = ref([]);
const loadingMessages = ref(false);

/** Fetch messages */
async function loadMessages() {
    loadingMessages.value = true;
    try {
        const { data } = await api.get("/doctor/messages/api");
        messages.value = Array.isArray(data) ? data : [];
    } catch (e) {
        console.error("Failed to load messages", e);
        messages.value = [];
    } finally {
        loadingMessages.value = false;
    }
}

/** Mount */
onMounted(loadMessages);
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Messages
            </h2>
        </template>

        <div class="py-6 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900">
                        <h3 class="text-base sm:text-lg font-medium mb-4">
                            All Messages from Admin
                        </h3>
                        <div class="mt-2">
                            <div
                                v-if="loadingMessages"
                                class="text-sm text-gray-500"
                            >
                                Loading...
                            </div>
                            <div
                                v-else-if="messages.length === 0"
                                class="text-sm text-gray-500"
                            >
                                No messages yet.
                            </div>
                            <div v-else class="space-y-3 sm:space-y-4">
                                <div
                                    v-for="(msg, index) in messages"
                                    :key="msg.id"
                                    class="bg-gray-50 p-3 sm:p-4 rounded-lg border relative"
                                >
                                    <span
                                        v-if="index === 0"
                                        class="absolute top-1 right-1 sm:top-2 sm:right-2 bg-red-500 text-white text-xs px-1 py-0.5 sm:px-2 sm:py-1 rounded"
                                        title="New Message"
                                    >
                                        New
                                    </span>
                                    <div
                                        class="font-medium text-gray-800 text-sm sm:text-base"
                                    >
                                        {{ msg.admin_name }}
                                    </div>
                                    <div
                                        class="text-gray-700 mt-2 text-sm sm:text-base"
                                    >
                                        {{ msg.message }}
                                    </div>
                                    <div
                                        class="text-gray-500 mt-2 text-xs sm:text-sm"
                                    >
                                        {{ msg.created_at }}
                                    </div>
                                    <div
                                        v-if="msg.schedule_info"
                                        class="text-gray-600 italic mt-1 text-xs sm:text-sm"
                                    >
                                        {{ msg.schedule_info }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
