<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    packageBookings: Array,
});

const packageBookings = ref(props.packageBookings);

const deletePackageBooking = (id) => {
    if (confirm("Are you sure you want to delete this package booking?")) {
        axios
            .delete(route("admin.package-bookings.destroy", id))
            .then((response) => {
                // Remove the deleted package booking from the local array
                packageBookings.value = packageBookings.value.filter(
                    (packageBooking) => packageBooking.id !== id
                );
                // Show success message
                alert("Package booking deleted successfully.");
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while deleting the package booking.");
            });
    }
};

const confirmPackageBooking = (id) => {
    if (confirm("Are you sure you want to confirm this package booking?")) {
        axios
            .put(route("admin.package-bookings.update", id), {
                status: "confirmed",
            })
            .then((response) => {
                // Update the status in the local array
                const booking = packageBookings.value.find(
                    (pb) => pb.id === id
                );
                if (booking) {
                    booking.status = "confirmed";
                }
                alert("Package booking confirmed successfully.");
            })
            .catch((error) => {
                console.error("Error:", error);
                alert(
                    "An error occurred while confirming the package booking."
                );
            });
    }
};

const cancelPackageBooking = (id) => {
    const reason = prompt("Please enter the reason for cancellation:");
    if (reason !== null && reason.trim() !== "") {
        axios
            .put(route("admin.package-bookings.update", id), {
                status: "cancelled",
                cancellation_reason: reason.trim(),
            })
            .then((response) => {
                // Update the status in the local array
                const booking = packageBookings.value.find(
                    (pb) => pb.id === id
                );
                if (booking) {
                    booking.status = "cancelled";
                    booking.cancellation_reason = reason.trim();
                }
                alert("Package booking cancelled successfully.");
            })
            .catch((error) => {
                console.error("Error:", error);
                alert(
                    "An error occurred while cancelling the package booking."
                );
            });
    } else if (reason === null) {
        // User cancelled the prompt
    } else {
        alert("Cancellation reason is required.");
    }
};
</script>

<template>
    <AppLayout title="Package Bookings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Package Bookings Management
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            User
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Package
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Payment Type
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Amount Paid
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Total Amount
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Receipt
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="packageBooking in packageBookings"
                                        :key="packageBooking.id"
                                    >
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ packageBooking.user.name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{
                                                packageBooking.health_check.name
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ packageBooking.payment_type }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ packageBooking.amount_paid }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ packageBooking.total_amount }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <span
                                                v-if="
                                                    packageBooking.status ===
                                                    'confirmed'
                                                "
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                            >
                                                Confirmed
                                            </span>
                                            <span
                                                v-else-if="
                                                    packageBooking.status ===
                                                    'pending'
                                                "
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                            >
                                                Pending
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                            >
                                                Cancelled
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <a
                                                :href="
                                                    route(
                                                        'admin.package-bookings.download-pdf',
                                                        packageBooking
                                                    )
                                                "
                                                class="text-blue-600 hover:text-blue-900"
                                                target="_blank"
                                            >
                                                Download
                                            </a>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <div class="flex space-x-2">
                                                <button
                                                    v-if="
                                                        packageBooking.status ===
                                                        'pending'
                                                    "
                                                    @click="
                                                        confirmPackageBooking(
                                                            packageBooking.id
                                                        )
                                                    "
                                                    class="text-green-600 hover:text-green-900"
                                                >
                                                    Confirm
                                                </button>
                                                <button
                                                    v-if="
                                                        packageBooking.status ===
                                                        'pending'
                                                    "
                                                    @click="
                                                        cancelPackageBooking(
                                                            packageBooking.id
                                                        )
                                                    "
                                                    class="text-yellow-600 hover:text-yellow-900"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    @click="
                                                        deletePackageBooking(
                                                            packageBooking.id
                                                        )
                                                    "
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
