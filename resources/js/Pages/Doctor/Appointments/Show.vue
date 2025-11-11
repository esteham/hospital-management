<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    appointment: Object,
});

const appointment = ref(props.appointment);

// form fields
const diagnosis = ref("");
const medicines = ref([{ name: "", dosage: "", duration: "" }]);
const advice = ref("");
const isSubmitting = ref(false);

const addMedicineRow = () => {
    medicines.value.push({ name: "", dosage: "", duration: "" });
};

const removeMedicineRow = (index) => {
    medicines.value.splice(index, 1);
};

const getStatusColor = (status) => {
    const colors = {
        pending: "bg-amber-50 text-amber-700 ring-amber-600/20",
        confirmed: "bg-emerald-50 text-emerald-700 ring-emerald-600/20",
        cancelled: "bg-rose-50 text-rose-700 ring-rose-600/20",
    };
    return colors[status] || "bg-gray-50 text-gray-700 ring-gray-600/20";
};

const submitPrescription = async () => {
    isSubmitting.value = true;
    try {
        // make structured prescription text
        const prescriptionText = `
Diagnosis: ${diagnosis.value}

${medicines.value
    .map(
        (m, i) =>
            `${i + 1}) ${m.name || "—"} — ${m.dosage || "—"} — ${
                m.duration || "—"
            }`
    )
    .join("\n")}

Advice: ${advice.value}
`.trim();

        const response = await fetch(
            `/doctor/appointments/${appointment.value.id}/prescription`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    prescription_text: prescriptionText,
                }),
            }
        );

        if (response.ok) {
            showNotification("Prescription added successfully!", "success");
            // Reset form
            diagnosis.value = "";
            advice.value = "";
            medicines.value = [{ name: "", dosage: "", duration: "" }];
            // Reload to show new prescription
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error("Failed to add prescription");
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification("Failed to add prescription", "error");
    } finally {
        isSubmitting.value = false;
    }
};

const showNotification = (message, type = "info") => {
    // In a real app, you'd use a proper notification system
    const alertClass = type === "success" ? "alert-success" : "alert-error";
    alert(message);
};

// Computed property for latest prescription
const latestPrescription = computed(() => {
    if (
        !appointment.value.prescriptions ||
        appointment.value.prescriptions.length === 0
    ) {
        return null;
    }
    return appointment.value.prescriptions[
        appointment.value.prescriptions.length - 1
    ];
});
</script>

<template>
    <Head title="Appointment Details - Doctor Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50/30 py-6 sm:py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6 sm:mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="p-2 sm:p-3 bg-white rounded-2xl shadow-sm border border-gray-100"
                            >
                                <svg
                                    class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1
                                    class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900"
                                >
                                    Appointment Details
                                </h1>
                                <p
                                    class="text-xs sm:text-sm md:text-base text-gray-600 mt-1"
                                >
                                    Patient consultation and prescription
                                    management
                                </p>
                            </div>
                        </div>
                        <Link
                            href="/doctor/appointments"
                            class="inline-flex items-center px-3 py-2 md:px-4 md:py-2.5 border border-black-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            <span class="hidden sm:inline"
                                >Back to Appointments</span
                            >
                            <span class="sm:hidden">Back</span>
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Left Column - Patient Information -->
                    <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                        <!-- Patient Card -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <div
                                class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6"
                            >
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center"
                                >
                                    <span
                                        class="text-white font-semibold text-sm sm:text-lg"
                                    >
                                        {{ appointment.first_name.charAt(0)
                                        }}{{ appointment.last_name.charAt(0) }}
                                    </span>
                                </div>
                                <div>
                                    <h2
                                        class="text-lg sm:text-xl font-bold text-gray-900"
                                    >
                                        {{ appointment.first_name }}
                                        {{ appointment.last_name }}
                                    </h2>
                                    <p class="text-gray-600 text-xs sm:text-sm">
                                        {{ appointment.email }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-500"
                                        >Status</span
                                    >
                                    <span
                                        :class="[
                                            'px-2 sm:px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset',
                                            getStatusColor(appointment.status),
                                        ]"
                                    >
                                        {{ appointment.status }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-500"
                                        >Age</span
                                    >
                                    <span
                                        class="text-xs sm:text-sm text-gray-900"
                                        >{{ appointment.age }} years</span
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-500"
                                        >Phone</span
                                    >
                                    <span
                                        class="text-xs sm:text-sm text-gray-900"
                                        >{{ appointment.phone }}</span
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs sm:text-sm font-medium text-gray-500"
                                        >Booking ID</span
                                    >
                                    <span
                                        class="text-xs sm:text-sm font-mono text-gray-900"
                                        >{{ appointment.booking_id }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details Card -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <h3
                                class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4"
                            >
                                Appointment Details
                            </h3>
                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg
                                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m0 5V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2v-6a2 2 0 00-2-2h-8z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-xs sm:text-sm font-medium text-gray-500"
                                        >
                                            Date
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-900"
                                        >
                                            {{ appointment.preferred_date }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-green-50 rounded-lg">
                                        <svg
                                            class="w-4 h-4 sm:w-5 sm:h-5 text-green-600"
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
                                        <p
                                            class="text-xs sm:text-sm font-medium text-gray-500"
                                        >
                                            Time
                                        </p>
                                        <p
                                            class="text-xs sm:text-sm text-gray-900"
                                        >
                                            {{ appointment.preferred_time }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div
                            class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-6"
                        >
                            <h3
                                class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4"
                            >
                                Quick Actions
                            </h3>
                            <div class="space-y-2 sm:space-y-3">
                                <a
                                    v-if="latestPrescription"
                                    :href="`/doctor/appointments/${appointment.id}/prescriptions/${latestPrescription.id}/download-pdf`"
                                    target="_blank"
                                    class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors group"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-red-50 rounded-lg">
                                            <svg
                                                class="w-4 h-4 sm:w-5 sm:h-5 text-red-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-xs sm:text-sm font-medium text-gray-700"
                                            >Download Latest Prescription</span
                                        >
                                    </div>
                                    <svg
                                        class="w-4 h-4 text-gray-400 group-hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </a>
                                <button
                                    class="w-full flex items-center justify-between p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors group"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="p-2 bg-purple-50 rounded-lg"
                                        >
                                            <svg
                                                class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-xs sm:text-sm font-medium text-gray-700"
                                            >Add Hospital Notes</span
                                        >
                                    </div>
                                    <svg
                                        class="w-4 h-4 text-gray-400 group-hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Prescriptions -->
                    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                        <!-- New Prescription Form -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden"
                        >
                            <div
                                class="px-4 sm:px-6 py-4 border-b border-gray-200"
                            >
                                <h3
                                    class="text-base sm:text-lg font-semibold text-gray-900"
                                >
                                    New Prescription
                                </h3>
                                <p
                                    class="text-xs sm:text-sm text-gray-600 mt-1"
                                >
                                    Fill out the prescription details below
                                </p>
                            </div>

                            <form
                                @submit.prevent="submitPrescription"
                                class="p-4 sm:p-6 space-y-4 sm:space-y-6"
                            >
                                <!-- Diagnosis -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Diagnosis
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="diagnosis"
                                        type="text"
                                        class="w-full px-4 py-3 border border-black-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        placeholder="Enter diagnosis (e.g., Upper respiratory infection, Hypertension)"
                                        required
                                    />
                                </div>

                                <!-- Medicines -->
                                <div>
                                    <label class="block font-semibold mb-2"
                                        >Medicines:</label
                                    >

                                    <div class="overflow-x-auto">
                                        <table
                                            class="w-full rounded-lg overflow-hidden min-w-[600px]"
                                        >
                                            <thead class="text-gray-700">
                                                <tr>
                                                    <th
                                                        class="min-w-[150px] text-left"
                                                    >
                                                        Medicine Name
                                                    </th>
                                                    <th
                                                        class="min-w-[150px] text-left"
                                                    >
                                                        Dosage
                                                    </th>
                                                    <th
                                                        class="min-w-[100px] text-left"
                                                    >
                                                        Duration
                                                    </th>
                                                    <th
                                                        class="min-w-[50px]"
                                                    ></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(
                                                        medicine, index
                                                    ) in medicines"
                                                    :key="index"
                                                    class="bg-white"
                                                >
                                                    <td class="p-2">
                                                        <input
                                                            v-model="
                                                                medicine.name
                                                            "
                                                            type="text"
                                                            class="w-full px-2 py-1 border rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                            placeholder="Medicine name"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="p-2">
                                                        <input
                                                            v-model="
                                                                medicine.dosage
                                                            "
                                                            type="text"
                                                            class="w-full px-2 py-1 border rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                            placeholder="e.g. 1 tab thrice daily"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="p-2">
                                                        <input
                                                            v-model="
                                                                medicine.duration
                                                            "
                                                            type="text"
                                                            class="w-full px-2 py-1 border rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                            placeholder="e.g. 5 days"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="p-2 text-center">
                                                        <button
                                                            v-if="
                                                                medicines.length >
                                                                1
                                                            "
                                                            type="button"
                                                            @click="
                                                                removeMedicineRow(
                                                                    index
                                                                )
                                                            "
                                                            class="text-red-500 hover:text-red-700 font-bold text-lg"
                                                        >
                                                            ×
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <button
                                        type="button"
                                        @click="addMedicineRow"
                                        class="mt-2 text-indigo-600 hover:underline font-medium"
                                    >
                                        + Add Medicine
                                    </button>
                                </div>

                                <!-- Advice -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Advice
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        v-model="advice"
                                        rows="4"
                                        class="w-full px-4 py-3 border border-black-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                        placeholder="Provide medical advice and instructions (e.g., Rest, Hydration, Follow-up in 3 days)"
                                        required
                                    ></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div
                                    class="flex items-center justify-end pt-4 border-t border-gray-200"
                                >
                                    <button
                                        type="submit"
                                        :disabled="isSubmitting"
                                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-indigo-400 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg
                                            v-if="isSubmitting"
                                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-5 h-5 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        {{
                                            isSubmitting
                                                ? "Creating Prescription..."
                                                : "Create Prescription"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Existing Prescriptions -->
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
                        >
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        Prescription History
                                    </h3>
                                    <span
                                        v-if="
                                            appointment.prescriptions &&
                                            appointment.prescriptions.length > 0
                                        "
                                        class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium"
                                    >
                                        {{ appointment.prescriptions.length }}
                                        prescription{{
                                            appointment.prescriptions.length !==
                                            1
                                                ? "s"
                                                : ""
                                        }}
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="
                                    appointment.prescriptions &&
                                    appointment.prescriptions.length > 0
                                "
                                class="divide-y divide-gray-200"
                            >
                                <div
                                    v-for="prescription in appointment.prescriptions"
                                    :key="prescription.id"
                                    class="p-6 hover:bg-gray-50/50 transition-colors"
                                >
                                    <div
                                        class="flex items-center justify-between mb-4"
                                    >
                                        <p class="text-sm text-gray-500">
                                            {{
                                                new Date(
                                                    prescription.created_at
                                                ).toLocaleString()
                                            }}
                                        </p>
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <a
                                                :href="`/doctor/appointments/${appointment.id}/prescriptions/${prescription.id}/download-pdf`"
                                                target="_blank"
                                                class="inline-flex items-center px-3 py-1.5 bg-white border border-black-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    />
                                                </svg>
                                                Download PDF
                                            </a>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 rounded-xl p-4 border border-gray-200"
                                    >
                                        <pre
                                            class="whitespace-pre-wrap font-sans text-sm text-gray-700"
                                            >{{
                                                prescription.prescription_text
                                            }}</pre
                                        >
                                    </div>
                                </div>
                            </div>
                            <div v-else class="p-12 text-center">
                                <div
                                    class="p-4 bg-gray-50 rounded-2xl inline-flex mb-4"
                                >
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-900 mb-2"
                                >
                                    No prescriptions yet
                                </h3>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    Create your first prescription for this
                                    patient using the form below.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
