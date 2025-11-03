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
        <div class="flex items-start p-6 sm:items-center justify-between gap-3">
            <div>
                <nav
                    class="text-xs text-slate-500 mb-1"
                    aria-label="Breadcrumb"
                >
                    <ol class="flex items-center gap-1">
                        <li
                            class="inline-flex items-center gap-2 rounded-xl bg-red-300 text-black px-4 py-1 text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
                        >
                            <Link href="/dashboard">Back</Link>
                        </li>
                        <li aria-hidden="true" class="mx-1 text-slate-400">
                            /
                        </li>
                        <li class="text-slate-700 font-medium">Dashboard</li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Appoitment
                </h2>
                <p class="text-slate-600 mt-1">
                    Explore preventive care and manage your booked appoitment.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    href="/book-appointment"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
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
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Book Appoitment
                </Link>
            </div>
        </div>

        <!-- Booked Appointments Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-white/20"
            >
                <h3 class="font-bold text-xl text-slate-800 mb-4">
                    Your Booked Appointments
                </h3>
                <div
                    v-if="appointments && appointments.length > 0"
                    class="space-y-4"
                >
                    <div
                        v-for="appointment in appointments"
                        :key="appointment.id"
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"
                    >
                        <div class="flex items-center space-x-4">
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
                                    ></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">
                                    Booking ID: {{ appointment.booking_id }}
                                </p>
                                <p class="text-sm text-slate-600">
                                    {{ appointment.speciality }} -
                                    {{ appointment.preferred_date }} at
                                    {{ appointment.preferred_time }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Status: {{ appointment.status }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="openModal(appointment)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm"
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
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm"
                                >Recept
                            </a>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8">
                    <svg
                        class="w-16 h-16 text-slate-400 mx-auto mb-4"
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
                    <p class="text-slate-600">No booked appointments yet.</p>
                    <Link
                        href="/book-appointment"
                        class="text-blue-600 hover:text-blue-800 mt-2 inline-block"
                        >Book your first appointment</Link
                    >
                </div>
            </div>
        </div>

        <!-- Modal for Appointment Details -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-bold mb-4">Appointment Details</h3>
                <div v-if="selectedAppointment" class="space-y-2">
                    <p>
                        <strong>Booking ID:</strong>
                        {{ selectedAppointment.booking_id }}
                    </p>
                    <p>
                        <strong>Speciality:</strong>
                        {{ selectedAppointment.speciality }}
                    </p>
                    <p>
                        <strong>Date:</strong>
                        {{ selectedAppointment.preferred_date }}
                    </p>
                    <p>
                        <strong>Time:</strong>
                        {{ selectedAppointment.preferred_time }}
                    </p>
                    <p>
                        <strong>Status:</strong>
                        {{ selectedAppointment.status }}
                    </p>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button
                        @click="closeModal"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"
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
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
                    >
                        Receipt
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
