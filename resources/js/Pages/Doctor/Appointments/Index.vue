<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    appointments: Array,
});

const appointments = ref(props.appointments || []);
const showModal = ref(false);
const selectedAppointment = ref(null);

const openModal = (appointment) => {
    selectedAppointment.value = appointment;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedAppointment.value = null;
};

const updateStatus = async (appointmentId, status) => {
    try {
        const response = await fetch(`/doctor/appointments/${appointmentId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ status }),
        });

        if (response.ok) {
            const updatedAppointment = await response.json();
            const index = appointments.value.findIndex(
                (app) => app.id === appointmentId
            );
            if (index !== -1) {
                appointments.value[index] = updatedAppointment.appointment;
            }
            alert("Appointment status updated successfully!");
        } else {
            alert("Failed to update appointment status.");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while updating the appointment.");
    }
};
</script>

<template>
    <Head title="My Appointments - Doctor Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <svg
                                class="w-8 h-8 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11M9 11h6"
                                ></path>
                            </svg>
                            <h1 class="ml-2 text-2xl font-medium text-gray-900">
                                My Appointments
                            </h1>
                        </div>

                        <p class="mt-6 text-gray-500 leading-relaxed">
                            Here you can view and manage all your patient
                            appointments.
                        </p>
                    </div>

                    <div
                        class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8"
                    >
                        <div
                            v-if="appointments.length === 0"
                            class="col-span-full text-center py-12"
                        >
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                ></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                No appointments
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                You don't have any appointments yet.
                            </p>
                        </div>

                        <div
                            v-for="appointment in appointments"
                            :key="appointment.id"
                            class="bg-white rounded-lg shadow p-6"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ appointment.first_name }}
                                    {{ appointment.last_name }}
                                </h3>
                                <span
                                    :class="{
                                        'bg-yellow-100 text-yellow-800':
                                            appointment.status === 'pending',
                                        'bg-green-100 text-green-800':
                                            appointment.status === 'confirmed',
                                        'bg-red-100 text-red-800':
                                            appointment.status === 'cancelled',
                                    }"
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                >
                                    {{ appointment.status }}
                                </span>
                            </div>
                            <div class="mt-4 space-y-2">
                                <p class="text-sm text-gray-600">
                                    <strong>Email:</strong>
                                    {{ appointment.email }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Phone:</strong>
                                    {{ appointment.phone }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Date:</strong>
                                    {{ appointment.preferred_date }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Time:</strong>
                                    {{ appointment.preferred_time }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Speciality:</strong>
                                    {{ appointment.speciality }}
                                </p>
                                <p
                                    v-if="appointment.additional_notes"
                                    class="text-sm text-gray-600"
                                >
                                    <strong>Notes:</strong>
                                    {{ appointment.additional_notes }}
                                </p>
                            </div>
                            <div class="mt-6 flex space-x-2">
                                <button
                                    v-if="appointment.status === 'pending'"
                                    @click="
                                        updateStatus(
                                            appointment.id,
                                            'confirmed'
                                        )
                                    "
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Confirm
                                </button>
                                <button
                                    v-if="appointment.status === 'pending'"
                                    @click="
                                        updateStatus(
                                            appointment.id,
                                            'cancelled'
                                        )
                                    "
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="openModal(appointment)"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click="closeModal"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                @click.stop
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Appointment Details
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4 space-y-4" v-if="selectedAppointment">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xl font-semibold text-gray-900">
                                {{ selectedAppointment.first_name }}
                                {{ selectedAppointment.last_name }}
                            </h4>
                            <span
                                :class="{
                                    'bg-yellow-100 text-yellow-800':
                                        selectedAppointment.status ===
                                        'pending',
                                    'bg-green-100 text-green-800':
                                        selectedAppointment.status ===
                                        'confirmed',
                                    'bg-red-100 text-red-800':
                                        selectedAppointment.status ===
                                        'cancelled',
                                }"
                                class="px-3 py-1 text-sm font-medium rounded-full"
                            >
                                {{ selectedAppointment.status }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <p class="text-sm text-gray-600">
                                <strong>Email:</strong>
                                {{ selectedAppointment.email }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Phone:</strong>
                                {{ selectedAppointment.phone }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Date:</strong>
                                {{ selectedAppointment.preferred_date }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Time:</strong>
                                {{ selectedAppointment.preferred_time }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Speciality:</strong>
                                {{ selectedAppointment.speciality }}
                            </p>
                        </div>
                        <div v-if="selectedAppointment.additional_notes">
                            <p class="text-sm text-gray-600">
                                <strong>Notes:</strong>
                                {{ selectedAppointment.additional_notes }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
