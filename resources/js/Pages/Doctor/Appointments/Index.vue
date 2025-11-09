<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    appointments: Array,
});

const appointments = ref(props.appointments || []);
const showModal = ref(false);
const selectedAppointment = ref(null);
const selectedDate = ref(new Date().toISOString().split("T")[0]);
const searchQuery = ref("");
const statusFilter = ref("all");
const isLoading = ref(false);

// Status options for filter
const statusOptions = [
    { value: "all", label: "All Status", color: "gray" },
    { value: "pending", label: "Pending", color: "yellow" },
    { value: "confirmed", label: "Confirmed", color: "green" },
    { value: "cancelled", label: "Cancelled", color: "red" },
];

const filteredAppointments = computed(() => {
    let filtered = appointments.value.filter((app) => {
        const name = `${app.first_name} ${app.last_name}`.toLowerCase();
        const email = app.email.toLowerCase();
        const date = app.preferred_date;
        const query = searchQuery.value.toLowerCase();

        const matchesSearch =
            name.includes(query) ||
            email.includes(query) ||
            date.includes(query);

        if (searchQuery.value) {
            return matchesSearch;
        } else {
            const matchesStatus =
                statusFilter.value === "all" ||
                app.status === statusFilter.value;
            return matchesSearch && matchesStatus;
        }
    });

    // Filter by selected date if not "all" and not searching
    if (selectedDate.value && !searchQuery.value) {
        filtered = filtered.filter(
            (app) => app.preferred_date === selectedDate.value
        );
    }

    // Group by date
    const grouped = {};
    filtered.forEach((app) => {
        const date = app.preferred_date;
        if (!grouped[date]) grouped[date] = [];
        grouped[date].push(app);
    });
    return grouped;
});

const getStatusColor = (status) => {
    const colors = {
        pending: "bg-amber-50 text-amber-700 ring-amber-600/20",
        confirmed: "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
        cancelled: "bg-rose-50 text-rose-700 ring-rose-600/20",
    };
    return colors[status] || "bg-gray-50 text-gray-700 ring-gray-600/20";
};

const getTimeColor = (time) => {
    const hour = parseInt(time.split(":")[0]);
    if (hour < 12) return "bg-blue-50 text-blue-700 ring-blue-600/20";
    if (hour < 17) return "bg-amber-50 text-amber-700 ring-amber-600/20";
    return "bg-indigo-50 text-indigo-700 ring-indigo-600/20";
};

const openModal = (appointment) => {
    selectedAppointment.value = appointment;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedAppointment.value = null;
};

const showNotification = (message, type = "info") => {
    // You can integrate with a proper notification system here
    const alertClass = type === "success" ? "alert-success" : "alert-error";
    alert(message); // Replace with toast notification
};

// Format date for display
const formatDisplayDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// Clear filters
const clearFilters = () => {
    searchQuery.value = "";
    selectedDate.value = new Date().toISOString().split("T")[0];
    statusFilter.value = "all";
};
</script>

<template>
    <Head title="My Appointments - Doctor Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50/30 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="p-3 bg-white rounded-2xl shadow-sm border border-gray-100"
                            >
                                <svg
                                    class="w-7 h-7 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">
                                    Appointments
                                </h1>
                                <p class="text-gray-600 mt-1">
                                    Manage and track your patient appointments
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">
                                Total Appointments
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ appointments.length }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Filters Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8"
                >
                    <div class="flex flex-col lg:flex-row gap-4 items-end">
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
                                    placeholder="Search by patient name, email, or date..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                />
                            </div>
                        </div>

                        <div class="w-full lg:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Date
                            </label>
                            <input
                                v-model="selectedDate"
                                type="date"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            />
                        </div>

                        <div class="w-full lg:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Status
                            </label>
                            <select
                                v-model="statusFilter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                                <option
                                    v-for="option in statusOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <button
                            @click="clearFilters"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium"
                        >
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Appointments List -->
                <div
                    v-if="Object.keys(filteredAppointments).length === 0"
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center"
                >
                    <div class="max-w-md mx-auto">
                        <div
                            class="p-4 bg-gray-50 rounded-2xl inline-flex mb-6"
                        >
                            <svg
                                class="w-8 h-8 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            No appointments found
                        </h3>
                        <p class="text-gray-500 mb-6">
                            No appointments match your current filters. Try
                            adjusting your search criteria.
                        </p>
                        <button
                            @click="clearFilters"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium"
                        >
                            Clear all filters
                        </button>
                    </div>
                </div>

                <div v-else class="space-y-8">
                    <div
                        v-for="(apps, date) in filteredAppointments"
                        :key="date"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ formatDisplayDate(date) }}
                            </h2>
                            <span
                                class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium"
                            >
                                {{ apps.length }} appointment{{
                                    apps.length !== 1 ? "s" : ""
                                }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            <div
                                v-for="appointment in apps"
                                :key="appointment.id"
                                class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                            >
                                <div
                                    class="flex items-start justify-between mb-4"
                                >
                                    <div>
                                        <h3
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ appointment.first_name }}
                                            {{ appointment.last_name }}
                                        </h3>
                                        <p class="text-gray-600 text-sm mt-1">
                                            {{ appointment.email }}
                                        </p>
                                    </div>
                                    <span
                                        :class="[
                                            'px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset',
                                            getStatusColor(appointment.status),
                                        ]"
                                    >
                                        {{ appointment.status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2 text-gray-400"
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
                                        <span>{{
                                            appointment.preferred_time
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                                            />
                                        </svg>
                                        <span>{{
                                            appointment.speciality
                                        }}</span>
                                    </div>
                                    <div
                                        class="flex items-center text-sm text-gray-600 col-span-2"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                            />
                                        </svg>
                                        <span>{{ appointment.phone }}</span>
                                    </div>
                                </div>

                                <div
                                    v-if="appointment.additional_notes"
                                    class="mb-6"
                                >
                                    <p
                                        class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200"
                                    >
                                        <strong
                                            class="font-medium text-gray-900"
                                            >Notes:</strong
                                        >
                                        {{ appointment.additional_notes }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <Link
                                        :href="`/doctor/appointments/${appointment.id}`"
                                        class="flex-1 min-w-[120px] px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors flex items-center justify-center space-x-2"
                                    >
                                        <svg
                                            class="w-4 h-4"
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
                                        <span>Details and Make Prescription
                                        </span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
