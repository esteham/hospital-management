<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    packageBookings: Array,
});

const packageBookings = ref(props.packageBookings);
const searchQuery = ref("");
const statusFilter = ref("all");
const paymentTypeFilter = ref("all");

// Status options for filtering
const statusOptions = [
    { value: "all", label: "All Status" },
    { value: "pending", label: "Pending" },
    { value: "confirmed", label: "Confirmed" },
    { value: "cancelled", label: "Cancelled" },
];

const paymentTypeOptions = [
    { value: "all", label: "All Payment Types" },
    { value: "cash", label: "Cash" },
    { value: "card", label: "Card" },
    { value: "online", label: "Online" },
];

// Status styling
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

// Filter bookings
const filteredBookings = computed(() => {
    let filtered = packageBookings.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (booking) =>
                booking.user.name.toLowerCase().includes(query) ||
                booking.health_check.name.toLowerCase().includes(query) ||
                booking.payment_type.toLowerCase().includes(query)
        );
    }

    if (statusFilter.value !== "all") {
        filtered = filtered.filter(
            (booking) => booking.status === statusFilter.value
        );
    }

    if (paymentTypeFilter.value !== "all") {
        filtered = filtered.filter(
            (booking) => booking.payment_type === paymentTypeFilter.value
        );
    }

    return filtered;
});

// Calculate statistics
const stats = computed(() => {
    const total = filteredBookings.value.length;
    const totalRevenue = filteredBookings.value.reduce(
        (sum, booking) => sum + parseFloat(booking.amount_paid),
        0
    );
    const pending = filteredBookings.value.filter(
        (b) => b.status === "pending"
    ).length;
    const confirmed = filteredBookings.value.filter(
        (b) => b.status === "confirmed"
    ).length;
    const cancelled = filteredBookings.value.filter(
        (b) => b.status === "cancelled"
    ).length;

    return {
        total,
        totalRevenue,
        pending,
        confirmed,
        cancelled,
    };
});

const deletePackageBooking = async (id) => {
    if (
        confirm(
            "Are you sure you want to delete this package booking? This action cannot be undone."
        )
    ) {
        try {
            await axios.delete(route("admin.package-bookings.destroy", id));
            // Remove the deleted package booking from the local array
            packageBookings.value = packageBookings.value.filter(
                (packageBooking) => packageBooking.id !== id
            );
            // Show success message (in real app, use toast notification)
            console.log("Package booking deleted successfully.");
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while deleting the package booking.");
        }
    }
};

const confirmPackageBooking = async (id) => {
    if (confirm("Are you sure you want to confirm this package booking?")) {
        try {
            await axios.put(route("admin.package-bookings.update", id), {
                status: "confirmed",
            });
            // Update the status in the local array
            const booking = packageBookings.value.find((pb) => pb.id === id);
            if (booking) {
                booking.status = "confirmed";
            }
            console.log("Package booking confirmed successfully.");
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while confirming the package booking.");
        }
    }
};

const cancelPackageBooking = async (id) => {
    const reason = prompt("Please enter the reason for cancellation:");
    if (reason !== null && reason.trim() !== "") {
        try {
            await axios.put(route("admin.package-bookings.update", id), {
                status: "cancelled",
                cancellation_reason: reason.trim(),
            });
            // Update the status in the local array
            const booking = packageBookings.value.find((pb) => pb.id === id);
            if (booking) {
                booking.status = "cancelled";
                booking.cancellation_reason = reason.trim();
            }
            console.log("Package booking cancelled successfully.");
        } catch (error) {
            console.error("Error:", error);
            alert("An error occurred while cancelling the package booking.");
        }
    } else if (reason === null) {
        // User cancelled the prompt
    } else {
        alert("Cancellation reason is required.");
    }
};

const clearFilters = () => {
    searchQuery.value = "";
    statusFilter.value = "all";
    paymentTypeFilter.value = "all";
};

// Format currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    }).format(amount);
};
</script>

<template>
    <AppLayout title="Package Bookings Management">
        <div class="flex p-4 items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    Package Bookings Management
                </h2>
                <p class="text-gray-600 mt-1 text-sm">
                    Manage and monitor all health package bookings across the
                    system
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                >
                    {{ stats.total }} bookings
                </span>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
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
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Total Bookings
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
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">
                                    Total Revenue
                                </p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ formatCurrency(stats.totalRevenue) }}
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
                                    {{ stats.pending }}
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
                                    {{ stats.cancelled }}
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
                                Search Bookings
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
                                    placeholder="Search by user name, package name, or payment type..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
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

                        <div class="w-full lg:w-48">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Payment Type
                            </label>
                            <select
                                v-model="paymentTypeFilter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                                <option
                                    v-for="option in paymentTypeOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <button
                            @click="clearFilters"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium whitespace-nowrap"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div
                    class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200"
                >
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Package Bookings
                            </h3>
                            <span
                                class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full"
                            >
                                Showing {{ filteredBookings.length }} of
                                {{ packageBookings.length }} bookings
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
                                        Booking Information
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Payment Details
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                    >
                                        Document
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
                                    v-for="booking in filteredBookings"
                                    :key="booking.id"
                                    class="hover:bg-gray-50/50 transition-colors"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-4">
                                            <div
                                                class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-white font-semibold text-sm"
                                                >
                                                    {{
                                                        booking.user.name
                                                            .split(" ")
                                                            .map((n) => n[0])
                                                            .join("")
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="flex items-center space-x-2 mb-2"
                                                >
                                                    <p
                                                        class="text-sm font-semibold text-gray-900 truncate"
                                                    >
                                                        {{ booking.user.name }}
                                                    </p>
                                                    <span
                                                        class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full"
                                                    >
                                                        {{
                                                            booking.payment_type
                                                        }}
                                                    </span>
                                                </div>
                                                <p
                                                    class="text-sm text-gray-600 mb-1"
                                                >
                                                    {{
                                                        booking.health_check
                                                            .name
                                                    }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    Booking ID: {{ booking.id }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >Amount Paid:</span
                                                >
                                                <span
                                                    class="text-sm font-semibold text-gray-900"
                                                >
                                                    {{
                                                        formatCurrency(
                                                            booking.amount_paid
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >Total Amount:</span
                                                >
                                                <span
                                                    class="text-sm font-medium text-gray-700"
                                                >
                                                    {{
                                                        formatCurrency(
                                                            booking.total_amount
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                            <div
                                                v-if="
                                                    booking.cancellation_reason
                                                "
                                                class="mt-2"
                                            >
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    <strong
                                                        >Cancellation
                                                        Reason:</strong
                                                    >
                                                    {{
                                                        booking.cancellation_reason
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="[
                                                'px-3 py-1.5 rounded-full text-sm font-medium ring-1 ring-inset flex items-center space-x-2',
                                                statusColors[booking.status],
                                            ]"
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
                                                            booking.status
                                                        ]
                                                    "
                                                ></g>
                                            </svg>
                                            <span>{{
                                                booking.status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                booking.status.slice(1)
                                            }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <!-- Download Receipt -->
                                        <a
                                            :href="
                                                route(
                                                    'admin.package-bookings.download-pdf',
                                                    booking
                                                )
                                            "
                                            target="_blank"
                                            class="inline-flex items-center p-2 bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors"
                                            title="Download Receipt"
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
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                /></svg
                                            >Download
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <!-- Action Buttons -->
                                            <div
                                                class="flex items-center space-x-1"
                                            >
                                                <button
                                                    v-if="
                                                        booking.status ===
                                                        'pending'
                                                    "
                                                    @click="
                                                        confirmPackageBooking(
                                                            booking.id
                                                        )
                                                    "
                                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-100 transition-colors"
                                                    title="Confirm Booking"
                                                >
                                                    <svg
                                                        class="w-4 h-4 mr-1"
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
                                                    Confirm
                                                </button>
                                                <button
                                                    v-if="
                                                        booking.status ===
                                                        'pending'
                                                    "
                                                    @click="
                                                        cancelPackageBooking(
                                                            booking.id
                                                        )
                                                    "
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 text-sm font-medium rounded-lg hover:bg-amber-100 transition-colors"
                                                    title="Cancel Booking"
                                                >
                                                    <svg
                                                        class="w-4 h-4 mr-1"
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
                                                    Cancel
                                                </button>
                                                <button
                                                    @click="
                                                        deletePackageBooking(
                                                            booking.id
                                                        )
                                                    "
                                                    class="inline-flex items-center p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                                    title="Delete Booking"
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
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="filteredBookings.length === 0"
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
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">
                                No package bookings found
                            </h3>
                            <p
                                class="mt-2 text-sm text-gray-500 max-w-md mx-auto"
                            >
                                {{
                                    searchQuery ||
                                    statusFilter !== "all" ||
                                    paymentTypeFilter !== "all"
                                        ? "No bookings match your current filters. Try adjusting your search criteria."
                                        : "No package bookings have been made yet."
                                }}
                            </p>
                            <button
                                v-if="
                                    searchQuery ||
                                    statusFilter !== 'all' ||
                                    paymentTypeFilter !== 'all'
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
    </AppLayout>
</template>
