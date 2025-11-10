<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister, doctors } = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    doctors: Array,
});

const searchQuery = ref("");
const selectedSpecialty = ref("");

const filteredDoctors = computed(() => {
    let filtered = doctors;

    if (searchQuery.value) {
        filtered = filtered.filter(
            (doctor) =>
                doctor.user.name
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()) ||
                doctor.speciality
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedSpecialty.value) {
        filtered = filtered.filter(
            (doctor) => doctor.speciality === selectedSpecialty.value
        );
    }

    return filtered;
});

const specialties = computed(() => {
    const uniqueSpecialties = [
        ...new Set(doctors.map((doctor) => doctor.speciality)),
    ];
    return uniqueSpecialties;
});
</script>

<template>
    <Head title="Find Doctor - Xet Specialized Hospital" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50/30"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row mb-6">
                <div class="w-full md:w-1/2 md:flex-1">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full text-blue-700 text-sm font-semibold mb-4"
                    >
                        <div
                            class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"
                        ></div>
                        Find Your Doctor
                    </div>

                    <h1
                        class="text-3xl md:text-4xl lg:text-4xl font-black text-gray-900 mb-4 leading-tight"
                    >
                        Discover Our
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                        >
                            Expert Doctors
                        </span>
                    </h1>
                    <p
                        class="text-base text-gray-700 leading-relaxed max-w-3xl mx-auto"
                    >
                        Find the right specialist for your healthcare needs.
                        Search by name or specialty to connect with our
                        experienced medical professionals.
                    </p>
                </div>
                <!-- Search and Filter Section -->
                <div
                    class="w-full md:w-1/2 md:flex-1 bg-white rounded-3xl shadow-xl border border-gray-100 p-5 mb-8"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Search Bar -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-3"
                            >
                                Search by Doctor Name
                            </label>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Enter doctor's name..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400"
                            />
                        </div>

                        <!-- Specialty Filter -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-3"
                            >
                                Filter by Specialty
                            </label>
                            <select
                                v-model="selectedSpecialty"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white"
                            >
                                <option value="">All Specialties</option>
                                <option
                                    v-for="specialty in specialties"
                                    :key="specialty"
                                    :value="specialty"
                                >
                                    {{ specialty }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctors Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="doctor in filteredDoctors"
                    :key="doctor.id"
                    class="group relative bg-white rounded-l shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300"
                >
                    <!-- Doctor Photo -->
                    <div
                        class="relative w-full h-64 bg-gradient-to-br from-blue-100 to-cyan-100"
                    >
                        <img
                            v-if="doctor.user.photo"
                            :src="`/storage/${doctor.user.photo}`"
                            alt="Doctor Photo"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <svg
                            v-else
                            class="w-20 h-20 text-blue-600 absolute inset-0 m-auto"
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

                    <!-- Doctor Info -->
                    <div class="p-6 text-center space-y-2">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ doctor.user.name }}
                        </h3>
                        <p class="text-blue-600 font-semibold">
                            {{ doctor.speciality }}
                        </p>
                        <p class="text-gray-600 text-sm">
                            {{ doctor.designation }}
                        </p>
                        <p class="text-gray-700 text-sm leading-relaxed mt-3">
                            {{ doctor.about }}
                        </p>
                        <div
                            class="flex items-center justify-center gap-2 text-gray-600 mt-2"
                        >
                            <svg
                                class="w-5 h-5 text-blue-500"
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
                            <span>{{ doctor.phone }}</span>
                        </div>
                    </div>

                    <!-- Hover Overlay (Buttons in center) -->
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-500"
                    >
                        <!-- Call Button -->
                        <a
                            :href="`tel:${doctor.phone}`"
                            class="mb-3 bg-white text-blue-600 px-5 py-2 rounded-full font-semibold shadow hover:bg-blue-600 hover:text-white transition-all duration-300"
                        >
                            📞 Call Now
                        </a>

                        <!-- Book Appointment Button -->
                        <Link
                            :href="`/appointment-booking?doctor=${doctor.id}`"
                            class="mb-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-3 rounded-full font-semibold shadow hover:scale-105 transition-all duration-300"
                        >
                            Book Appointment
                        </Link>

                        <!-- Doctors Details -->
                        <Link
                            :href="`/doctor/${doctor.id}`"
                            class="bg-white text-blue-600 px-5 py-2 rounded-full font-semibold shadow hover:bg-blue-600 hover:text-white transition-all duration-300"
                        >
                            Details
                        </Link>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="filteredDoctors.length === 0" class="text-center py-12">
                <div
                    class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <svg
                        class="w-12 h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    No doctors found
                </h3>
                <p class="text-gray-600">
                    Try adjusting your search criteria or browse all doctors.
                </p>
            </div>
        </div>
    </main>

    <Footer />
</template>
