<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    appointment: Object,
});

const form = useForm({
    status: props.appointment.status,
});

const updateStatus = () => {
    form.put(`/admin/appointments/${props.appointment.id}`, {
        onSuccess: () => {
            alert("Appointment status updated successfully!");
        },
        onError: () => {
            alert("Failed to update appointment status.");
        },
    });
};

const statusOptions = [
    { value: "pending", label: "Pending" },
    { value: "confirmed", label: "Confirmed" },
    { value: "cancelled", label: "Cancelled" },
];

const statusColors = {
    pending: "bg-yellow-100 text-yellow-800",
    confirmed: "bg-green-100 text-green-800",
    cancelled: "bg-red-100 text-red-800",
};
</script>

<template>
    <Head title="Appointment Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Appointment Details
                </h2>
                <Link
                    href="/admin/appointments"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Back to Appointments
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                Patient Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Full Name</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ appointment.first_name }}
                                        {{ appointment.last_name }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ appointment.email }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Phone</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ appointment.phone }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Appointment ID</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        #{{ appointment.id }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                Appointment Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Preferred Date</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ appointment.preferred_date }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Preferred Time</label
                                    >
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ appointment.preferred_time }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Speciality</label
                                    >
                                    <p
                                        class="mt-1 text-sm text-gray-900 capitalize"
                                    >
                                        {{ appointment.speciality }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Status</label
                                    >
                                    <p class="mt-1">
                                        <span
                                            :class="[
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                statusColors[
                                                    appointment.status
                                                ],
                                            ]"
                                        >
                                            {{
                                                appointment.status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                appointment.status.slice(1)
                                            }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6" v-if="appointment.additional_notes">
                            <h3 class="text-lg font-semibold mb-4">
                                Additional Notes
                            </h3>
                            <p
                                class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg"
                            >
                                {{ appointment.additional_notes }}
                            </p>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                Update Status
                            </h3>
                            <form
                                @submit.prevent="updateStatus"
                                class="flex items-end gap-4"
                            >
                                <div class="flex-1">
                                    <label
                                        for="status"
                                        class="block text-sm font-medium text-gray-700"
                                        >Status</label
                                    >
                                    <select
                                        v-model="form.status"
                                        id="status"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
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
                                    :disabled="form.processing"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <div class="text-sm text-gray-500">
                            <p>
                                Created:
                                {{
                                    new Date(
                                        appointment.created_at
                                    ).toLocaleString()
                                }}
                            </p>
                            <p>
                                Last Updated:
                                {{
                                    new Date(
                                        appointment.updated_at
                                    ).toLocaleString()
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
