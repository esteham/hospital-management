<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    summaries: {
        type: Object,
        default: () => ({
            services: 0,
            services_percentage: 0,
            bookings: 0,
            bookings_today: 0,
            pending_results: 0,
            total_results: 0,
        }),
    },
    recentBookings: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Diagnostic Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0"
            >
                <div>
                    <h2
                        class="font-semibold text-xl md:text-2xl text-gray-800 leading-tight"
                    >
                        Diagnostic Dashboard
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Manage diagnostic services and monitor bookings.
                    </p>
                </div>
                <div class="flex items-center">
                    <div
                        class="bg-white border rounded-lg px-3 md:px-4 py-2 flex items-center"
                    >
                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                        <span class="text-xs md:text-sm font-medium">{{
                            new Date().toLocaleDateString("en-US", {
                                weekday: "long",
                                year: "numeric",
                                month: "long",
                                day: "numeric",
                            })
                        }}</span>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Summary Stats -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8"
                >
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Services
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.services }}
                                </p>
                                <p
                                    :class="[
                                        'text-xs mt-1 flex items-center',
                                        summaries.services_percentage >= 0
                                            ? 'text-green-500'
                                            : 'text-red-500',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'mr-1',
                                            summaries.services_percentage >= 0
                                                ? 'fas fa-arrow-up'
                                                : 'fas fa-arrow-down',
                                        ]"
                                    ></i>
                                    {{
                                        Math.abs(summaries.services_percentage)
                                    }}% from last month
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Total Bookings
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.bookings }}
                                </p>
                                <p
                                    class="text-xs text-blue-500 mt-1 flex items-center"
                                >
                                    <i class="fas fa-circle mr-1"></i>
                                    {{ summaries.bookings_today }}
                                    scheduled today
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Pending Results
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.pending_results }}
                                </p>
                                <p
                                    class="text-xs text-orange-500 mt-1 flex items-center"
                                >
                                    <i class="fas fa-clock mr-1"></i>
                                    out of {{ summaries.total_results }} total
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Completed Results
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{
                                        summaries.total_results -
                                        summaries.pending_results
                                    }}
                                </p>
                                <p
                                    class="text-xs text-green-500 mt-1 flex items-center"
                                >
                                    <i class="fas fa-check-circle mr-1"></i>
                                    delivered
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-blue-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-sky-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-flask text-blue-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Diagnostic Services
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Manage test services
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Add, edit, or remove diagnostic services and manage
                            pricing and categories.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/diagnostic/services"
                                class="bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-plus mr-2"></i> Add Service
                            </Link>
                            <Link
                                href="/diagnostic/services"
                                class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium"
                            >
                                All Services
                            </Link>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-green-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-calendar-check text-green-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Bookings Management
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Handle test bookings
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            View and manage diagnostic test bookings, update
                            statuses and assign technicians.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/diagnostic/bookings"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-list mr-2"></i> View Bookings
                            </Link>
                            <Link
                                href="/diagnostic/bookings/today"
                                class="bg-white text-green-600 border border-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors text-sm font-medium"
                            >
                                Today's Bookings
                            </Link>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-yellow-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-file-medical text-yellow-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Test Results
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Upload and deliver results
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Upload test results, mark as delivered, and track
                            completion status.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/diagnostic/results/pending"
                                class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-upload mr-2"></i> Upload
                                Results
                            </Link>
                            <Link
                                href="/diagnostic/results"
                                class="bg-white text-yellow-600 border border-yellow-600 px-4 py-2 rounded-lg hover:bg-yellow-50 transition-colors text-sm font-medium"
                            >
                                View Results
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div
                    class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8"
                >
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                Recent Bookings
                            </h3>
                            <Link
                                href="/diagnostic/bookings"
                                class="text-blue-600 text-sm font-medium"
                            >
                                View All
                            </Link>
                        </div>
                        <div class="space-y-4">
                            <div
                                v-for="booking in recentBookings"
                                :key="booking.id"
                                class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            {{ booking.service_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ booking.patient_name }} •
                                            {{ booking.booking_time }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    :class="[
                                        'text-xs px-2 py-1 rounded-full',
                                        booking.status === 'completed'
                                            ? 'bg-green-100 text-green-800'
                                            : booking.status === 'in_progress'
                                            ? 'bg-blue-100 text-blue-800'
                                            : 'bg-yellow-100 text-yellow-800',
                                    ]"
                                >
                                    {{
                                        booking.status
                                            .replace("_", " ")
                                            .toUpperCase()
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                Quick Actions
                            </h3>
                            <button class="text-blue-600 text-sm font-medium">
                                More Options
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-plus-circle text-blue-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    New Service
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-calendar-plus text-green-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Schedule Test
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-file-upload text-yellow-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Upload Result
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-chart-line text-gray-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Reports
                                </p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.management-card {
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}

.management-card:hover {
    border-left-color: currentColor;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
