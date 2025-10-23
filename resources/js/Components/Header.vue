<template>
    <header
        class="bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Hospital Name -->
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-8 h-8 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent"
                        >
                            Xet Specialized Hospital
                        </h1>
                        <p class="text-sm text-gray-500 font-medium">
                            Excellence in Specialized Healthcare
                        </p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="hidden lg:flex space-x-10">
                    <a
                        href="#home"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Home
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#services"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Services
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#health-s"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Health s
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#patient-stories"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Patient Stories
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#blog"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Blog
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <div v-if="canLogin" class="flex space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-gradient-to-r from-green-600 to-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>
