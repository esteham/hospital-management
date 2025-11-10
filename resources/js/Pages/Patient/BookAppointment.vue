<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    appointments: Array,
});

const showModal = ref(false);
const selectedAppointment = ref(null);

function openModal(appointment) {
    selectedAppointment.value = appointment;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedAppointment.value = null;
}
</script>
<template>
    <Head title="Book Appointment" />

    <AuthenticatedLayout>
        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 sm:p-6"
        >
            <div class="w-full sm:w-auto">
                <nav
                    class="text-xs text-slate-500 mb-1"
                    aria-label="Breadcrumb"
                >
                    <ol class="flex items-center gap-1">
                        <li
                            class="inline-flex items-center gap-2 rounded-xl bg-red-300 text-black px-3 sm:px-4 py-1 text-xs sm:text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
                        >
                            <Link href="/dashboard">Back</Link>
                        </li>
                        <li aria-hidden="true" class="mx-1 text-slate-400">
                            /
                        </li>
                        <li class="text-slate-700 font-medium">Dashboard</li>
                    </ol>
                </nav>
                <h2
                    class="font-bold text-xl sm:text-2xl text-slate-800 leading-tight"
                >
                    Appointment
                </h2>
                <p class="text-slate-600 mt-1 text-sm sm:text-base">
                    Explore preventive care and manage your booked appointment.
                </p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <Link
                    href="/book-appointment"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
                >
                    <svg
                        class="w-3 h-3 sm:w-4 sm:h-4"
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
                    Book Appointment
                </Link>
            </div>
        </div>

        <!-- Booked Appointments Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6">
            <div
                class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-white/20"
            >
                <h3 class="font-bold text-lg sm:text-xl text-slate-800 mb-4">
                    Your Booked Appointments
                </h3>
                <div
                    v-if="appointments && appointments.length > 0"
                    class="space-y-3 sm:space-y-4"
                >
                    <div
                        v-for="appointment in appointments"
                        :key="appointment.id"
                        class="flex flex-col sm:flex-row sm:items-center justify-between p-3 sm:p-4 bg-slate-50 rounded-xl gap-3 sm:gap-0"
                    >
                        <div
                            class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0"
                        >
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"
                            >
                                <svg
                                    class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="font-medium text-slate-800 text-sm sm:text-base"
                                >
                                    Booking ID: {{ appointment.booking_id }}
                                </p>
                                <p class="text-xs sm:text-sm text-slate-600">
                                    {{ appointment.speciality }} -
                                    {{ appointment.preferred_date }} at
                                    {{ appointment.preferred_time }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Status: {{ appointment.status }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center space-x-2 justify-end sm:justify-start"
                        >
                            <button
                                @click="openModal(appointment)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm"
                            >
                                View
                            </button>
                            <a
                                :href="
                                    route(
                                        'patient.appointments.download-pdf',
                                        appointment.id
                                    )
                                "
                                data-inertia="false"
                                download
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 sm:px-3 sm:py-1 rounded-lg text-xs sm:text-sm"
                                >Receipt
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-6 sm:py-8">
                    <svg
                        class="w-12 h-12 sm:w-16 sm:h-16 text-slate-400 mx-auto mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                    <p class="text-slate-600 text-sm sm:text-base">
                        No booked appointments yet.
                    </p>
                    <Link
                        href="/book-appointment"
                        class="text-blue-600 hover:text-blue-800 mt-2 inline-block text-sm sm:text-base"
                        >Book your first appointment</Link
                    >
                </div>
            </div>
        </div>

        <!-- Modal for Appointment Details -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        >
            <div
                class="bg-white rounded-lg p-4 sm:p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto"
            >
                <h3 class="text-base sm:text-lg font-bold mb-4">
                    Appointment Details
                </h3>
                <div v-if="selectedAppointment" class="space-y-3 sm:space-y-2">
                    <p class="text-sm sm:text-base">
                        <strong>Booking ID:</strong>
                        {{ selectedAppointment.booking_id }}
                    </p>
                    <p class="text-sm sm:text-base">
                        <strong>Speciality:</strong>
                        {{ selectedAppointment.speciality }}
                    </p>
                    <p class="text-sm sm:text-base">
                        <strong>Date:</strong>
                        {{ selectedAppointment.preferred_date }}
                    </p>
                    <p class="text-sm sm:text-base">
                        <strong>Time:</strong>
                        {{ selectedAppointment.preferred_time }}
                    </p>
                    <p class="text-sm sm:text-base">
                        <strong>Status:</strong>
                        {{ selectedAppointment.status }}
                    </p>
                </div>
                <div
                    class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-6"
                >
                    <button
                        @click="closeModal"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm sm:text-base order-2 sm:order-1"
                    >
                        OK
                    </button>
                    <a
                        :href="
                            route(
                                'patient.appointments.download-pdf',
                                selectedAppointment.id
                            )
                        "
                        data-inertia="false"
                        download
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm sm:text-base text-center order-1 sm:order-2"
                    >
                        Receipt
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
