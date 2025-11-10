<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister, doctor } = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    doctor: Object,
});
</script>

<template>
    <Head
        :title="`${doctor.user.name} - Doctor Details - Xet Specialized Hospital`"
    />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50/30"
    >
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Back Button -->
            <div class="mb-6">
                <Link
                    href="/find-doctor"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-blue-600 rounded-full font-semibold shadow hover:bg-blue-600 hover:text-white transition-all duration-300"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                    Back to Find Doctor
                </Link>
            </div>

            <!-- Doctor Profile Card -->
            <div
                class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden"
            >
                <!-- Doctor Photo and Basic Info -->
                <div class="relative">
                    <div
                        class="h-48 sm:h-64 bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center"
                    >
                        <img
                            v-if="doctor.user.photo"
                            :src="`/storage/${doctor.user.photo}`"
                            alt="Doctor Photo"
                            class="w-32 h-32 sm:w-48 sm:h-48 object-cover rounded-full border-4 border-white shadow-lg"
                        />
                        <svg
                            v-else
                            class="w-32 h-32 sm:w-48 sm:h-48 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                    </div>
                    <div
                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent p-4 sm:p-6"
                    >
                        <h1
                            class="text-2xl sm:text-3xl font-black text-white mb-2"
                        >
                            {{ doctor.user.name }}
                        </h1>
                        <p
                            class="text-blue-200 font-semibold text-base sm:text-lg"
                        >
                            {{ doctor.speciality }}
                        </p>
                        <p class="text-white/80 text-sm sm:text-base">
                            {{ doctor.designation }}
                        </p>
                    </div>
                </div>

                <!-- Doctor Details -->
                <div class="p-4 sm:p-8 space-y-6">
                    <!-- About Section -->
                    <div>
                        <h2
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-4"
                        >
                            About
                        </h2>
                        <p
                            class="text-gray-700 leading-relaxed text-sm sm:text-base"
                        >
                            {{ doctor.about }}
                        </p>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h2
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-4"
                        >
                            Contact Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl"
                            >
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Phone
                                    </h3>
                                    <p class="text-gray-600">
                                        {{ doctor.phone }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-4 p-4 bg-green-50 rounded-xl"
                            >
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">
                                        Email
                                    </h3>
                                    <p class="text-gray-600">
                                        {{ doctor.user.email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a
                                :href="`tel:${doctor.phone}`"
                                class="flex-1 bg-blue-600 text-white px-4 sm:px-6 py-3 rounded-xl font-semibold shadow hover:bg-blue-700 transition-all duration-300 text-center text-sm sm:text-base"
                            >
                                📞 Call Now
                            </a>
                            <Link
                                :href="`/appointment-booking?doctor=${doctor.id}`"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-4 sm:px-6 py-3 rounded-xl font-semibold shadow hover:scale-105 transition-all duration-300 text-center text-sm sm:text-base"
                            >
                                📋 Book Appointment
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <Footer />
</template>
