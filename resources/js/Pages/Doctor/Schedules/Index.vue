<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, reactive, onMounted, computed } from "vue";
import api from "@/lib/api";
import {
    Loader2,
    Edit3,
    Trash2,
    Plus,
    Calendar,
    Clock,
    Users,
    DollarSign,
} from "lucide-vue-next";

const days = [
    { v: "sat", t: "Saturday" },
    { v: "sun", t: "Sunday" },
    { v: "mon", t: "Monday" },
    { v: "tue", t: "Tuesday" },
    { v: "wed", t: "Wednesday" },
    { v: "thu", t: "Thursday" },
    { v: "fri", t: "Friday" },
];

const items = ref([]);
const loading = ref(false);
const saving = ref(false);
const editing = ref(null);
const showForm = ref(false);

const form = reactive({
    day_of_week: "sat",
    start_time: "09:00",
    end_time: "13:00",
    slot_minutes: 15,
    max_patients_per_day: 20,
    fee: 500,
});

// Computed properties for better organization
const dayOptions = computed(() => days);
const isEditing = computed(() => editing.value !== null);
const hasSchedules = computed(() => items.value.length > 0);

function toHHmm(t) {
    if (!t) return t;
    if (/^\d{2}:\d{2}:\d{2}$/.test(t)) return t.slice(0, 5);
    const m = t.match(/^(\d{1,2}):(\d{2})/);
    return m ? `${m[1].padStart(2, "0")}:${m[2]}` : t;
}

async function fetchList() {
    loading.value = true;
    try {
        const { data } = await api.get("/doctor/schedules/list");
        items.value = data;
    } catch (e) {
        alert(e.response?.data?.message || "Failed to load schedules.");
    } finally {
        loading.value = false;
    }
}

function resetForm() {
    editing.value = null;
    showForm.value = false;
    Object.assign(form, {
        day_of_week: "sat",
        start_time: "09:00",
        end_time: "13:00",
        slot_minutes: 15,
        max_patients_per_day: 20,
        fee: 500,
    });
}

function startCreating() {
    resetForm();
    showForm.value = true;
}

async function save() {
    saving.value = true;
    const payload = {
        ...form,
        start_time: toHHmm(form.start_time),
        end_time: toHHmm(form.end_time),
    };
    try {
        if (editing.value) {
            const { data } = await api.put(
                `/doctor/schedules/${editing.value.id}`,
                payload
            );
            const idx = items.value.findIndex((i) => i.id === editing.value.id);
            if (idx > -1) items.value[idx] = data;
        } else {
            const { data } = await api.post("/doctor/schedules", payload);
            items.value.push(data);
        }
        resetForm();
    } catch (e) {
        alert(e.response?.data?.message || "Validation error");
    } finally {
        saving.value = false;
    }
}

function edit(row) {
    editing.value = row;
    showForm.value = true;
    Object.assign(form, {
        day_of_week: row.day_of_week,
        start_time: toHHmm(row.start_time),
        end_time: toHHmm(row.end_time),
        slot_minutes: row.slot_minutes,
        max_patients_per_day: row.max_patients_per_day,
        fee: row.fee,
    });
}

async function removeRow(row) {
    if (!confirm("Are you sure you want to delete this schedule?")) return;
    try {
        await api.delete(`/doctor/schedules/${row.id}`);
        items.value = items.value.filter((i) => i.id !== row.id);
    } catch (e) {
        alert(e.response?.data?.message || "Failed to delete schedule.");
    }
}

function getDayName(dayValue) {
    const day = days.find((d) => d.v === dayValue);
    return day ? day.t : dayValue;
}

onMounted(fetchList);
</script>

<template>
    <AuthenticatedLayout title="Schedule Management">
        <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <!-- Action Bar -->
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600 bg-white px-3 py-2 rounded-lg border"
                            >
                                <Calendar class="w-4 h-4" />
                                <span
                                    >{{ items.length }} schedule{{
                                        items.length !== 1 ? "s" : ""
                                    }}
                                    configured</span
                                >
                            </div>
                        </div>
                        <button
                            @click="startCreating"
                            class="btn-primary flex items-center gap-2"
                            :disabled="showForm && !isEditing"
                        >
                            <Plus class="w-4 h-4" />
                            <span>New Schedule</span>
                        </button>
                    </div>

                    <!-- Form Card -->
                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 transform -translate-y-4"
                        enter-to-class="opacity-100 transform translate-y-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 transform translate-y-0"
                        leave-to-class="opacity-0 transform -translate-y-4"
                    >
                        <div
                            v-if="showForm"
                            class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6 space-y-6 backdrop-blur-sm"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2
                                        class="text-xl font-semibold text-gray-900"
                                    >
                                        {{
                                            isEditing
                                                ? "Edit Schedule"
                                                : "Create New Schedule"
                                        }}
                                    </h2>
                                    <p class="text-gray-500 text-sm mt-1">
                                        Configure consultation hours and
                                        availability
                                    </p>
                                </div>
                                <span
                                    v-if="isEditing"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                >
                                    <Edit3 class="w-3 h-3" />
                                    Editing
                                </span>
                            </div>

                            <div
                                class="grid md:grid-cols-2 lg:grid-cols-3 gap-6"
                            >
                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                                    >
                                        <Calendar class="w-4 h-4" />
                                        Day of Week
                                    </label>
                                    <select
                                        v-model="form.day_of_week"
                                        class="input-field"
                                    >
                                        <option
                                            v-for="d in dayOptions"
                                            :key="d.v"
                                            :value="d.v"
                                        >
                                            {{ d.t }}
                                        </option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                                    >
                                        <Clock class="w-4 h-4" />
                                        Start Time
                                    </label>
                                    <input
                                        v-model="form.start_time"
                                        type="time"
                                        class="input-field"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                                    >
                                        <Clock class="w-4 h-4" />
                                        End Time
                                    </label>
                                    <input
                                        v-model="form.end_time"
                                        type="time"
                                        class="input-field"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        Slot Duration (Minutes)
                                    </label>
                                    <input
                                        v-model.number="form.slot_minutes"
                                        type="number"
                                        min="5"
                                        max="60"
                                        step="5"
                                        class="input-field"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                                    >
                                        <Users class="w-4 h-4" />
                                        Max Patients/Day
                                    </label>
                                    <input
                                        v-model.number="
                                            form.max_patients_per_day
                                        "
                                        type="number"
                                        min="1"
                                        class="input-field"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                                    >
                                        <DollarSign class="w-4 h-4" />
                                        Consultation Fee
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                                            >$</span
                                        >
                                        <input
                                            v-model.number="form.fee"
                                            type="number"
                                            min="0"
                                            step="50"
                                            class="input-field pl-8"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex gap-3 pt-4 border-t border-gray-200"
                            >
                                <button
                                    @click="save"
                                    :disabled="saving"
                                    class="btn-primary flex items-center gap-2"
                                >
                                    <Loader2
                                        v-if="saving"
                                        class="w-4 h-4 animate-spin"
                                    />
                                    <span>
                                        {{
                                            isEditing
                                                ? "Update Schedule"
                                                : "Create Schedule"
                                        }}
                                    </span>
                                </button>
                                <button
                                    @click="resetForm"
                                    class="btn-secondary"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </transition>

                    <!-- Schedule List -->
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden"
                    >
                        <div
                            class="px-6 py-4 border-b border-gray-200/80 bg-gray-50/50"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">
                                Configured Schedules
                            </h3>
                        </div>

                        <div class="overflow-hidden">
                            <table class="w-full">
                                <thead
                                    class="bg-gray-50/80 border-b border-gray-200/80"
                                >
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Day
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Time Slot
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Duration
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Capacity
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Fee
                                        </th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200/80">
                                    <tr v-if="loading">
                                        <td
                                            colspan="6"
                                            class="px-6 py-12 text-center"
                                        >
                                            <div
                                                class="flex flex-col items-center justify-center gap-3"
                                            >
                                                <Loader2
                                                    class="w-8 h-8 animate-spin text-blue-600"
                                                />
                                                <span
                                                    class="text-gray-500 font-medium"
                                                    >Loading schedules...</span
                                                >
                                            </div>
                                        </td>
                                    </tr>

                                    <tr
                                        v-for="row in items"
                                        :key="row.id"
                                        class="group hover:bg-blue-50/30 transition-colors duration-200"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-2 h-2 bg-blue-600 rounded-full"
                                                ></div>
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >{{
                                                        getDayName(
                                                            row.day_of_week
                                                        )
                                                    }}</span
                                                >
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="flex items-center gap-2 text-gray-700"
                                            >
                                                <Clock
                                                    class="w-4 h-4 text-gray-400"
                                                />
                                                <span
                                                    >{{ row.start_time }} -
                                                    {{ row.end_time }}</span
                                                >
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-gray-600"
                                        >
                                            {{ row.slot_minutes }} minutes
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="flex items-center gap-2 text-gray-700"
                                            >
                                                <Users
                                                    class="w-4 h-4 text-gray-400"
                                                />
                                                <span
                                                    >{{
                                                        row.max_patients_per_day
                                                    }}
                                                    patients</span
                                                >
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="flex items-center gap-2 text-gray-700"
                                            >
                                                <DollarSign
                                                    class="w-4 h-4 text-gray-400"
                                                />
                                                <span>${{ row.fee }}</span>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right"
                                        >
                                            <div
                                                class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <button
                                                    @click="edit(row)"
                                                    class="action-btn bg-blue-50 text-blue-600 hover:bg-blue-100"
                                                    title="Edit schedule"
                                                >
                                                    <Edit3 class="w-4 h-4" />
                                                </button>
                                                <button
                                                    @click="removeRow(row)"
                                                    class="action-btn bg-red-50 text-red-600 hover:bg-red-100"
                                                    title="Delete schedule"
                                                >
                                                    <Trash2 class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-if="!loading && !hasSchedules">
                                        <td
                                            colspan="6"
                                            class="px-6 py-16 text-center"
                                        >
                                            <div
                                                class="flex flex-col items-center justify-center gap-4 text-gray-500"
                                            >
                                                <Calendar
                                                    class="w-12 h-12 text-gray-300"
                                                />
                                                <div>
                                                    <p
                                                        class="font-medium text-gray-900 mb-1"
                                                    >
                                                        No schedules configured
                                                    </p>
                                                    <p class="text-sm">
                                                        Get started by creating
                                                        your first schedule
                                                    </p>
                                                </div>
                                                <button
                                                    @click="startCreating"
                                                    class="btn-primary flex items-center gap-2 mt-2"
                                                >
                                                    <Plus class="w-4 h-4" />
                                                    <span>Create Schedule</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.input-field {
    @apply w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-all duration-200 text-gray-900 placeholder-gray-400;
}

.btn-primary {
    @apply bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 transition-all duration-200 shadow-sm hover:shadow-md;
}

.btn-secondary {
    @apply bg-white text-gray-700 px-6 py-3 rounded-xl font-semibold border border-gray-300 hover:bg-gray-50 active:scale-95 transition-all duration-200 shadow-sm;
}

.action-btn {
    @apply p-2.5 rounded-lg transition-all duration-200 hover:scale-105 active:scale-95;
}
</style>
