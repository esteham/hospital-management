<template>
    <Head title="Book Appointment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Book Appointment
            </h2>
            <p class="text-slate-600 mt-1">Schedule with our doctors</p>
        </template>

        <div class="py-2">
            <div class="max-w-7l mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-white/20"
                >
                    <p>Appointment booking form will be here.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
