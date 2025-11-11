<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const { groupedAppointments } = defineProps({
    groupedAppointments: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = ref("");
const statusFilter = ref("all");
const dateFilter = ref(new Date().toISOString().slice(0, 10));

// Status options for filtering
const statusOptions = [
    { value: "all", label: "All Status" },
    { value: "pending", label: "Pending" },
    { value: "confirmed", label: "Confirmed" },
    { value: "cancelled", label: "Cancelled" },
];

// Status badge styling
const statusColors = {
    pending: "bg-amber-50 text-amber-700 ring-amber-600/20",
    confirmed: "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
    cancelled: "bg-rose-50 text-rose-700 ring-rose-600/20",
};

const statusLabels = {
    pending: "Pending",
    confirmed: "Confirmed",
    cancelled: "Cancelled",
};

// Filter appointments based on search and filters
const filteredAppointments = computed(() => {
    let filtered = groupedAppointments;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (group) =>
                group.doctor_name.toLowerCase().includes(query) ||
                group.speciality.toLowerCase().includes(query) ||
                group.date.includes(query)
        );
    }

    if (dateFilter.value) {
        filtered = filtered.filter((group) => group.date === dateFilter.value);
    }

    return filtered;
});

// Calculate statistics
const stats = computed(() => {
    const total = filteredAppointments.value.length;
    const totalBooked = filteredAppointments.value.reduce(
        (sum, group) => sum + group.booked,
        0
    );
    const totalCapacity = filteredAppointments.value.reduce(
        (sum, group) => sum + group.max,
        0
    );
    const utilization =
        totalCapacity > 0 ? Math.round((totalBooked / totalCapacity) * 100) : 0;

    return {
        total,
        totalBooked,
        totalCapacity,
        utilization,
    };
});

const clearFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "all";
    dateFilter.value = "";
};
</script>

<template>
    <Head title="Appointments Management" />

    <AuthenticatedLayout>
        <div
            class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 p-3"
        >
            <div>
                <h2
                    class="font-semibold text-xl sm:text-2xl text-gray-900 leading-tight"
                >
                    Appointments Management
                </h2>
                <p class="text-gray-600 mt-1 text-sm">
                    Manage and monitor all doctor appointments across the system
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                >
                    {{ stats.total }} entries
                </span>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-6 mb-4"
                >
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-blue-600"
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
                                <p class="text-sm font-medium text-gray-600">
                                    Total Sessions
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ stats.total }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-green-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-green-600"
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
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Total Bookings
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ stats.totalBooked }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Total Capacity
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ stats.totalCapacity }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-amber-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Utilization Rate
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ stats.utilization }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6"
                >
                    <div class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Search Appointments
                            </label>
                            <div class="relative">
                                <svg
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search by doctor name, speciality, or date..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                />
                            </div>
                        </div>

                        <div class="w-full sm:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Date
                            </label>
                            <input
                                v-model="dateFilter"
                                type="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            />
                        </div>

                        <button
                            @click="clearFilters"
                            class="px-4 sm:px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium whitespace-nowrap"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div
                    class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200"
                >
                    <div class="px-4 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Doctor Appointment Sessions
                            </h3>
                            <span
                                class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                            >
                                Showing {{ filteredAppointments.length }} of
                                {{ groupedAppointments.length }} sessions
                            </span>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Doctor Information
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Session Date
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Capacity
                                    </th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="group in filteredAppointments"
                                    :key="`${group.doctor_id}-${group.date}`"
                                    class="hover:bg-gray-50/50 transition-colors"
                                >
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-semibold text-gray-900"
                                                >
                                                    {{ group.doctor_name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500 capitalize"
                                                >
                                                    {{ group.speciality }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div
                                            class="text-sm text-gray-900 font-medium"
                                        >
                                            {{
                                                new Date(
                                                    group.date
                                                ).toLocaleDateString("en-US", {
                                                    weekday: "short",
                                                    year: "numeric",
                                                    month: "short",
                                                    day: "numeric",
                                                })
                                            }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ group.date }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <div
                                                    class="text-sm font-semibold text-gray-900"
                                                >
                                                    {{ group.booked }} /
                                                    {{ group.max }}
                                                </div>
                                                <div
                                                    class="w-24 bg-gray-200 rounded-full h-2 mt-1"
                                                >
                                                    <div
                                                        class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                                        :style="{
                                                            width: `${Math.min(
                                                                100,
                                                                (group.booked /
                                                                    group.max) *
                                                                    100
                                                            )}%`,
                                                        }"
                                                        :class="{
                                                            'bg-red-500':
                                                                group.booked /
                                                                    group.max >
                                                                0.9,
                                                            'bg-amber-500':
                                                                group.booked /
                                                                    group.max >
                                                                    0.7 &&
                                                                group.booked /
                                                                    group.max <=
                                                                    0.9,
                                                            'bg-green-500':
                                                                group.booked /
                                                                    group.max <=
                                                                0.7,
                                                        }"
                                                    ></div>
                                                </div>
                                                <div
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    {{
                                                        Math.round(
                                                            (group.booked /
                                                                group.max) *
                                                                100
                                                        )
                                                    }}% booked
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="px-4 py-2 whitespace-nowrap text-sm font-medium"
                                    >
                                        <Link
                                            :href="`/admin/appointments/${group.doctor_id}/${group.date}`"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-xl hover:bg-indigo-100 transition-colors"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-2"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                            View Details
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="lg:hidden">
                        <div class="divide-y divide-gray-200">
                            <div
                                v-for="group in filteredAppointments"
                                :key="`${group.doctor_id}-${group.date}`"
                                class="p-4 hover:bg-gray-50/50 transition-colors"
                            >
                                <div
                                    class="flex justify-between items-start mb-3"
                                >
                                    <div>
                                        <div
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            {{ group.doctor_name }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 capitalize"
                                        >
                                            {{ group.speciality }}
                                        </div>
                                    </div>
                                    <Link
                                        :href="`/admin/appointments/${group.doctor_id}/${group.date}`"
                                        class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg hover:bg-indigo-100 transition-colors"
                                    >
                                        <svg
                                            class="w-3 h-3 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                        View
                                    </Link>
                                </div>
                                <div
                                    class="flex justify-between items-center mb-2"
                                >
                                    <div
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{
                                            new Date(
                                                group.date
                                            ).toLocaleDateString("en-US", {
                                                weekday: "short",
                                                month: "short",
                                                day: "numeric",
                                            })
                                        }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ group.date }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-1">
                                        <div
                                            class="text-sm font-semibold text-gray-900 mb-1"
                                        >
                                            {{ group.booked }} /
                                            {{ group.max }} booked
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-2"
                                        >
                                            <div
                                                class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                                :style="{
                                                    width: `${Math.min(
                                                        100,
                                                        (group.booked /
                                                            group.max) *
                                                            100
                                                    )}%`,
                                                }"
                                                :class="{
                                                    'bg-red-500':
                                                        group.booked /
                                                            group.max >
                                                        0.9,
                                                    'bg-amber-500':
                                                        group.booked /
                                                            group.max >
                                                            0.7 &&
                                                        group.booked /
                                                            group.max <=
                                                            0.9,
                                                    'bg-green-500':
                                                        group.booked /
                                                            group.max <=
                                                        0.7,
                                                }"
                                            ></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{
                                                Math.round(
                                                    (group.booked / group.max) *
                                                        100
                                                )
                                            }}% capacity
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="filteredAppointments.length === 0"
                        class="text-center py-16"
                    >
                        <div class="text-gray-500">
                            <svg
                                class="mx-auto h-16 w-16 text-gray-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">
                                No appointments found
                            </h3>
                            <p
                                class="mt-2 text-sm text-gray-500 max-w-md mx-auto"
                            >
                                {{
                                    searchQuery || dateFilter
                                        ? "No appointments match your current filters. Try adjusting your search criteria."
                                        : "No appointments have been scheduled yet."
                                }}
                            </p>
                            <button
                                v-if="searchQuery || dateFilter"
                                @click="clearFilters"
                                class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors"
                            >
                                Clear all filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
