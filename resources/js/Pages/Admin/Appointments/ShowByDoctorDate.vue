<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

// Toast notification system
const toasts = ref([]);
function showToast(message, type = "success") {
    const id = Date.now() + Math.random();
    toasts.value.push({ id, message, type });
    setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    }, 3500);
}

const props = defineProps({
    appointments: Array,
    doctor: Object,
    date: String,
});

const searchQuery = ref("");
const statusFilter = ref("all");
const timeFilter = ref("");

// Status management
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

// Filter appointments
const filteredAppointments = computed(() => {
    let filtered = props.appointments;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (appointment) =>
                appointment.first_name.toLowerCase().includes(query) ||
                appointment.last_name.toLowerCase().includes(query) ||
                appointment.email.toLowerCase().includes(query) ||
                appointment.phone.includes(query)
        );
    }

    if (statusFilter.value !== "all") {
        filtered = filtered.filter(
            (appointment) => appointment.status === statusFilter.value
        );
    }

    if (timeFilter.value) {
        filtered = filtered.filter(
            (appointment) => appointment.preferred_time === timeFilter.value
        );
    }

    return filtered;
});

// Statistics
const appointmentStats = computed(() => {
    const total = filteredAppointments.value.length;
    const pending = filteredAppointments.value.filter(
        (a) => a.status === "pending"
    ).length;
    const confirmed = filteredAppointments.value.filter(
        (a) => a.status === "confirmed"
    ).length;
    const cancelled = filteredAppointments.value.filter(
        (a) => a.status === "cancelled"
    ).length;

    return {
        total,
        pending,
        confirmed,
        cancelled,
    };
});

// Status update function
const updateStatus = async (appointmentId, newStatus) => {
    const form = useForm({
        status: newStatus,
    });

    form.put(`/admin/appointments/${appointmentId}`, {
        preserveScroll: true,
        onSuccess: (page) => {
            // Show success message from flash data
            if (page.props.flash?.success) {
                showToast(page.props.flash.success, "success");
            }
        },
        onError: () => {
            showToast("Failed to update appointment status.", "error");
        },
    });
};

// Clear filters
const clearFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "all";
    timeFilter.value = "";
};

// Format date for display
const formattedDate = computed(() => {
    return new Date(props.date).toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
});
</script>

<template>
    <Head :title="`Appointments - ${doctor?.user?.name} - ${date}`" />

    <AuthenticatedLayout>
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Doctor Information Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-4"
                >
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                    >
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3
                                    class="text-lg sm:text-xl font-bold text-gray-900"
                                >
                                    {{ doctor?.user?.name }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ doctor?.designation }}
                                </p>
                                <p class="text-sm text-gray-500 capitalize">
                                    {{ doctor?.speciality }}
                                </p>
                            </div>
                        </div>

                        <div class="text-left sm:text-right w-full sm:w-auto">
                            <Link
                                href="/admin/appointments"
                                class="inline-flex items-center px-4 sm:px-5 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors mb-2 sm:mb-0"
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
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                    />
                                </svg>
                                Back
                            </Link>
                            <p class="text-sm text-gray-500">
                                Appointment Date
                            </p>
                            <p
                                class="text-base sm:text-lg font-semibold text-gray-900"
                            >
                                {{ formattedDate }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
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
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Total Appointments
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ appointmentStats.total }}
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Pending
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ appointmentStats.pending }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-emerald-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-emerald-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Confirmed
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ appointmentStats.confirmed }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 bg-rose-50 rounded-xl mr-4">
                                <svg
                                    class="w-6 h-6 text-rose-600"
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
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Cancelled
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ appointmentStats.cancelled }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-4"
                >
                    <div class="flex flex-col lg:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Search Patients
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
                                    placeholder="Search by patient name, email, or phone..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                />
                            </div>
                        </div>

                        <div class="w-full lg:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Status
                            </label>
                            <select
                                v-model="statusFilter"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="w-full lg:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Time Slot
                            </label>
                            <select
                                v-model="timeFilter"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                                <option value="">All Times</option>
                                <option
                                    v-for="time in [
                                        ...new Set(
                                            appointments.map(
                                                (a) => a.preferred_time
                                            )
                                        ),
                                    ]"
                                    :key="time"
                                    :value="time"
                                >
                                    {{ time }}
                                </option>
                            </select>
                        </div>

                        <button
                            @click="clearFilters"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium whitespace-nowrap"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Appointments Table (Desktop) -->
                <div
                    class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hidden md:block"
                >
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Patient Appointments
                            </h3>
                            <span
                                class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                            >
                                Showing {{ filteredAppointments.length }} of
                                {{ appointments.length }} appointments
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Patient Information
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Contact Details
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Appointment Time
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="appointment in filteredAppointments"
                                    :key="appointment.id"
                                    class="hover:bg-gray-50/50 transition-colors"
                                >
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-semibold text-gray-900"
                                                >
                                                    {{ appointment.first_name }}
                                                    {{ appointment.last_name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    ID:
                                                    {{
                                                        appointment.booking_id ||
                                                        appointment.id
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">
                                            {{ appointment.email }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ appointment.phone }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            {{ appointment.preferred_time }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ appointment.preferred_date }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <select
                                            :value="appointment.status"
                                            @change="
                                                updateStatus(
                                                    appointment.id,
                                                    $event.target.value
                                                )
                                            "
                                            class="px-3 py-1.5 text-sm font-medium rounded-full border-0 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors cursor-pointer"
                                            :class="
                                                statusColors[appointment.status]
                                            "
                                        >
                                            <option
                                                v-for="option in [
                                                    'pending',
                                                    'confirmed',
                                                    'cancelled',
                                                ]"
                                                :key="option"
                                                :value="option"
                                            >
                                                {{ statusLabels[option] }}
                                            </option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-sm font-medium"
                                    >
                                        <Link
                                            :href="`/admin/appointments/${appointment.id}`"
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
                </div>

                <!-- Appointments Cards (Mobile) -->
                <div class="md:hidden space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Patient Appointments
                        </h3>
                        <span
                            class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                        >
                            Showing {{ filteredAppointments.length }} of
                            {{ appointments.length }} appointments
                        </span>
                    </div>

                    <div
                        v-for="appointment in filteredAppointments"
                        :key="appointment.id"
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4"
                    >
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">
                                    {{ appointment.first_name }}
                                    {{ appointment.last_name }}
                                </h4>
                                <p class="text-xs text-gray-500">
                                    ID:
                                    {{
                                        appointment.booking_id || appointment.id
                                    }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-900">
                                    <strong>Email:</strong>
                                    {{ appointment.email }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <strong>Phone:</strong>
                                    {{ appointment.phone }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ appointment.preferred_time }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ appointment.preferred_date }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between">
                                <select
                                    :value="appointment.status"
                                    @change="
                                        updateStatus(
                                            appointment.id,
                                            $event.target.value
                                        )
                                    "
                                    class="px-3 py-1.5 text-sm font-medium rounded-full border-0 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors cursor-pointer"
                                    :class="statusColors[appointment.status]"
                                >
                                    <option
                                        v-for="option in [
                                            'pending',
                                            'confirmed',
                                            'cancelled',
                                        ]"
                                        :key="option"
                                        :value="option"
                                    >
                                        {{ statusLabels[option] }}
                                    </option>
                                </select>

                                <Link
                                    :href="`/admin/appointments/${appointment.id}`"
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
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for Mobile -->
                    <div
                        v-if="filteredAppointments.length === 0"
                        class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-200"
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
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">
                                No appointments found
                            </h3>
                            <p
                                class="mt-2 text-sm text-gray-500 max-w-md mx-auto"
                            >
                                {{
                                    searchQuery ||
                                    statusFilter !== "all" ||
                                    timeFilter
                                        ? "No appointments match your current filters. Try adjusting your search criteria."
                                        : "No appointments scheduled for this doctor on the selected date."
                                }}
                            </p>
                            <button
                                v-if="
                                    searchQuery ||
                                    statusFilter !== 'all' ||
                                    timeFilter
                                "
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

        <!-- Toasts -->
        <div
            class="pointer-events-none fixed inset-x-0 top-4 z-[60] mx-auto flex w-full max-w-xl flex-col items-center gap-2 px-4"
        >
            <div
                v-for="t in toasts"
                :key="t.id"
                class="pointer-events-auto flex w-full items-start gap-3 rounded-2xl bg-white p-3 shadow-lg ring-1 ring-gray-100"
            >
                <div
                    :class="[
                        'mt-0.5 h-2.5 w-2.5 rounded-full',
                        t.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                    ]"
                />
                <div class="text-sm text-gray-800">{{ t.message }}</div>
                <button
                    class="ml-auto rounded-full p-1 hover:bg-gray-100"
                    @click="toasts = toasts.filter((x) => x.id !== t.id)"
                >
                    <span class="sr-only">Dismiss</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        class="h-4 w-4"
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
    </AuthenticatedLayout>
</template>
