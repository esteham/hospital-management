<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

import Logo from "@/assets/images/logo/logo.png";
// import { Rotate3D } from "lucide-vue-next"; // not used; remove if unnecessary

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const isMenuOpen = ref(false);
const isServicesDropdownOpen = ref(false);

// optional helpers
const closeAll = () => {
    isMenuOpen.value = false;
    isServicesDropdownOpen.value = false;
};
</script>

<template>
    <header
        class="bg-white/95 backdrop-blur-xl shadow-sm border-b border-gray-100/50 sticky top-0 z-50"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Hospital Name -->
                <Link :href="route('welcome')" @click.stop>
                    <div class="flex items-center space-x-4">
                        <img
                            class="w-12 h-12"
                            :src="Logo"
                            alt="Xet Specialized Hospital Logo"
                        />
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
                <nav class="hidden lg:flex space-x-8" @click.stop>
                    <Link
                        :href="route('welcome')"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2"
                    >
                        <span class="relative z-10">Home</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                        />
                    </Link>

                    <!-- Services (desktop: hover, mobile handled below) -->
                    <div
                        class="relative"
                        @mouseenter="isServicesDropdownOpen = true"
                        @mouseleave="isServicesDropdownOpen = false"
                        @click.stop
                    >
                        <button
                            type="button"
                            class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2 flex items-center space-x-1"
                            aria-haspopup="menu"
                            :aria-expanded="isServicesDropdownOpen"
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
                                />
                            </svg>
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                            />
                        </button>

                        <div
                            v-if="isServicesDropdownOpen"
                            class="absolute top-8 left-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
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
                        />
                    </a>
                    <a
                        href="#news"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-all duration-300 relative group py-2"
                    >
                        <span class="relative z-10">News</span>
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-blue-600 to-cyan-500 group-hover:w-full transition-all duration-300 rounded-full"
                        />
                    </a>
                </nav>

                <!-- Right side (auth + hamburger) -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button
                        type="button"
                        @click.stop="isMenuOpen = !isMenuOpen"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                        aria-label="Toggle menu"
                        :aria-expanded="isMenuOpen"
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

                    <!-- Desktop Auth -->
                    <div
                        v-if="canLogin"
                        class="hidden lg:flex space-x-4"
                        @click.stop
                    >
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
                v-show="isMenuOpen"
                class="lg:hidden py-4 border-t border-gray-200/50 animate-fade-in"
                @click.stop
            >
                <nav class="flex flex-col space-y-4">
                    <Link
                        :href="route('welcome')"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="closeAll"
                    >
                        Home
                    </Link>

                    <!-- Services (mobile: click to toggle) -->
                    <div class="relative" @click.stop>
                        <button
                            type="button"
                            @click.stop="
                                isServicesDropdownOpen = !isServicesDropdownOpen
                            "
                            class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-4 px-4 rounded-lg hover:bg-blue-50 flex items-center justify-between w-full"
                            :aria-expanded="isServicesDropdownOpen"
                            aria-haspopup="menu"
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
                                />
                            </svg>
                        </button>

                        <div
                            v-show="isServicesDropdownOpen"
                            class="mt-2 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                        >
                            <Link
                                :href="route('appointment.booking')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="closeAll"
                            >
                                Book Appointment
                            </Link>
                            <Link
                                href="#health-s"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="closeAll"
                            >
                                Health Packages
                            </Link>
                            <Link
                                :href="route('find.doctor')"
                                class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                                @click="closeAll"
                            >
                                Find Doctor
                            </Link>
                        </div>
                    </div>

                    <a
                        href="#patient-stories"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="closeAll"
                    >
                        Patient Stories
                    </a>
                    <a
                        href="#blog"
                        class="text-gray-600 hover:text-blue-600 font-semibold transition-colors duration-200 py-2 px-4 rounded-lg hover:bg-blue-50"
                        @click="closeAll"
                    >
                        Blog
                    </a>

                    <!-- Mobile Auth Links -->
                    <div
                        v-if="canLogin"
                        class="pt-4 border-t border-gray-200/50 space-y-3"
                        @click.stop
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="block w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300"
                            @click="closeAll"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="block w-full text-gray-600 hover:text-blue-600 font-semibold text-center py-3 rounded-xl border border-gray-200 hover:border-blue-200 transition-all duration-300"
                                @click="closeAll"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="block w-full bg-gradient-to-r from-green-600 to-emerald-500 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300"
                                @click="closeAll"
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
    animation: fadeIn 0.25s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
