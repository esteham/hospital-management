<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
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
`;

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
                    prescription_text: prescriptionText.trim(),
                }),
            }
        );

        if (response.ok) {
            alert("Prescription added successfully!");
            diagnosis.value = "";
            advice.value = "";
            medicines.value = [{ name: "", dosage: "", duration: "" }];
            window.location.reload();
        } else {
            alert("Failed to add prescription.");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred.");
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <Head title="Appointment Details - Doctor Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold">
                                Appointment Details
                            </h2>
                            <Link
                                href="/doctor/appointments"
                                class="text-blue-600"
                            >
                                Back to Appointments
                            </Link>
                        </div>

                        <!-- Appointment Info -->
                        <div class="mt-4 space-y-1 text-sm">
                            <p>
                                <strong>Booking ID:</strong>
                                {{ appointment.booking_id }}
                            </p>
                            <p>
                                <strong>Name:</strong>
                                {{ appointment.first_name }}
                                {{ appointment.last_name }}
                            </p>
                            <p>
                                <strong>Email:</strong> {{ appointment.email }}
                            </p>
                            <p>
                                <strong>Phone:</strong> {{ appointment.phone }}
                            </p>
                            <p>
                                <strong>Date:</strong>
                                {{ appointment.preferred_date }}
                            </p>
                            <p>
                                <strong>Time:</strong>
                                {{ appointment.preferred_time }}
                            </p>
                            <p>
                                <strong>Speciality:</strong>
                                {{ appointment.speciality }}
                            </p>
                            <p>
                                <strong>Status:</strong>
                                {{ appointment.status }}
                            </p>
                        </div>

                        <!-- Existing Prescriptions -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold">
                                    Prescriptions
                                </h3>
                                <div
                                    v-if="
                                        appointment.prescriptions &&
                                        appointment.prescriptions.length > 0
                                    "
                                >
                                    <a
                                        :href="`/doctor/appointments/${
                                            appointment.id
                                        }/prescriptions/${
                                            appointment.prescriptions[
                                                appointment.prescriptions
                                                    .length - 1
                                            ].id
                                        }/download-pdf`"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        target="_blank"
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
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        Download Latest Prescription
                                    </a>
                                </div>
                            </div>
                            <div
                                v-if="
                                    appointment.prescriptions &&
                                    appointment.prescriptions.length > 0
                                "
                                class="mt-4"
                            >
                                <div
                                    v-for="prescription in appointment.prescriptions"
                                    :key="prescription.id"
                                    class="border p-4 mb-4 rounded bg-gray-50"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <p class="text-sm text-gray-500">
                                            Created at:
                                            {{
                                                new Date(
                                                    prescription.created_at
                                                ).toLocaleString()
                                            }}
                                        </p>
                                        <a
                                            :href="`/doctor/appointments/${appointment.id}/prescriptions/${prescription.id}/download-pdf`"
                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                            target="_blank"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                ></path>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                    <pre class="whitespace-pre-wrap">{{
                                        prescription.prescription_text
                                    }}</pre>
                                </div>
                            </div>
                            <div v-else class="mt-4">
                                <p>No prescriptions yet.</p>
                            </div>

                            <!-- Add New Prescription Form -->
                            <div class="mt-8">
                                <h4 class="text-lg font-medium">
                                    Add New Prescription
                                </h4>

                                <form
                                    @submit.prevent="submitPrescription"
                                    class="mt-4 space-y-4"
                                >
                                    <!-- Diagnosis -->
                                    <div>
                                        <label class="block font-semibold mb-1"
                                            >Diagnosis:</label
                                        >
                                        <input
                                            v-model="diagnosis"
                                            type="text"
                                            class="w-full border rounded p-2"
                                            placeholder="e.g. Fever, Cough"
                                            required
                                        />
                                    </div>

                                    <!-- Medicine Table -->
                                    <div>
                                        <label class="block font-semibold mb-1"
                                            >Medicines:</label
                                        >
                                        <table
                                            class="w-full border border-gray-300"
                                        >
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="p-2 border">
                                                        Medicine Name
                                                    </th>
                                                    <th class="p-2 border">
                                                        Dosage
                                                    </th>
                                                    <th class="p-2 border">
                                                        Duration
                                                    </th>
                                                    <th class="p-2 border"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(
                                                        m, index
                                                    ) in medicines"
                                                    :key="index"
                                                >
                                                    <td class="p-2 border">
                                                        <input
                                                            v-model="m.name"
                                                            type="text"
                                                            class="w-full border p-1 rounded"
                                                            placeholder="Medicine name"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="p-2 border">
                                                        <input
                                                            v-model="m.dosage"
                                                            type="text"
                                                            class="w-full border p-1 rounded"
                                                            placeholder="e.g. 1 tab thrice daily"
                                                            required
                                                        />
                                                    </td>
                                                    <td class="p-2 border">
                                                        <input
                                                            v-model="m.duration"
                                                            type="text"
                                                            class="w-full border p-1 rounded"
                                                            placeholder="e.g. 5 days"
                                                            required
                                                        />
                                                    </td>
                                                    <td
                                                        class="p-2 border text-center"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                removeMedicineRow(
                                                                    index
                                                                )
                                                            "
                                                            class="text-red-500 hover:text-red-700"
                                                            v-if="
                                                                medicines.length >
                                                                1
                                                            "
                                                        >
                                                            ✖
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <button
                                            type="button"
                                            @click="addMedicineRow"
                                            class="mt-2 text-blue-600 hover:underline"
                                        >
                                            + Add Medicine
                                        </button>
                                    </div>

                                    <!-- Advice -->
                                    <div>
                                        <label class="block font-semibold mb-1"
                                            >Advice:</label
                                        >
                                        <textarea
                                            v-model="advice"
                                            rows="3"
                                            class="w-full border rounded p-2"
                                            placeholder="e.g. Drink boiled water, Take rest"
                                            required
                                        ></textarea>
                                    </div>

                                    <!-- Submit -->
                                    <button
                                        type="submit"
                                        :disabled="isSubmitting"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    >
                                        {{
                                            isSubmitting
                                                ? "Submitting..."
                                                : "Add Prescription"
                                        }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
