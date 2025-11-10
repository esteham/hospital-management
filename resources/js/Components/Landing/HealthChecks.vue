<script setup>
import api from "@/lib/api";
import Modal from "@/Components/Modal.vue";
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import axios from "axios";

const page = usePage();
const isLoggedIn = computed(() => !!page.props.auth?.user);

const healths = ref([]);
const showModal = ref(false);
const selectedHealth = ref(null);
const showLoginPrompt = ref(false);
const bookingLoading = ref(false);
const showSuccessModal = ref(false);
const bookingMessage = ref("");

const fetchHealthChecks = async () => {
    try {
        const response = await api.get("/api/health-checks");
        healths.value = response.data;
    } catch (error) {
        console.error("Error fetching health checks:", error);
    }
};

const selectPackage = (health) => {
    selectedHealth.value = health;
    showModal.value = true;
};

const bookPackage = async (paymentType) => {
    if (!isLoggedIn.value) {
        showModal.value = false;
        showLoginPrompt.value = true;
        return;
    }

    bookingLoading.value = true;
    try {
        const response = await axios.post("/api/bookings", {
            health_check_id: selectedHealth.value.id,
            payment_type: paymentType,
        });

        bookingMessage.value = response.data.message;
        showModal.value = false;
        showSuccessModal.value = true;
    } catch (error) {
        console.error("Error booking package:", error);
        if (error.response?.status === 401) {
            router.visit("/login", { preserveState: true });
        } else {
            alert("Failed to book package. Please try again.");
        }
    } finally {
        bookingLoading.value = false;
    }
};

const goToLogin = () => {
    router.visit("/login");
};

const goToLanding = () => {
    router.visit("/");
};

const goToDashboardPackages = () => {
    router.visit("/dashboard");
};

onMounted(() => {
    fetchHealthChecks();
});
</script>

<template>
    <section
        id="health-s"
        class="py-10 bg-gradient-to-br from-blue-200 to-yellow-200 text-white/10"
    >
        <div class="absolute inset-0 bg-white/85 pointer-events-none z-0"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-semibold lg:text-5xl text-black mb-2">
                    Health Package
                    <span class="text-gradient-to-br from-blue-600 to-cyan-500">
                        s
                    </span>
                </h2>
                <p
                    class="text-l text-gray-400 max-w-3xl mx-auto leading-relaxed"
                >
                    Comprehensive health screening s designed for preventive
                    care and early detection.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div
                    v-for="health in healths"
                    :key="health.name"
                    class="text-center bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 hover:bg-white transition-all duration-500 transform hover:-translate-y-2"
                    :class="
                        health.popular
                            ? 'border-blue-500 scale-105'
                            : 'border-gray-200'
                    "
                >
                    <!-- Popular Badge -->
                    <div
                        v-if="health.popular"
                        class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2 rounded-full font-semibold text-sm"
                    >
                        Most Popular
                    </div>

                    <div class="p-8">
                        <h3
                            class="text-2xl font-bold text-gray-900 text-center mb-4"
                        >
                            {{ health.name }}
                        </h3>
                        <div
                            class="text-3xl font-black text-blue-600 text-center mb-6"
                        >
                            {{ health.price }}
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li
                                v-for="feature in health.features"
                                :key="feature"
                                class="flex items-center text-gray-600"
                            >
                                <svg
                                    class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>

                        <button
                            @click="selectPackage(health)"
                            class="w-full py-3 rounded-xl font-semibold transition-all duration-300"
                            :class="
                                health.popular
                                    ? 'bg-gradient-to-r from-blue-400 text-black hover:text-white hover:bg-blue-800 hover:shadow-lg'
                                    : 'bg-blue-100 text-gray-700 hover:bg-blue-500 hover:text-white'
                            "
                        >
                            Select
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Package Details -->
        <Modal :show="showModal" @close="showModal = false" max-width="md">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    {{ selectedHealth?.name }}
                </h3>
                <div class="text-3xl font-black text-blue-600 mb-6">
                    {{ selectedHealth?.price }}
                </div>
                <ul class="space-y-4 mb-8">
                    <li
                        v-for="feature in selectedHealth?.features"
                        :key="feature"
                        class="flex items-center text-gray-600"
                    >
                        <svg
                            class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        {{ feature }}
                    </li>
                </ul>
                <div class="flex justify-end">
                    <button
                        @click="showModal = false"
                        class="mr-4 px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="bookPackage('50%')"
                        :disabled="bookingLoading"
                        class="mr-2 px-6 py-2 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-lg hover:shadow-lg transition-all disabled:opacity-50"
                    >
                        {{ bookingLoading ? "Booking..." : "Pay 50%" }}
                    </button>
                    <button
                        @click="bookPackage('100%')"
                        :disabled="bookingLoading"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg hover:shadow-lg transition-all disabled:opacity-50"
                    >
                        {{ bookingLoading ? "Booking..." : "Pay 100%" }}
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Login Prompt Modal -->
        <Modal
            :show="showLoginPrompt"
            @close="showLoginPrompt = false"
            max-width="md"
        >
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    Login Required
                </h3>
                <p class="text-gray-600 mb-6">
                    You need to be logged in to book a health check package.
                    Please log in to continue.
                </p>
                <div class="flex justify-end">
                    <button
                        @click="showLoginPrompt = false"
                        class="mr-4 px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="goToLogin"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg hover:shadow-lg transition-all"
                    >
                        Go to Login
                    </button>
                </div>
            </div>
        </Modal>

        <!-- Success Modal -->
        <Modal
            :show="showSuccessModal"
            @close="showSuccessModal = false"
            max-width="md"
        >
            <div class="p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    Booking Successful
                </h3>
                <p class="text-gray-600 mb-6">
                    {{ bookingMessage }}
                </p>
                <div class="flex justify-end space-x-4">
                    <button
                        @click="goToLanding"
                        class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        OK
                    </button>
                    <button
                        @click="goToDashboardPackages"
                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg hover:shadow-lg transition-all"
                    >
                        Go to Book Package
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
.text-card {
    margin: 0 100px;
    margin-bottom: 10px;
}
</style>
