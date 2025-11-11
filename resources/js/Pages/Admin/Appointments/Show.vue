<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    appointment: Object,
});

const form = useForm({
    status: props.appointment.status,
});

const updateStatus = () => {
    form.put(`/admin/appointments/${props.appointment.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // In a real app, you'd use a proper notification system
            console.log("Appointment status updated successfully!");
        },
        onError: () => {
            console.error("Failed to update appointment status.");
        },
    });
};

const statusOptions = [
    { value: "pending", label: "Pending", color: "amber" },
    { value: "confirmed", label: "Confirmed", color: "emerald" },
    { value: "cancelled", label: "Cancelled", color: "rose" },
];

const statusColors = {
    pending: "bg-amber-50 text-amber-700 ring-amber-600/20",
    confirmed: "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
    cancelled: "bg-rose-50 text-rose-700 ring-rose-600/20",
};

const statusIcons = {
    pending: `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    `,
    confirmed: `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    `,
    cancelled: `
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M6 18L18 6M6 6l12 12"/>
    `,
};

// Format dates for display
const formattedDate = computed(() => {
    return new Date(props.appointment.preferred_date).toLocaleDateString(
        "en-US",
        {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        }
    );
});

const createdDate = computed(() => {
    return new Date(props.appointment.created_at).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
});

const updatedDate = computed(() => {
    return new Date(props.appointment.updated_at).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
});
</script>

<template>
    <Head :title="`Appointment #${appointment.id} - Details`" />

    <AuthenticatedLayout>
        <div
            class="flex flex-col sm:flex-row p-2 sm:p-3 items-start sm:items-center justify-between gap-3 sm:gap-4"
        >
            <div>
                <h2
                    class="font-semibold text-lg sm:text-xl lg:text-2xl text-gray-900 leading-tight"
                >
                    Appointment Details
                </h2>
                <p class="text-gray-600 mt-1 text-xs sm:text-sm">
                    Complete information for appointment #{{ appointment.id }}
                </p>
            </div>
            <Link
                href="/admin/appointments"
                class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2.5 border border-gray-300 text-xs sm:text-sm font-medium rounded-lg sm:rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
                <svg
                    class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2"
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
                Back to Appointments
            </Link>
        </div>

        <div class="py-2 sm:py-4">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Patient Information Card -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4"
                            >
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Patient Information
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500"
                                        >Appointment ID</span
                                    >
                                    <span
                                        class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium"
                                    >
                                        #{{ appointment.id }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex sm:flex-row items-start sm:items-center sm:space-y-0 sm:space-x-4 mb-6"
                            >
                                <div
                                    class="w-10 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center flex-shrink-0"
                                >
                                    <span class="text-white font-bold text-xl">
                                        {{ appointment.first_name[0]
                                        }}{{ appointment.last_name[0] }}
                                    </span>
                                </div>
                                <div>
                                    <h4
                                        class="text-lg sm:text-xl font-bold text-gray-900"
                                    >
                                        {{ appointment.first_name }}
                                        {{ appointment.last_name }}
                                    </h4>
                                    <p
                                        class="text-gray-600 text-sm sm:text-base"
                                    >
                                        {{ appointment.email }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Contact Information
                                        </label>
                                        <p class="text-sm text-gray-900">
                                            {{ appointment.phone }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Appointment Date
                                        </label>
                                        <p class="text-sm text-gray-900">
                                            {{ formattedDate }}
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Preferred Time
                                        </label>
                                        <p class="text-sm text-gray-900">
                                            {{ appointment.preferred_time }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Medical Speciality
                                        </label>
                                        <p
                                            class="text-sm text-gray-900 capitalize"
                                        >
                                            {{ appointment.speciality }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes Card -->
                        <div
                            v-if="appointment.additional_notes"
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-purple-50 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-purple-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Additional Notes
                                </h3>
                            </div>
                            <div
                                class="bg-gray-50 rounded-xl p-4 border border-gray-200"
                            >
                                <p
                                    class="text-sm text-gray-700 leading-relaxed"
                                >
                                    {{ appointment.additional_notes }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Status Management Card -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-indigo-50 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-indigo-600"
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
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Appointment Status
                                </h3>
                            </div>

                            <!-- Current Status -->
                            <div class="mb-6">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-3"
                                >
                                    Current Status
                                </label>
                                <div class="flex items-center space-x-3">
                                    <span
                                        :class="[
                                            'px-4 py-2 rounded-full text-sm font-medium ring-1 ring-inset',
                                            statusColors[appointment.status],
                                        ]"
                                    >
                                        <span
                                            class="flex items-center space-x-2"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <g
                                                    v-html="
                                                        statusIcons[
                                                            appointment.status
                                                        ]
                                                    "
                                                ></g>
                                            </svg>
                                            <span>{{
                                                statusOptions.find(
                                                    (opt) =>
                                                        opt.value ===
                                                        appointment.status
                                                )?.label
                                            }}</span>
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <!-- Status Update Form -->
                            <form @submit.prevent="updateStatus">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            for="status"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Update Status
                                        </label>
                                        <select
                                            v-model="form.status"
                                            id="status"
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
                                        type="submit"
                                        :disabled="
                                            form.processing ||
                                            form.status === appointment.status
                                        "
                                        class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-indigo-400 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg
                                            v-if="form.processing"
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-5 h-5 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                        {{
                                            form.processing
                                                ? "Updating..."
                                                : "Update Status"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Timeline & Metadata -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-gray-50 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-gray-600"
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
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Timeline
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-2 h-2 bg-green-500 rounded-full mt-2"
                                    ></div>
                                    <div class="flex-1">
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Appointment Created
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ createdDate }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-2 h-2 bg-blue-500 rounded-full mt-2"
                                    ></div>
                                    <div class="flex-1">
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Last Updated
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ updatedDate }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
