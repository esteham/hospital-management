<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Profile
            </h2>
            <p class="text-slate-600 mt-1">Manage your account</p>
        </template>

        <div class="py-2">
            <div class="max-w-7l mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-white/20"
                >
                    <p>Profile settings will be here.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
