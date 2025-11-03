<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

import Logo from "@/assets/images/logo/logo.png";
import { Rotate3D } from "lucide-vue-next";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);
const isServicesDropdownOpen = ref(false);
</script>

<template>
    <header
        class="bg-white/95 backdrop-blur-xl shadow-sm border-b border-gray-100/50 sticky top-0 z-50"
        @click="isMenuOpen = !isMenuOpen"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Hospital Name -->
                <Link :href="route('welcome')">
                    <div class="flex items-center space-x-4">
                        <img class="w-12 h-12" :src="Logo" />
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent tracking-tight"
                            >
                                Xet Specialized Hospital
                            </h1>
                            <p
                                class="text-sm text-gray-600 font-medium tracking-wide"
                            >
                                Excellence in Specialized Healthcare
                            </p>
                        </div>
                    </div>
                </Link>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:flex space-x-8">
                    <Link
                        :href="route('welcome')"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2"
                    >
                        <span class="relative z-10">Home</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                        ></span>
                    </Link>
                    <div
                        class="relative"
                        @mouseleave="isServicesDropdownOpen = false"
                    >
                        <button
                            @mouseenter="isServicesDropdownOpen = true"
                            class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2 flex items-center space-x-1"
                        >
                            <span class="relative z-10">Services</span>
                            <svg
                                class="w-4 h-4 transition-transform duration-200"
                                :class="{
                                    'rotate-180': isServicesDropdownOpen,
                                }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                            ></span>
                        </button>
                        <div
                            v-if="isServicesDropdownOpen"
                            class="absolute top-8 left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                            @mouseenter="isServicesDropdownOpen = true"
                        >
                            <Link
                                :href="route('appointment.booking')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 font-semibold hover:text-blue-600 transition-colors duration-200"
                                @click="isServicesDropdownOpen = false"
                            >
                                Book Appointment
                            </Link>
                            <Link
                                href="#health-s"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 font-semibold hover:text-blue-600 transition-colors duration-200"
                                @click="isServicesDropdownOpen = false"
                            >
                                Health Packages
                            </Link>
                            <Link
                                :href="route('find.doctor')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 font-semibold hover:text-blue-600 transition-colors duration-200"
                                @click="isServicesDropdownOpen = false"
                            >
                                Find Doctor
                            </Link>
                        </div>
                    </div>

                    <a
                        href="#patient-stories"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2"
                    >
                        <span class="relative z-10">Patient Stories</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                        ></span>
                    </a>
                    <a
                        href="#blog"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2"
                    >
                        <span class="relative z-10">Blog</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                        ></span>
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button
                        @click="isMenuOpen = !isMenuOpen"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                    >
                        <svg
                            class="w-6 h-6 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="
                                    isMenuOpen
                                        ? 'M6 18L18 6M6 6l12 12'
                                        : 'M4 6h16M4 12h16M4 18h16'
                                "
                            />
                        </svg>
                    </button>

                    <div v-if="canLogin" class="hidden lg:flex space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 shadow-md"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 hover:scale-105 px-4 py-2 rounded-lg hover:bg-blue-50"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-gradient-to-r from-green-600 to-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 shadow-md"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div
                v-if="isMenuOpen"
                class="lg:hidden py-4 border-t border-gray-200/50 animate-fade-in"
            >
                <nav class="flex flex-col space-y-4">
                    <a
                        :href="route('/')"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="isMenuOpen = false"
                    >
                        Home
                    </a>
                    <div class="relative">
                        <button
                            @click="
                                isServicesDropdownOpen = !isServicesDropdownOpen
                            "
                            class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-4 px-4 rounded-lg hover:bg-blue-50 flex items-center justify-between w-full"
                        >
                            <span>Services</span>
                            <svg
                                class="w-4 h-4 transition-transform duration-200"
                                :class="{
                                    'rotate-180': isServicesDropdownOpen,
                                }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </button>
                        <div
                            v-if="isServicesDropdownOpen"
                            class="mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                        >
                            <Link
                                :href="route('appointment.booking')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="
                                    isServicesDropdownOpen = false;
                                    isMenuOpen = false;
                                "
                            >
                                Book Appointment
                            </Link>
                            <Link
                                href="#health-s"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="
                                    isServicesDropdownOpen = false;
                                    isMenuOpen = false;
                                "
                            >
                                Health Packages
                            </Link>
                            <Link
                                :href="route('find.doctor')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="
                                    isServicesDropdownOpen = false;
                                    isMenuOpen = false;
                                "
                            >
                                Find Doctor
                            </Link>
                        </div>
                    </div>

                    <a
                        href="#patient-stories"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="isMenuOpen = false"
                    >
                        Patient Stories
                    </a>
                    <a
                        href="#blog"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="isMenuOpen = false"
                    >
                        Blog
                    </a>

                    <!-- Mobile Auth Links -->
                    <div
                        v-if="canLogin"
                        class="pt-4 border-t border-gray-200/50 space-y-3"
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="block w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300"
                            @click="isMenuOpen = false"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="block w-full text-gray-600 hover:text-blue-600 font-semibold text-center py-3 rounded-xl border border-gray-200 hover:border-blue-200 transition-all duration-300"
                                @click="isMenuOpen = false"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="block w-full bg-gradient-to-r from-green-600 to-emerald-500 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300"
                                @click="isMenuOpen = false"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
