<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Logo from "@/assets/images/logo/logo.png";

defineProps({ title: { type: String, default: "" } });

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const isAdmin = computed(() => user.value && user.value.role === "admin");
const isDoctor = computed(() => user.value && user.value.role === "doctor");
const sidebarOpen = ref(false);

const isRouteActive = (routeName) => {
    return route().current() === routeName;
};
</script>

<template>
    <div
        class="h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 overflow-hidden"
        :class="{ flex: isAdmin || isDoctor }"
    >
        <!-- Mobile Menu Button -->
        <button
            v-if="!isAdmin && !isDoctor"
            @click="sidebarOpen = true"
            class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white/90 backdrop-blur-md rounded-xl shadow-lg border border-white/80"
        >
            <svg
                class="w-6 h-6 text-slate-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

        <!-- Admin Sidebar -->
        <aside
            v-if="isAdmin"
            class="w-80 bg-white/10 backdrop-blur-2xl border-r border-white/80 shadow-2xl flex flex-col"
        >
            <!-- Sidebar Header -->
            <div class="p-8 border-b border-white/10">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-7 h-7 text-white"
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
                        <h2 class="text-xl font-bold text-slate-800">
                            Admin Panel
                        </h2>
                        <p class="text-slate-600 mt-1 text-sm">
                            Welcome back, {{ user.name }} 👋
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-6 space-y-3 overflow-y-auto">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('dashboard'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-blue-600 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Dashboard</span>
                </Link>

                <Link
                    :href="route('admin.doctors.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'admin.doctors.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-green-600 group-hover:text-white"
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
                    <span class="font-semibold">Manage Doctors</span>
                </Link>

                <Link
                    :href="route('admin.staff.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('admin.staff.index'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center group-hover:bg-cyan-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-cyan-600 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Manage Staff</span>
                </Link>

                <Link
                    :href="route('admin.schedules.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'admin.schedules.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-purple-600 group-hover:text-white"
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
                    <span class="font-semibold">Doctors Schedules</span>
                </Link>

                <Link
                    :href="route('admin.package-bookings.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'admin.package-bookings.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-indigo-600 group-hover:text-white"
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
                    <span class="font-semibold">Booking Package</span>
                </Link>

                <Link
                    :href="route('admin.appointments.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'admin.appointments.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-teal-600 group-hover:text-white"
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
                    </div>
                    <span class="font-semibold">Dr. Appointments</span>
                </Link>

                <Link
                    :href="route('admin.health-checks.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'admin.health-checks.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-orange-600 group-hover:text-white"
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
                    <span class="font-semibold">Manage Packages</span>
                </Link>

                <Link
                    :href="route('admin.news.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('admin.news.index'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center group-hover:bg-rose-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-rose-600 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m7-7v14"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Manage News</span>
                </Link>
            </nav>

            <!-- Logout -->
            <div class="p-6 border-t border-white/10">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center space-x-4 w-full px-4 py-2 text-red-600 hover:bg-red-50 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-red-100"
                >
                    <div
                        class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Log Out</span>
                </Link>
            </div>
        </aside>
        <!-- Admin Sidebar -->

        <!-- Doctor Sidebar -->
        <aside
            v-if="isDoctor"
            class="w-80 bg-white/10 backdrop-blur-2xl border-r border-white/80 shadow-2xl flex flex-col"
        >
            <!-- Sidebar Header -->
            <div class="p-8 border-b border-white/10">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-7 h-7 text-white"
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
                        <h2 class="text-xl font-bold text-slate-800">
                            Doctor Portal
                        </h2>
                        <p class="text-slate-600 mt-1 text-sm">
                            Welcome back, {{ user.name }} 👋
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-6 space-y-3 overflow-y-auto">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('dashboard'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-blue-600 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Dashboard</span>
                </Link>

                <Link
                    :href="route('doctor.schedules')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('doctor.schedules'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-purple-600 group-hover:text-white"
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
                    <span class="font-semibold">My Schedules</span>
                </Link>

                <Link
                    :href="route('doctor.appointments.index')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80': isRouteActive(
                            'doctor.appointments.index'
                        ),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-teal-600 group-hover:text-white"
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
                    </div>
                    <span class="font-semibold">Appointments</span>
                </Link>

                <Link
                    :href="route('doctor.messages')"
                    class="flex items-center space-x-4 px-4 py-2 text-slate-700 hover:bg-sky-200 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-white/80"
                    :class="{
                        'bg-sky-200 shadow-lg border-white/80':
                            isRouteActive('doctor.messages'),
                    }"
                >
                    <div
                        class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:bg-yellow-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 text-yellow-600 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Messages</span>
                </Link>
            </nav>

            <!-- Logout -->
            <div class="p-6 border-t border-white/10">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center space-x-4 w-full px-4 py-2 text-red-600 hover:bg-red-50 hover:shadow-lg rounded-2xl transition-all duration-300 group border border-white/0 hover:border-red-100"
                >
                    <div
                        class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-500 transition-colors duration-300"
                    >
                        <svg
                            class="w-5 h-5 group-hover:text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                    </div>
                    <span class="font-semibold">Log Out</span>
                </Link>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div
            v-if="sidebarOpen && !isAdmin && !isDoctor"
            class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-all duration-300"
            @click="sidebarOpen = false"
        ></div>

        <!-- Mobile Sidebar -->
        <aside
            v-if="sidebarOpen && !isAdmin && !isDoctor"
            class="lg:hidden fixed top-0 left-0 w-80 h-full bg-white/95 backdrop-blur-2xl shadow-2xl border-r border-white/80 z-50 transform transition-transform duration-300"
        >
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center"
                        >
                            <span class="text-white font-bold text-sm">{{
                                user.name?.charAt(0)
                            }}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">
                                {{ user.name }}
                            </h3>
                            <p class="text-slate-600 text-sm">
                                {{ user.role }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="sidebarOpen = false"
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors duration-200"
                    >
                        <svg
                            class="w-5 h-5 text-slate-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-4 space-y-2">
                <!-- Logo and Hospital Name -->
                <Link :href="route('welcome')">
                    <div class="flex items-center space-x-4">
                        <img class="w-14 h-14" :src="Logo" />
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
                <Link
                    v-if="user?.role === 'doctor'"
                    href="/doctor"
                    class="block px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-xl transition-all duration-200 font-medium"
                    :class="{ 'bg-slate-50': isActive('/doctor') }"
                    >Doctor Portal</Link
                >
                <Link
                    v-if="user?.role === 'doctor'"
                    href="/doctor/schedules"
                    class="block px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-xl transition-all duration-200 font-medium"
                    :class="{ 'bg-slate-50': isActive('/doctor/schedules') }"
                    >My Schedules</Link
                >
                <Link
                    v-if="user?.role === 'doctor'"
                    href="/doctor/messages"
                    class="block px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-xl transition-all duration-200 font-medium"
                    :class="{ 'bg-slate-50': isActive('/doctor/messages') }"
                    >Messages</Link
                >
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div
            :class="{ 'flex-1': isAdmin || isDoctor }"
            class="h-screen flex flex-col"
        >
            <!-- Top Navigation for Non-Admin -->
            <nav
                v-if="!isAdmin && !isDoctor"
                class="bg-white/80 backdrop-blur-xl border-b border-white/80 shadow-sm sticky top-0 z-30"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-20 items-center">
                        <!-- Left Navigation -->
                        <div class="flex items-center space-x-10">
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
                        </div>

                        <!-- User Info & Logout -->
                        <div class="flex items-center space-x-6">
                            <div
                                class="flex items-center space-x-4 bg-sky-200 backdrop-blur-sm rounded-2xl px-4 py-2 shadow-sm border border-white/80"
                            >
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg"
                                >
                                    {{ user.name?.charAt(0) }}
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-slate-800"
                                        >{{ user.name }}</span
                                    >
                                    <span
                                        class="text-xs text-slate-600 capitalize"
                                        >{{ user.role }}</span
                                    >
                                </div>
                            </div>
                            <Link
                                v-if="user"
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-slate-600 hover:text-red-600 transition-all duration-300 flex items-center space-x-2 px-4 py-2 rounded-xl hover:bg-red-50 border border-transparent hover:border-red-100"
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
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                <span class="font-medium">Logout</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Header (hidden for admin and doctor) -->
            <header
                v-if="!isAdmin && !isDoctor && title"
                class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 backdrop-blur-sm border-b border-white/80"
            >
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-4 lg:px-10">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-3 h-12 bg-gradient-to-b from-blue-500 to-purple-600 rounded-full shadow-lg"
                        ></div>
                        <div>
                            <h2
                                class="font-bold text-2xl text-slate-800 leading-tight"
                            >
                                {{ title }}
                            </h2>
                            <p class="text-slate-600 mt-1">
                                Manage your medical dashboard
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div
                v-if="page.props.flash?.success"
                class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg border border-green-600"
            >
                {{ page.props.flash.success }}
            </div>
            <div
                v-if="page.props.flash?.error"
                class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg border border-red-600"
            >
                {{ page.props.flash.error }}
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div
                    :class="
                        isAdmin
                            ? 'p-8'
                            : 'max-w-7xl mx-auto py-8 sm:px-6 lg:px-8'
                    "
                >
                    <div
                        :class="
                            isDoctor
                                ? 'bg-white/70 backdrop-blur-xl rounded-3xl shadow-sm border border-white/80 overflow-hidden'
                                : 'bg-white/70 backdrop-blur-xl rounded-3xl shadow-sm border border-white/80 overflow-hidden'
                        "
                    >
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
