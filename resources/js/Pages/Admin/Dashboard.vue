<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">
                            Welcome, Admin!
                        </h3>
                        <p class="mb-4">
                            You have administrative privileges. Manage doctors,
                            schedules, and health checks from here.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold">Doctors</h4>
                                <p>Manage doctor accounts and information.</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="font-semibold">Schedules</h4>
                                <p>View and manage appointment schedules.</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="font-semibold">Health Checks</h4>
                                <p>Monitor and manage health check records.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
