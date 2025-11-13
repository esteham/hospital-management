<script setup>
import { ref, onMounted, watch } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { MagnifyingGlassIcon, FunnelIcon } from "@heroicons/vue/24/outline";
import api from "@/lib/api";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister } = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = ref([]);
const displayedCategories = ref([]);
const selectedCategory = ref("");
const categorySearch = ref("");
const categoryOffset = ref(0);
const categoryLimit = [];
const hasMoreCategories = ref(true);

const displayedServices = ref([]);
const serviceSearch = ref("");
const currentOffset = ref(0);
const isLoading = ref(false);
const hasMoreServices = ref(true);
const serviceLimit = 10;

// Modal state
const showModal = ref(false);
const selectedService = ref(null);

// Fetch categories
const fetchCategories = async (reset = false) => {
    try {
        if (reset) {
            categoryOffset.value = 0;
            displayedCategories.value = [];
            hasMoreCategories.value = true;
        }

        const params = new URLSearchParams({
            limit: categoryLimit,
            offset: categoryOffset.value,
        });

        if (categorySearch.value) {
            params.append("search", categorySearch.value);
        }

        const res = await api.get(`/diagnostic/services/categories?${params}`);
        const data = res.data;

        if (data.length < categoryLimit) hasMoreCategories.value = false;

        if (reset) {
            displayedCategories.value = data;
        } else {
            displayedCategories.value.push(...data);
        }

        categoryOffset.value += data.length;
    } catch (e) {
        console.error("Error fetching categories:", e);
    }
};

// Fetch services
const fetchServices = async (reset = false) => {
    if (reset) {
        currentOffset.value = 0;
        displayedServices.value = [];
        hasMoreServices.value = true;
    }

    if (!hasMoreServices.value && !reset) return;

    isLoading.value = true;
    try {
        const params = new URLSearchParams({
            limit: serviceLimit,
            offset: currentOffset.value,
        });

        if (selectedCategory.value) {
            params.append("category", selectedCategory.value);
        }

        if (serviceSearch.value) {
            params.append("search", serviceSearch.value);
        }

        const response = await api.get(`/diagnostic/services/list?${params}`);
        const newServices = response.data;

        if (newServices.length < serviceLimit) hasMoreServices.value = false;

        if (reset) {
            displayedServices.value = newServices;
        } else {
            displayedServices.value.push(...newServices);
        }

        currentOffset.value += newServices.length;
    } catch (error) {
        console.error("Error fetching diagnostic services:", error);
    } finally {
        isLoading.value = false;
    }
};

const loadMoreCategories = () => fetchCategories();
const loadMoreServices = () => fetchServices();

const clearFilters = () => {
    selectedCategory.value = "";
    serviceSearch.value = "";
    categorySearch.value = "";
    fetchServices(true);
};

const openModal = (service) => {
    selectedService.value = service;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedService.value = null;
};

watch(categorySearch, () => fetchCategories(true));
watch(serviceSearch, () => fetchServices(true));
watch(selectedCategory, () => fetchServices(true));

onMounted(() => {
    fetchCategories(true);
    fetchServices(true);
});
</script>

<template>
    <Head title="Diagnostic Services - Xet Specialized Hospital" />
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 pb-8 to-blue-50">
        <!-- Hero Section -->
        <div class="relative bg-white shadow-sm">
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-cyan-500/10"
            ></div>
            <div
                class="flex justify-between max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4"
            >
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    Diagnostic
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Services
                    </span>
                </h1>
                <!-- Service Search -->
                <div class="relative w-full md:w-80">
                    <MagnifyingGlassIcon
                        class="absolute left-3 top-3 h-5 w-5 text-gray-400"
                    />
                    <input
                        v-model="serviceSearch"
                        type="text"
                        placeholder="Search services..."
                        class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-6 mt-2">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar - Categories -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24"
                    >
                        <div class="p-2 border-b border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-bold text-gray-900">
                                    Categories
                                </h2>
                                <FunnelIcon class="h-5 w-5 text-gray-500" />
                            </div>

                            <!-- Category Search -->
                            <div class="relative mb-4">
                                <MagnifyingGlassIcon
                                    class="absolute left-3 top-3 h-5 w-5 text-gray-400"
                                />
                                <input
                                    v-model="categorySearch"
                                    type="text"
                                    placeholder="Search categories..."
                                    class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <!-- Clear Filters -->
                        </div>

                        <!-- Category List -->
                        <div class="max-h-96 overflow-y-auto">
                            <div
                                v-for="(category, idx) in displayedCategories"
                                :key="idx"
                                @click="selectedCategory = category"
                                class="px-6 py-3 cursor-pointer border-b border-gray-50 hover:bg-blue-50 transition-colors"
                                :class="{
                                    'bg-blue-50 border-l-4 border-l-blue-500':
                                        selectedCategory === category,
                                }"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="w-2 h-2 rounded-full mr-3"
                                        :class="
                                            selectedCategory === category
                                                ? 'bg-blue-500'
                                                : 'bg-gray-300'
                                        "
                                    ></div>
                                    <span
                                        class="font-medium"
                                        :class="
                                            selectedCategory === category
                                                ? 'text-blue-700'
                                                : 'text-gray-700'
                                        "
                                    >
                                        {{ category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Load More Categories -->
                            <div
                                v-if="hasMoreCategories"
                                class="p-4 text-center border-t border-gray-100"
                            >
                                <button
                                    @click="loadMoreCategories"
                                    class="text-sm text-blue-600 font-medium hover:text-blue-800 transition-colors"
                                >
                                    Load more categories
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content - Services -->
                <div class="lg:col-span-3">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                    >
                        <div class="p-4 border-b border-gray-400">
                            <div
                                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
                            >
                                <div>
                                    <p class="text-gray-600 mt-1">
                                        {{ displayedServices.length }} services
                                        show
                                        <span
                                            v-if="selectedCategory"
                                            class="text-blue-600 font-medium"
                                        >
                                            in {{ selectedCategory }}
                                        </span>
                                    </p>
                                </div>
                                <button
                                    @click="clearFilters"
                                    class="text-sm text-blue-600 font-medium hover:text-blue-800 transition-colors"
                                >
                                    Clear all filters
                                </button>
                            </div>
                        </div>

                        <!-- Services Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead
                                    class="bg-gray-50 border-b border-gray-100"
                                >
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                        >
                                            Service
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                        >
                                            Price
                                        </th>
                                        <th
                                            class="px-6 py-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr
                                        v-for="service in displayedServices"
                                        :key="service.id"
                                        class="hover:bg-gray-50 transition-colors"
                                    >
                                        <td class="px-6 py-4">
                                            <div class="flex">
                                                <div
                                                    class="font-medium text-gray-900 mr-3"
                                                >
                                                    {{ service.name }}
                                                </div>
                                                <div
                                                    v-if="
                                                        service.home_collection_available
                                                    "
                                                >
                                                    <span
                                                        class="inline-flex items-center p-[2px] rounded-full text-xs font-medium bg-green-200"
                                                    >
                                                        🏠 Home Collection
                                                        Available
                                                    </span>
                                                </div>
                                            </div>
                                            <div
                                                v-if="service.description"
                                                class="text-sm text-gray-500 mt-1 line-clamp-1"
                                            >
                                                {{ service.description }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="items-center text-sm font-medium text-blue-800"
                                            >
                                                {{
                                                    service.category ||
                                                    "General"
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="font-semibold text-green-600"
                                            >
                                                <span class="text-xl text-black"
                                                    >৳</span
                                                >&nbsp;
                                                {{ service.price }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="flex justify-center space-x-2"
                                            >
                                                <button
                                                    @click="openModal(service)"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                                                >
                                                    Details
                                                </button>
                                                <Link
                                                    :href="`/diagnostic/schedule/${service.id}`"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
                                                >
                                                    Schedule
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Empty State -->
                            <div
                                v-if="
                                    displayedServices.length === 0 && !isLoading
                                "
                                class="text-center py-12"
                            >
                                <div
                                    class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4"
                                >
                                    <MagnifyingGlassIcon
                                        class="h-10 w-10 text-gray-400"
                                    />
                                </div>
                                <h3
                                    class="text-lg font-medium text-gray-900 mb-2"
                                >
                                    No services found
                                </h3>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    Try adjusting your search or filter criteria
                                    to find what you're looking for.
                                </p>
                                <button
                                    @click="clearFilters"
                                    class="mt-4 text-blue-600 font-medium hover:text-blue-800 transition-colors"
                                >
                                    Clear all filters
                                </button>
                            </div>
                        </div>

                        <!-- Load More -->
                        <div
                            v-if="
                                hasMoreServices && displayedServices.length > 0
                            "
                            class="p-6 border-t border-gray-100 text-center"
                        >
                            <button
                                @click="loadMoreServices"
                                :disabled="isLoading"
                                class="inline-flex items-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50"
                            >
                                <span
                                    v-if="isLoading"
                                    class="animate-spin h-4 w-4 border-b-2 border-blue-600 inline-block mr-2 rounded-full"
                                ></span>
                                {{
                                    isLoading
                                        ? "Loading..."
                                        : "Load More Services"
                                }}
                            </button>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                        >
                            <div
                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2">
                                Accurate Results
                            </h3>
                            <p class="text-gray-600 text-sm">
                                State-of-the-art equipment ensures precise
                                diagnostic outcomes.
                            </p>
                        </div>

                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                        >
                            <div
                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-4"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2">
                                Quick Turnaround
                            </h3>
                            <p class="text-gray-600 text-sm">
                                Get your test results promptly with our
                                efficient processing.
                            </p>
                        </div>

                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                        >
                            <div
                                class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-4"
                            >
                                <svg
                                    class="w-5 h-5 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    ></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2">
                                Expert Staff
                            </h3>
                            <p class="text-gray-600 text-sm">
                                Our certified professionals provide
                                compassionate care and guidance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity duration-300"
        @click="closeModal"
    >
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto max-h-[85vh] overflow-hidden border border-gray-100 transform transition-all duration-300 scale-100"
            @click.stop
        >
            <!-- Header -->
            <div
                class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h2
                            class="text-xl font-bold text-gray-900 truncate pr-2"
                        >
                            {{ selectedService?.name }}
                        </h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-s font-medium text-blue-800">
                                {{ selectedService?.category || "General" }}
                            </span>
                            <span
                                v-if="
                                    selectedService?.home_collection_available
                                "
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800"
                            >
                                🏠 Home Collection Available
                            </span>
                        </div>
                    </div>
                    <button
                        @click="closeModal"
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 hover:scale-110"
                    >
                        <svg
                            class="w-4 h-4 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Service Image & Basic Info -->
                <div class="flex items-start gap-4">
                    <div
                        class="flex-shrink-0 w-16 h-16 flex items-center justify-center"
                    >
                        <img
                            v-if="selectedService?.image"
                            :src="'/storage/' + selectedService.image"
                            :alt="selectedService.name"
                            class="h-14 object-contain"
                        />
                        <svg
                            v-else
                            class="h-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"
                            />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline gap-2 mb-2">
                            <div class="flex text-2xl font-bold text-gray-900">
                                <p>Price :</p>
                                <span>৳&nbsp;{{ selectedService?.price }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-[14px]">
                            <div class="flex">
                                <div class="flex items-center text-gray-600">
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <p>Duration:</p>
                                    &nbsp;

                                    <span>{{
                                        selectedService?.duration
                                            ? `${selectedService.duration} min`
                                            : "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-gray-600">
                                <div class="flex">
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    <p>Report Time:</p>
                                    &nbsp;
                                    <span>{{
                                        selectedService?.report_time
                                            ? `${selectedService.report_time}h`
                                            : "N/A"
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Type -->
                <div
                    v-if="selectedService?.sample_type"
                    class="bg-blue-50 rounded-lg p-3 border border-blue-100"
                >
                    <div class="flex items-center gap-2 text-sm">
                        <svg
                            class="w-4 h-4 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                            />
                        </svg>
                        <span class="font-medium text-blue-900"
                            >Sample Required:</span
                        >
                        <span class="text-blue-800">{{
                            selectedService.sample_type
                        }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div v-if="selectedService?.description" class="space-y-2">
                    <h3
                        class="font-semibold text-gray-900 flex items-center gap-2"
                    >
                        <svg
                            class="w-4 h-4 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        Description
                    </h3>
                    <p
                        class="text-gray-700 leading-relaxed text-sm bg-gray-50 rounded-lg p-3 border border-gray-200"
                    >
                        {{ selectedService.description }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-2">
                    <button
                        @click="closeModal"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-200 hover:border-gray-400 active:scale-95"
                    >
                        Close
                    </button>
                    <Link
                        :href="`/diagnostic/schedule/${selectedService?.id}`"
                        class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95 text-center"
                    >
                        Schedule Test
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.sticky {
    position: sticky;
}
</style>
