<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>
<template>
    <Head title="Book Appointment" />

    <AuthenticatedLayout>
        <div class="flex items-start p-6 sm:items-center justify-between gap-3">
            <div>
                <nav
                    class="text-xs text-slate-500 mb-1"
                    aria-label="Breadcrumb"
                >
                    <ol class="flex items-center gap-1">
                        <li
                            class="inline-flex items-center gap-2 rounded-xl bg-red-300 text-black px-4 py-1 text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
                        >
                            <Link href="/dashboard">Dashboard</Link>
                        </li>
                        <li aria-hidden="true" class="mx-1 text-slate-400">
                            /
                        </li>
                        <li class="text-slate-700 font-medium">
                            Book Appoitment
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Appoitment
                </h2>
                <p class="text-slate-600 mt-1">
                    Explore preventive care and manage your booked appoitment.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    href="/book-appointment"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-blue-800 hover:text-white transition-colors"
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
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    Book Appoitment
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
