<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Medical Records" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Medical Records
            </h2>
            <p class="text-slate-600 mt-1">Access your health history</p>
        </template>

        <div class="py-2">
            <div class="max-w-7l mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-white/20"
                >
                    <p>Medical records content will be here.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
