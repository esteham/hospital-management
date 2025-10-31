<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Patients Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                        Hospital Management Dashboard
                    </h2>
                    <p class="text-slate-600 mt-1">
                        Welcome back! Manage your health and appointments.
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7l mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Welcome Section -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 border border-blue-100"
                >
                    <div class="flex items-center space-x-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
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
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800">
                                Welcome to Our Hospital
                            </h3>
                            <p class="text-slate-600 mt-2">
                                Access your medical records, book appointments,
                                and manage your health packages all in one
                                place.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <!-- Book Appointment -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600 group-hover:text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Book Appointment
                                </h4>
                                <p class="text-sm text-slate-600">
                                    Schedule with our doctors
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            Book Now
                        </button>
                    </div>

                    <!-- Health Packages -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-green-600 group-hover:text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Health Packages
                                </h4>
                                <p class="text-sm text-slate-600">
                                    Explore preventive care
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            View Packages
                        </button>
                    </div>

                    <!-- Medical Records -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-purple-600 group-hover:text-white"
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
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Medical Records
                                </h4>
                                <p class="text-sm text-slate-600">
                                    Access your health history
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            View Records
                        </button>
                    </div>

                    <!-- Emergency Contact -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-red-600 group-hover:text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Emergency
                                </h4>
                                <p class="text-sm text-slate-600">
                                    24/7 emergency services
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            Call Now
                        </button>
                    </div>

                    <!-- Health Tips -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-orange-600 group-hover:text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Health Tips
                                </h4>
                                <p class="text-sm text-slate-600">
                                    Daily wellness advice
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            Read More
                        </button>
                    </div>

                    <!-- Profile Settings -->
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-white/20 hover:shadow-lg transition-all duration-300 group"
                    >
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-500 transition-colors duration-300"
                            >
                                <svg
                                    class="w-6 h-6 text-indigo-600 group-hover:text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-800">
                                    Profile
                                </h4>
                                <p class="text-sm text-slate-600">
                                    Manage your account
                                </p>
                            </div>
                        </div>
                        <button
                            class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-xl transition-colors duration-300"
                        >
                            Edit Profile
                        </button>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-white/20"
                >
                    <h4 class="font-bold text-xl text-slate-800 mb-4">
                        Recent Activity
                    </h4>
                    <div class="space-y-4">
                        <div
                            class="flex items-center space-x-4 p-4 bg-slate-50 rounded-xl"
                        >
                            <div
                                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800">
                                    Appointment booked with Dr. Smith
                                </p>
                                <p class="text-sm text-slate-600">
                                    Tomorrow at 10:00 AM
                                </p>
                            </div>
                            <span class="text-xs text-slate-500"
                                >2 hours ago</span
                            >
                        </div>
                        <div
                            class="flex items-center space-x-4 p-4 bg-slate-50 rounded-xl"
                        >
                            <div
                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800">
                                    Health Package purchased
                                </p>
                                <p class="text-sm text-slate-600">
                                    Annual Checkup Package
                                </p>
                            </div>
                            <span class="text-xs text-slate-500"
                                >1 day ago</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
