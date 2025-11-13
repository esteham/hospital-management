<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { ChevronRightIcon, CalendarDaysIcon } from "@heroicons/vue/24/outline";
import api from "@/lib/api";

const diagnosticServices = ref([]);

const fetchDiagnosticServices = async () => {
    try {
        const response = await api.get("/api/diagnostic-services");
        diagnosticServices.value = response.data;
    } catch (error) {
        console.error("Error fetching diagnostic services:", error);
    }
};

onMounted(() => {
    fetchDiagnosticServices();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Diagnostic
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Services
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Advanced diagnostic services with state-of-the-art equipment
                    for accurate and timely results.
                </p>
            </div>

            <!-- Services Grid -->
            <div
                v-if="diagnosticServices.length === 0"
                class="text-center py-12"
            >
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"
                ></div>
                <p class="text-gray-500">Loading diagnostic services...</p>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                <div
                    v-for="service in diagnosticServices"
                    :key="service.id"
                    class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
                >
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        <img
                            v-if="service.image"
                            :src="'/storage/' + service.image"
                            :alt="service.name"
                            class="w-full h-48 object-cover"
                        />
                        <div
                            v-else
                            class="w-full h-48 bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center"
                        >
                            <svg
                                class="w-16 h-16 text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-2"
                            >
                                {{ service.name }}
                            </h3>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                            >
                                {{ service.category || "General" }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{
                                service.description ||
                                "No description available"
                            }}
                        </p>

                        <div class="flex items-center justify-between mb-4">
                            <div class="text-2xl font-bold text-green-600">
                                ${{ service.price }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{
                                    service.duration
                                        ? `${service.duration} min`
                                        : "Duration N/A"
                                }}
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between text-sm text-gray-600 mb-4"
                        >
                            <span v-if="service.sample_type">
                                Sample: {{ service.sample_type }}
                            </span>
                            <span v-if="service.report_time">
                                Report: {{ service.report_time }}h
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <Link
                                :href="`/diagnostic/service/${service.id}`"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                View Details
                                <ChevronRightIcon class="w-4 h-4 ml-2" />
                            </Link>

                            <Link
                                :href="`/diagnostic/schedule/${service.id}`"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200"
                            >
                                <CalendarDaysIcon class="w-4 h-4 mr-2" />
                                Schedule Test
                            </Link>
                        </div>

                        <div
                            v-if="service.home_collection_available"
                            class="mt-3 text-center"
                        >
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                            >
                                🏠 Home Collection Available
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-12">
                <Link
                    href="/"
                    class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200"
                >
                    ← Back to Home
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
