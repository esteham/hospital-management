<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

const api = axios.create({ headers: { "X-Requested-With": "XMLHttpRequest" } });
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

const page = usePage();
const flash = computed(() => page.props.value?.flash ?? {});

const loading = ref(false);
const items = ref([]);

const form = reactive({
    name: "",
    email: "",
    password: "",
    designation: "",
    speciality: "",
    phone: "",
    about: "",
});
const errors = ref({});
const hasErrors = computed(
    () => !!errors.value && Object.keys(errors.value).length > 0
);

const showEdit = ref(false);
const editing = ref(null);
const editForm = reactive({
    name: "",
    email: "",
    password: "",
    designation: "",
    speciality: "",
    phone: "",
    about: "",
});

async function fetchList() {
    loading.value = true;
    try {
        const { data } = await api.get("/admin/doctors/list");
        items.value = data;
    } catch (e) {
        alert(e?.response?.data?.message || "Failed to load doctors");
    } finally {
        loading.value = false;
    }
}

function resetForm() {
    form.name = "";
    form.email = "";
    form.password = "";
    form.designation = "";
    form.speciality = "";
    form.phone = "";
    form.about = "";
    errors.value = {};
}

async function submit() {
    errors.value = {};
    try {
        const { data } = await api.post("/admin/doctors", { ...form });
        items.value.unshift(data.doctor);
        resetForm();
        alert("Doctor Created & Email Sent!");
    } catch (e) {
        if (e?.response?.status === 422) {
            errors.value = e.response.data.errors || {};
            const msg = Object.values(errors.value || {})
                .flat()
                .join("\n");
            if (msg) alert(msg);
        } else {
            alert(e?.response?.data?.message || "Failed to create doctor");
        }
    }
}

function openEdit(row) {
    editing.value = row;
    editForm.name = row.name;
    editForm.email = row.email;
    editForm.password = "";
    editForm.designation = row.designation || "";
    editForm.speciality = row.speciality || "";
    editForm.phone = row.phone || "";
    editForm.about = row.about || "";
    showEdit.value = true;
}

async function saveEdit() {
    if (!editing.value) return;
    try {
        const { data } = await api.post(`/admin/doctors/${editing.value.id}`, {
            ...editForm,
        });
        const idx = items.value.findIndex((x) => x.id === editing.value.id);
        if (idx > -1) items.value[idx] = data.doctor;
        showEdit.value = false;
        alert("Doctor Updated");
    } catch (e) {
        const errs = e?.response?.data?.errors;
        if (errs) {
            alert(Object.values(errs).flat().join("\n"));
        } else {
            alert(e?.response?.data?.message || "Failed to update");
        }
    }
}

async function removeRow(row) {
    if (!confirm(`Delete ${row.name}? This will also remove the user account.`))
        return;
    try {
        await api.delete(`/admin/doctors/${row.id}`);
        items.value = items.value.filter((x) => x.id !== row.id);
    } catch (e) {
        alert(e?.response?.data?.message || "Failed to delete");
    }
}

onMounted(fetchList);
</script>

<template>
    <AuthenticatedLayout title="Admin: Doctors">
        <div class="admin-container">
            <!-- Header -->
            <div class="page-header">
                <h1 class="page-title">Doctor Management</h1>
                <p class="page-subtitle">
                    Manage medical staff and their information
                </p>
            </div>

            <!-- Flash Messages -->
            <div class="flash-container">
                <div v-if="flash.success" class="flash-message success">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ flash.success }}
                </div>
                <div v-if="flash.error" class="flash-message error">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ flash.error }}
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Add Doctor Card -->
                <div class="card add-doctor-card">
                    <div class="card-header">
                        <h2 class="card-title">Add New Doctor</h2>
                        <div class="card-subtitle">
                            Create a new doctor account
                        </div>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submit" class="doctor-form">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label class="form-label">Full Name</label>
                                    <input
                                        type="text"
                                        v-model="form.name"
                                        class="form-input"
                                        placeholder="Enter full name"
                                    />
                                    <p v-if="errors.name" class="form-error">
                                        {{ errors.name[0] }}
                                    </p>
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label"
                                        >Email Address</label
                                    >
                                    <input
                                        type="email"
                                        v-model="form.email"
                                        class="form-input"
                                        placeholder="Enter email address"
                                    />
                                    <p v-if="errors.email" class="form-error">
                                        {{ errors.email[0] }}
                                    </p>
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label">Password</label>
                                    <input
                                        type="password"
                                        v-model="form.password"
                                        class="form-input"
                                        placeholder="Minimum 8 characters"
                                    />
                                    <p
                                        v-if="errors.password"
                                        class="form-error"
                                    >
                                        {{ errors.password[0] }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label"
                                        >Designation</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.designation"
                                        class="form-input"
                                        placeholder="e.g., Senior Consultant"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Speciality</label>
                                    <input
                                        type="text"
                                        v-model="form.speciality"
                                        class="form-input"
                                        placeholder="e.g., Cardiology"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input
                                        type="text"
                                        v-model="form.phone"
                                        class="form-input"
                                        placeholder="Phone number"
                                    />
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label">About</label>
                                    <textarea
                                        v-model="form.about"
                                        class="form-input textarea"
                                        rows="3"
                                        placeholder="Brief professional background"
                                    ></textarea>
                                </div>
                            </div>

                            <div v-if="hasErrors" class="form-errors">
                                <div
                                    v-for="(msgs, key) in errors"
                                    :key="key"
                                    class="error-item"
                                >
                                    {{ Array.isArray(msgs) ? msgs[0] : msgs }}
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary btn-full"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="btn-icon"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Add Doctor
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Doctors List Card -->
                <div class="card doctors-list-card">
                    <div class="card-header with-action">
                        <div>
                            <h2 class="card-title">Doctors</h2>
                            <div class="card-subtitle">
                                Manage existing medical staff
                            </div>
                        </div>
                        <button
                            class="btn btn-secondary"
                            @click="fetchList"
                            :disabled="loading"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="btn-icon"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{ loading ? "Loading..." : "Refresh" }}
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">Name</th>
                                        <th class="text-left">Email</th>
                                        <th class="text-left">Speciality</th>
                                        <th class="text-left">Phone</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="loading">
                                        <td colspan="5" class="table-loading">
                                            <div class="loading-spinner"></div>
                                            <span>Loading doctors...</span>
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="d in items"
                                        :key="d.id"
                                        class="table-row"
                                    >
                                        <td class="doctor-name">
                                            <div class="name">{{ d.name }}</div>
                                            <div
                                                class="designation"
                                                v-if="d.designation"
                                            >
                                                {{ d.designation }}
                                            </div>
                                        </td>
                                        <td class="email">{{ d.email }}</td>
                                        <td>
                                            <span
                                                class="speciality-tag"
                                                v-if="d.speciality"
                                                >{{ d.speciality }}</span
                                            >
                                            <span class="no-data" v-else
                                                >Not specified</span
                                            >
                                        </td>
                                        <td>
                                            <span v-if="d.phone">{{
                                                d.phone
                                            }}</span>
                                            <span class="no-data" v-else
                                                >Not provided</span
                                            >
                                        </td>
                                        <td class="actions">
                                            <button
                                                class="btn btn-sm btn-outline"
                                                @click="openEdit(d)"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="btn-icon"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                                    />
                                                </svg>
                                                Edit
                                            </button>
                                            <button
                                                class="btn btn-sm btn-danger"
                                                @click="removeRow(d)"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="btn-icon"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!loading && items.length == 0">
                                        <td colspan="5" class="table-empty">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="empty-icon"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <div>No doctors found</div>
                                            <p class="empty-text">
                                                Get started by adding a new
                                                doctor
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Doctor Modal -->
            <div v-if="showEdit" class="modal-overlay">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Doctor</h3>
                        <button class="modal-close" @click="showEdit = false">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="icon"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form @submit.prevent="saveEdit" class="doctor-form">
                            <div class="form-grid">
                                <div class="form-group full-width">
                                    <label class="form-label">Full Name</label>
                                    <input
                                        type="text"
                                        v-model="editForm.name"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label"
                                        >Email Address</label
                                    >
                                    <input
                                        type="email"
                                        v-model="editForm.email"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label">Password</label>
                                    <input
                                        type="password"
                                        v-model="editForm.password"
                                        class="form-input"
                                        placeholder="Leave blank to keep current password"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label"
                                        >Designation</label
                                    >
                                    <input
                                        type="text"
                                        v-model="editForm.designation"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Speciality</label>
                                    <input
                                        type="text"
                                        v-model="editForm.speciality"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input
                                        type="text"
                                        v-model="editForm.phone"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label">About</label>
                                    <textarea
                                        v-model="editForm.about"
                                        class="form-input textarea"
                                        rows="3"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="modal-actions">
                                <button type="submit" class="btn btn-primary">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="btn-icon"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    Save Changes
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline"
                                    @click="showEdit = false"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.admin-container {
    @apply p-6 max-w-7xl mx-auto;
}

.page-header {
    @apply mb-8;
}

.page-title {
    @apply text-2xl font-bold text-gray-900;
}

.page-subtitle {
    @apply text-gray-600 mt-1;
}

.flash-container {
    @apply mb-6;
}

.flash-message {
    @apply p-4 rounded-lg flex items-center gap-3 mb-3;
}

.flash-message.success {
    @apply bg-green-50 text-green-700 border border-green-200;
}

.flash-message.error {
    @apply bg-red-50 text-red-700 border border-red-200;
}

.icon {
    @apply w-5 h-5;
}

.content-grid {
    @apply grid grid-cols-1 xl:grid-cols-2 gap-6;
}

.card {
    @apply bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden;
}

.add-doctor-card {
    @apply h-fit;
}

.doctors-list-card {
    @apply xl:col-span-2;
}

.card-header {
    @apply p-6 border-b border-gray-100;
}

.card-header.with-action {
    @apply flex items-center justify-between;
}

.card-title {
    @apply text-lg font-semibold text-gray-900;
}

.card-subtitle {
    @apply text-sm text-gray-500 mt-1;
}

.card-body {
    @apply p-6;
}

.doctor-form {
    @apply space-y-4;
}

.form-grid {
    @apply grid grid-cols-1 md:grid-cols-2 gap-4;
}

.form-group {
    @apply space-y-2;
}

.form-group.full-width {
    @apply md:col-span-2;
}

.form-label {
    @apply block text-sm font-medium text-gray-700;
}

.form-input {
    @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
}

.form-input.textarea {
    @apply resize-none;
}

.form-error {
    @apply text-sm text-red-600;
}

.form-errors {
    @apply p-3 bg-red-50 border border-red-200 rounded-lg;
}

.error-item {
    @apply text-sm text-red-700;
}

.btn {
    @apply inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2;
}

.btn:disabled {
    @apply opacity-50 cursor-not-allowed;
}

.btn-icon {
    @apply w-4 h-4;
}

.btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
}

.btn-secondary {
    @apply bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500;
}

.btn-outline {
    @apply bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500;
}

.btn-danger {
    @apply bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
}

.btn-sm {
    @apply px-3 py-1.5 text-sm;
}

.btn-full {
    @apply w-full;
}

.table-container {
    @apply overflow-x-auto;
}

.data-table {
    @apply w-full;
}

.data-table thead {
    @apply bg-gray-50;
}

.data-table th {
    @apply px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.data-table tbody {
    @apply divide-y divide-gray-200;
}

.table-loading {
    @apply px-4 py-8 text-center;
}

.loading-spinner {
    @apply w-6 h-6 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2;
}

.table-row {
    @apply transition-colors hover:bg-gray-50;
}

.table-row td {
    @apply px-4 py-3;
}

.doctor-name .name {
    @apply font-medium text-gray-900;
}

.doctor-name .designation {
    @apply text-sm text-gray-500 mt-1;
}

.email {
    @apply text-gray-600;
}

.speciality-tag {
    @apply inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800;
}

.no-data {
    @apply text-gray-400 italic;
}

.actions {
    @apply flex gap-2 justify-end;
}

.table-empty {
    @apply px-4 py-12 text-center;
}

.empty-icon {
    @apply w-12 h-12 text-gray-300 mx-auto mb-3;
}

.empty-text {
    @apply text-gray-500 text-sm mt-1;
}

.modal-overlay {
    @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50;
}

.modal {
    @apply bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto;
}

.modal-header {
    @apply flex items-center justify-between p-6 border-b border-gray-200;
}

.modal-title {
    @apply text-lg font-semibold text-gray-900;
}

.modal-close {
    @apply p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors;
}

.modal-body {
    @apply p-6;
}

.modal-actions {
    @apply flex gap-3 pt-4;
}
</style>
