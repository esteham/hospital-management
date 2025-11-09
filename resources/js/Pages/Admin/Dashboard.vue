<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    summaries: {
        type: Object,
        default: () => ({
            doctors: 0,
            doctors_percentage: 0,
            patients: 0,
            patients_percentage: 0,
            appointments: 0,
            appointments_today: 0,
            healthChecks: 0,
            pending_reviews: 0,
            staff: 0,
            staff_percentage: 0,
        }),
    },
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2
                        class="font-semibold text-2xl text-gray-800 leading-tight"
                    >
                        Admin Dashboard
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Welcome back! Here's what's happening today.
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="bg-white border rounded-lg px-4 py-2 flex items-center"
                    >
                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                        <span class="text-sm font-medium">{{
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
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8"
                >
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Doctors
                                </p>

                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.doctors }}
                                </p>

                                <p
                                    :class="[
                                        'text-xs mt-1 flex items-center',
                                        summaries.doctors_percentage >= 0
                                            ? 'text-green-500'
                                            : 'text-red-500',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'mr-1',
                                            summaries.doctors_percentage >= 0
                                                ? 'fas fa-arrow-up'
                                                : 'fas fa-arrow-down',
                                        ]"
                                    ></i>
                                    {{
                                        Math.abs(summaries.doctors_percentage)
                                    }}% from last month
                                </p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-full">
                                <i
                                    class="fas fa-user-md text-blue-500 text-xl"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Patients
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.patients }}
                                </p>
                                <p
                                    :class="[
                                        'text-xs mt-1 flex items-center',
                                        summaries.patients_percentage >= 0
                                            ? 'text-green-500'
                                            : 'text-red-500',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'mr-1',
                                            summaries.patients_percentage >= 0
                                                ? 'fas fa-arrow-up'
                                                : 'fas fa-arrow-down',
                                        ]"
                                    ></i>
                                    {{
                                        Math.abs(summaries.patients_percentage)
                                    }}% from last month
                                </p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-full">
                                <i
                                    class="fas fa-user-injured text-green-500 text-xl"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Appointments
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.appointments }}
                                </p>
                                <p
                                    class="text-xs text-blue-500 mt-1 flex items-center"
                                >
                                    <i class="fas fa-circle mr-1"></i>
                                    {{ summaries.appointments_today }}
                                    scheduled today
                                </p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-full">
                                <i
                                    class="fas fa-calendar-check text-purple-500 text-xl"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Health Checks
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.healthChecks }}
                                </p>
                                <p
                                    class="text-xs text-orange-500 mt-1 flex items-center"
                                >
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ summaries.pending_reviews }} pending
                                    reviews
                                </p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-full">
                                <i
                                    class="fas fa-heartbeat text-yellow-500 text-xl"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 stat-card hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Staff
                                </p>
                                <p class="text-3xl font-bold text-gray-800">
                                    {{ summaries.staff }}
                                </p>
                                <p
                                    :class="[
                                        'text-xs mt-1 flex items-center',
                                        summaries.staff_percentage === 0
                                            ? 'text-gray-500'
                                            : summaries.staff_percentage > 0
                                            ? 'text-green-500'
                                            : 'text-red-500',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            'mr-1',
                                            summaries.staff_percentage === 0
                                                ? 'fas fa-minus'
                                                : summaries.staff_percentage > 0
                                                ? 'fas fa-arrow-up'
                                                : 'fas fa-arrow-down',
                                        ]"
                                    ></i>
                                    {{
                                        summaries.staff_percentage === 0
                                            ? "No change"
                                            : Math.abs(
                                                  summaries.staff_percentage
                                              ) + "%"
                                    }}
                                    <span
                                        v-if="summaries.staff_percentage !== 0"
                                        >from last month</span
                                    >
                                </p>
                            </div>
                            <div class="bg-red-50 p-3 rounded-full">
                                <i
                                    class="fas fa-users text-red-500 text-xl"
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-blue-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-user-md text-blue-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Doctors Management
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Manage medical professionals
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Add, edit, or remove doctor accounts and manage
                            their schedules and specializations.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/admin/doctors"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-plus mr-2"></i> Add Doctor
                            </Link>
                            <Link
                                href="/admin/doctors"
                                class="bg-white text-blue-600 border border-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium"
                            >
                                View All
                            </Link>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-green-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-calendar-alt text-green-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Schedule Management
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Organize appointments
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            View, create, and manage appointment schedules
                            across all departments and doctors.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/admin/schedules"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-plus mr-2"></i> New Schedule
                            </Link>
                            <Link
                                href="/admin/schedules"
                                class="bg-white text-green-600 border border-green-600 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors text-sm font-medium"
                            >
                                Calendar View
                            </Link>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 management-card hover:shadow-md hover:border-yellow-200"
                    >
                        <div class="flex items-center mb-4">
                            <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                                <i
                                    class="fas fa-heartbeat text-yellow-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">
                                    Health Records
                                </h4>
                                <p class="text-sm text-gray-500">
                                    Patient health data
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6">
                            Access and manage patient health check records, test
                            results, and medical history.
                        </p>
                        <div class="flex space-x-3">
                            <Link
                                href="/admin/health-checks/create"
                                class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium flex items-center"
                            >
                                <i class="fas fa-file-medical mr-2"></i> New
                                Record
                            </Link>
                            <Link
                                href="/admin/health-checks"
                                class="bg-white text-yellow-600 border border-yellow-600 px-4 py-2 rounded-lg hover:bg-yellow-50 transition-colors text-sm font-medium"
                            >
                                View Reports
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                Recent Appointments
                            </h3>
                            <button class="text-blue-600 text-sm font-medium">
                                View All
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="bg-blue-100 p-2 rounded-lg mr-3"
                                    >
                                        <i
                                            class="fas fa-user-md text-blue-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            Dr. Sarah Johnson
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Cardiology • 10:00 AM
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full"
                                    >Completed</span
                                >
                            </div>
                            <div
                                class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="bg-purple-100 p-2 rounded-lg mr-3"
                                    >
                                        <i
                                            class="fas fa-user-md text-purple-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            Dr. Michael Chen
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Orthopedics • 11:30 AM
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full"
                                    >In Progress</span
                                >
                            </div>
                            <div
                                class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="bg-green-100 p-2 rounded-lg mr-3"
                                    >
                                        <i
                                            class="fas fa-user-md text-green-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            Dr. Emily Wilson
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Pediatrics • 2:15 PM
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full"
                                    >Scheduled</span
                                >
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
                                    class="fas fa-user-plus text-blue-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Add Patient
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-file-invoice text-green-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Generate Report
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-bell text-yellow-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Notifications
                                </p>
                            </button>
                            <button
                                class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg text-center transition-colors"
                            >
                                <i
                                    class="fas fa-cog text-gray-500 text-xl mb-2"
                                ></i>
                                <p class="font-medium text-gray-800 text-sm">
                                    Settings
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
