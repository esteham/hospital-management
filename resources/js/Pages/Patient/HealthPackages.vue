<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import api from "@/lib/api";

/* state */
const bookedPackages = ref([]);
const loading = ref(true);
const errorMsg = ref("");

/* utils */
const formatCurrency = (n) => {
    const num = Number(n ?? 0);
    try {
        return new Intl.NumberFormat("bn-BD", {
            style: "currency",
            currency: "BDT",
            maximumFractionDigits: 0,
        }).format(num);
    } catch {
        return `৳${num.toLocaleString()}`;
    }
};
const formatDate = (d) => {
    const dt = new Date(d);
    return isNaN(dt) ? "-" : dt.toLocaleDateString();
};
const statusClass = (s) => {
    if (s === "confirmed") return "bg-green-100 text-green-800 ring-green-200";
    if (s === "pending") return "bg-yellow-100 text-yellow-800 ring-yellow-200";
    return "bg-red-100 text-red-800 ring-red-200";
};

/* derived */
const hasData = computed(
    () => !loading.value && bookedPackages.value.length > 0
);

/* fetch */
const fetchBookedPackages = async () => {
    loading.value = true;
    errorMsg.value = "";
    try {
        const res = await api.get("/api/bookings");
        const data = res?.data?.data ?? res?.data ?? [];
        bookedPackages.value = Array.isArray(data) ? data : [];
    } catch (err) {
        console.error("Error fetching booked packages:", err);
        errorMsg.value =
            "Unable to load your bookings right now. Please try again.";
    } finally {
        loading.value = false;
    }
};

onMounted(fetchBookedPackages);
</script>

<template>
    <Head title="Health Packages" />

    <AuthenticatedLayout>
        <!-- Content -->
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Top info / hint -->
                <div
                    class="flex items-start sm:items-center justify-between gap-3"
                >
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
                                <li
                                    aria-hidden="true"
                                    class="mx-1 text-slate-400"
                                >
                                    /
                                </li>
                                <li class="text-slate-700 font-medium">
                                    Health Packages
                                </li>
                            </ol>
                        </nav>
                        <h2
                            class="font-bold text-2xl text-slate-800 leading-tight"
                        >
                            Health Packages
                        </h2>
                        <p class="text-slate-600 mt-1">
                            Explore preventive care and manage your booked
                            packages.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <Link
                            href="/#health-s"
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
                            Book Package
                        </Link>
                    </div>
                </div>

                <!-- Error state -->
                <div
                    v-if="errorMsg"
                    class="rounded-xl border border-red-200 bg-red-50 text-red-800 p-4"
                >
                    {{ errorMsg }}
                </div>

                <!-- Loading skeletons -->
                <div
                    v-if="loading"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="i in 6"
                        :key="i"
                        class="rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm"
                    >
                        <div class="animate-pulse space-y-3">
                            <div class="h-5 w-1/2 bg-slate-200 rounded"></div>
                            <div class="h-4 w-1/3 bg-slate-200 rounded"></div>
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <div class="h-4 bg-slate-200 rounded"></div>
                                <div class="h-4 bg-slate-200 rounded"></div>
                                <div class="h-4 bg-slate-200 rounded"></div>
                                <div class="h-4 bg-slate-200 rounded"></div>
                            </div>
                            <div class="h-3 w-1/3 bg-slate-200 rounded"></div>
                        </div>
                    </div>
                </div>

                <!-- List -->
                <div
                    v-else-if="hasData"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <article
                        v-for="booking in bookedPackages"
                        :key="booking.id"
                        class="group rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div
                            class="flex items-start justify-between gap-3 mb-3"
                        >
                            <div>
                                <h4
                                    class="font-semibold text-slate-800 group-hover:text-slate-900 transition-colors"
                                >
                                    {{
                                        booking.health_check?.name ||
                                        `Package #${booking.health_check_id}`
                                    }}
                                </h4>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    ID: {{ booking.id }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1"
                                :class="statusClass(booking.status)"
                            >
                                {{
                                    booking.status?.charAt(0).toUpperCase() +
                                    booking.status?.slice(1)
                                }}
                            </span>
                        </div>

                        <dl
                            class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-slate-700"
                        >
                            <div class="space-y-0.5">
                                <dt class="text-slate-500">Price</dt>
                                <dd class="font-medium">
                                    {{
                                        booking.health_check?.price
                                            ? formatCurrency(
                                                  booking.health_check.price
                                              )
                                            : formatCurrency(
                                                  booking.total_amount
                                              )
                                    }}
                                </dd>
                            </div>
                            <div class="space-y-0.5">
                                <dt class="text-slate-500">Payment</dt>
                                <dd class="font-medium">
                                    {{ booking.payment_type }}
                                </dd>
                            </div>
                            <div class="space-y-0.5">
                                <dt class="text-slate-500">Paid</dt>
                                <dd class="font-medium">
                                    {{ formatCurrency(booking.amount_paid) }}
                                </dd>
                            </div>
                            <div class="space-y-0.5">
                                <dt class="text-slate-500">Total</dt>
                                <dd class="font-medium">
                                    {{ formatCurrency(booking.total_amount) }}
                                </dd>
                            </div>
                        </dl>

                        <div
                            v-if="booking.cancellation_reason"
                            class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3"
                        >
                            <p class="text-xs font-medium text-red-800">
                                Notice
                            </p>
                            <p class="text-xs text-red-700 mt-1">
                                {{ booking.cancellation_reason }}
                            </p>
                        </div>

                        <div
                            class="mt-4 flex items-center justify-between text-xs text-slate-500"
                        >
                            <span
                                >Booked on
                                {{ formatDate(booking.created_at) }}</span
                            >

                            <!-- Example actions (wire up as needed) -->
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="`/patient/appointments`"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 font-medium text-slate-700 hover:bg-slate-50"
                                >
                                    View Details
                                </Link>
                                <!-- Placeholder for future invoice/receipt -->
                                <Link
                                    :href="
                                        route(
                                            'patient.appointments.download-pdf',
                                            { appointment: booking.id }
                                        )
                                    "
                                    class="inline-flex items-center gap-1 rounded-lg bg-slate-800 text-white px-2.5 py-1.5 font-medium hover:bg-slate-900"
                                    >Receipt</Link
                                >
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Empty state -->
                <div
                    v-else
                    class="rounded-2xl border border-slate-200/60 bg-white p-10 text-center shadow-sm"
                >
                    <div
                        class="mx-auto mb-4 w-16 h-16 rounded-2xl bg-slate-100 grid place-items-center"
                    >
                        <svg
                            class="w-8 h-8 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                    </div>
                    <h3 class="text-slate-800 font-semibold">
                        No packages booked yet
                    </h3>
                    <p class="text-slate-600 text-sm mt-1">
                        Browse our health packages and book your first checkup.
                    </p>
                    <div class="mt-4">
                        <Link
                            href="/book-appointment"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 text-white px-4 py-2.5 text-sm font-medium shadow-sm hover:bg-green-700 transition-colors"
                        >
                            Explore Packages
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
