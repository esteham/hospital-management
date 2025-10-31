<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, onMounted, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

/** Axios + CSRF */
const api = axios.create({ headers: { "X-Requested-With": "XMLHttpRequest" } });
const token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

/** Flash */
const page = usePage();
const flash = computed(() => page.props.value?.flash ?? {});

/** Data */
const loading = ref(false);
const submitting = ref(false);
const items = ref([]);

/** Table controls */
const query = ref("");
const sortBy = ref("name");
const sortDir = ref("asc");
const pageSize = ref(10);
const pageIndex = ref(1);

/** Add/Edit state */
const showAdd = ref(false);
const showEdit = ref(false);
const editing = ref(null);

/** Forms */
const form = reactive({
    name: "",
    email: "",
    password: "",
    designation: "",
    speciality: "",
    phone: "",
    about: "",
});
const editForm = reactive({
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

/** UI helpers */
const showPassword = ref(false);
const showEditPassword = ref(false);
const showDelete = ref(false);
const toDelete = ref(null);

/** Derived */
const filtered = computed(() => {
    const q = query.value.trim().toLowerCase();
    let arr = items.value.slice();
    if (q) {
        arr = arr.filter((d) =>
            [d.name, d.email, d.speciality, d.designation, d.phone]
                .filter(Boolean)
                .some((v) => String(v).toLowerCase().includes(q))
        );
    }
    arr.sort((a, b) => {
        const dir = sortDir.value === "asc" ? 1 : -1;
        const va = (a[sortBy.value] ?? "").toString().toLowerCase();
        const vb = (b[sortBy.value] ?? "").toString().toLowerCase();
        return va < vb ? -1 * dir : va > vb ? 1 * dir : 0;
    });
    return arr;
});

const paged = computed(() => {
    const start = (pageIndex.value - 1) * pageSize.value;
    return filtered.value.slice(start, start + pageSize.value);
});

watch([query, sortBy, sortDir, pageSize], () => {
    pageIndex.value = 1;
});

/** API */
async function fetchList() {
    loading.value = true;
    try {
        const { data } = await api.get("/admin/doctors/list");
        items.value = Array.isArray(data) ? data : data?.data ?? [];
    } catch (e) {
        toast({
            type: "error",
            title: "Load failed",
            message: e?.response?.data?.message || "Could not load doctors.",
        });
    } finally {
        loading.value = false;
    }
}

function validate(payload, isEdit = false) {
    const errs = {};
    if (!payload.name?.trim()) errs.name = ["Name is required."];
    if (!payload.email?.trim()) errs.email = ["Email is required."];
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(payload.email))
        errs.email = ["Email is invalid."];
    if (!isEdit) {
        if (!payload.password || payload.password.length < 8)
            errs.password = ["Password must be at least 8 characters."];
    } else if (payload.password && payload.password.length < 8) {
        errs.password = ["Password must be at least 8 characters."];
    }
    return errs;
}

function resetForm() {
    Object.assign(form, {
        name: "",
        email: "",
        password: "",
        designation: "",
        speciality: "",
        phone: "",
        about: "",
    });
    errors.value = {};
}

async function submit() {
    errors.value = validate(form);
    if (Object.keys(errors.value).length) return scrollToFirstError();
    submitting.value = true;
    try {
        const { data } = await api.post("/admin/doctors", { ...form });
        items.value.unshift(data.doctor ?? data);
        resetForm();
        showAdd.value = false;
        toast({
            type: "success",
            title: "Doctor created",
            message: "Account created & email sent.",
        });
    } catch (e) {
        if (e?.response?.status === 422) {
            errors.value = e.response.data.errors || {};
            toast({
                type: "error",
                title: "Validation error",
                message: flattenErrors(errors.value),
            });
        } else {
            toast({
                type: "error",
                title: "Create failed",
                message:
                    e?.response?.data?.message || "Could not create doctor.",
            });
        }
    } finally {
        submitting.value = false;
    }
}

function openAdd() {
    resetForm();
    showPassword.value = false;
    showAdd.value = true;
}

function openEdit(row) {
    editing.value = row;
    Object.assign(editForm, {
        name: row.name ?? "",
        email: row.email ?? "",
        password: "",
        designation: row.designation ?? "",
        speciality: row.speciality ?? "",
        phone: row.phone ?? "",
        about: row.about ?? "",
    });
    showEdit.value = true;
}

async function saveEdit() {
    if (!editing.value) return;
    errors.value = validate(editForm, true);
    if (Object.keys(errors.value).length) return scrollToFirstError();
    try {
        const { data } = await api.post(`/admin/doctors/${editing.value.id}`, {
            ...editForm,
        });
        const idx = items.value.findIndex((x) => x.id === editing.value.id);
        if (idx > -1) items.value[idx] = data.doctor ?? data;
        showEdit.value = false;
        toast({ type: "success", title: "Saved", message: "Doctor updated." });
    } catch (e) {
        const errs = e?.response?.data?.errors;
        if (errs) {
            errors.value = errs;
            toast({
                type: "error",
                title: "Validation error",
                message: flattenErrors(errs),
            });
        } else {
            toast({
                type: "error",
                title: "Update failed",
                message: e?.response?.data?.message || "Could not update.",
            });
        }
    }
}

function confirmDelete(row) {
    toDelete.value = row;
    showDelete.value = true;
}

async function removeRow() {
    if (!toDelete.value) return (showDelete.value = false);
    try {
        await api.delete(`/admin/doctors/${toDelete.value.id}`);
        items.value = items.value.filter((x) => x.id !== toDelete.value.id);
        toast({
            type: "success",
            title: "Deleted",
            message: `${toDelete.value.name} removed.`,
        });
    } catch (e) {
        toast({
            type: "error",
            title: "Delete failed",
            message: e?.response?.data?.message || "Could not delete.",
        });
    } finally {
        showDelete.value = false;
        toDelete.value = null;
    }
}

/** Small helpers */
function flattenErrors(obj) {
    return Object.values(obj || {})
        .flat()
        .join("\n");
}
function scrollToFirstError() {
    requestAnimationFrame(() => {
        const el = document.querySelector(".form-error");
        if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
    });
}

/** Toasts */
const toasts = ref([]);
function toast({ type = "info", title = "", message = "", timeout = 3500 }) {
    const id = crypto.randomUUID();
    toasts.value.push({ id, type, title, message });
    setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    }, timeout);
}

onMounted(fetchList);
</script>

<template>
    <AuthenticatedLayout title="Admin · Doctors">
        <div class="p-6 max-w-7xl mx-auto">
            <!-- Header -->
            <header class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h1
                        class="text-2xl font-semibold tracking-tight text-gray-900"
                    >
                        Doctors
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Only existing doctors are listed below.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-4 py-2 font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @click="openAdd"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
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
                    <button
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @click="fetchList"
                        :disabled="loading"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ loading ? "Refreshing…" : "Refresh" }}
                    </button>
                </div>
            </header>

            <!-- Flash -->
            <div class="space-y-2 mb-4">
                <TransitionGroup name="fade">
                    <div
                        v-if="flash.success"
                        key="flash-success"
                        class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm"
                    >
                        <strong class="font-medium">Success:</strong>
                        {{ flash.success }}
                    </div>
                    <div
                        v-if="flash.error"
                        key="flash-error"
                        class="rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm"
                    >
                        <strong class="font-medium">Error:</strong>
                        {{ flash.error }}
                    </div>
                </TransitionGroup>
            </div>

            <!-- Controls -->
            <div
                class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="relative sm:w-80">
                    <input
                        v-model="query"
                        type="search"
                        placeholder="Search name, email, speciality…"
                        class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 pl-9 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <svg
                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"
                        />
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <label class="sr-only" for="sortBy">Sort by</label>
                    <select
                        id="sortBy"
                        v-model="sortBy"
                        class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm"
                    >
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="speciality">Speciality</option>
                    </select>
                    <button
                        @click="sortDir = sortDir === 'asc' ? 'desc' : 'asc'"
                        class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm"
                        :title="`Sort ${
                            sortDir === 'asc' ? 'descending' : 'ascending'
                        }`"
                        aria-label="Toggle sort direction"
                    >
                        <span v-if="sortDir === 'asc'">⬆️</span>
                        <span v-else>⬇️</span>
                    </button>
                    <select
                        v-model.number="pageSize"
                        class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm"
                    >
                        <option :value="10">10</option>
                        <option :value="20">20</option>
                        <option :value="50">50</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div
                class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-x-auto"
            >
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">
                                Name
                            </th>
                            <th class="px-4 py-3 text-left font-medium">
                                Email
                            </th>
                            <th class="px-4 py-3 text-left font-medium">
                                Speciality
                            </th>
                            <th class="px-4 py-3 text-left font-medium">
                                Phone
                            </th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-if="loading">
                            <td colspan="5" class="px-4 py-6">
                                <div class="animate-pulse space-y-3">
                                    <div
                                        class="h-4 bg-gray-200 rounded w-2/3"
                                    ></div>
                                    <div
                                        class="h-4 bg-gray-200 rounded w-1/2"
                                    ></div>
                                    <div
                                        class="h-4 bg-gray-200 rounded w-3/4"
                                    ></div>
                                </div>
                            </td>
                        </tr>
                        <tr
                            v-for="d in paged"
                            :key="d.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ d.name }}
                                </div>
                                <div
                                    v-if="d.designation"
                                    class="text-xs text-gray-500 mt-1"
                                >
                                    {{ d.designation }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ d.email }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    v-if="d.speciality"
                                    class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-2.5 py-0.5 text-xs font-medium"
                                    >{{ d.speciality }}</span
                                >
                                <span v-else class="text-gray-400 italic"
                                    >Not specified</span
                                >
                            </td>
                            <td class="px-4 py-3">
                                <span v-if="d.phone" class="text-gray-700">{{
                                    d.phone
                                }}</span>
                                <span v-else class="text-gray-400 italic"
                                    >Not provided</span
                                >
                            </td>
                            <td class="px-4 py-3">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <button
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 hover:bg-gray-50"
                                        @click="openEdit(d)"
                                    >
                                        ✏️ <span class="sr-only">Edit</span>
                                    </button>
                                    <button
                                        class="inline-flex items-center gap-1 rounded-lg border border-red-300 text-red-700 px-3 py-1.5 hover:bg-red-50"
                                        @click="confirmDelete(d)"
                                    >
                                        🗑️ <span class="sr-only">Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!loading && filtered.length === 0">
                            <td
                                colspan="5"
                                class="px-4 py-12 text-center text-gray-500"
                            >
                                <div class="mx-auto w-12 h-12 text-gray-300">
                                    🩺
                                </div>
                                <div class="mt-2 font-medium">
                                    No doctors found
                                </div>
                                <p class="text-sm">
                                    Use the Add Doctor button to create a new
                                    profile.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination info -->
            <div
                v-if="filtered.length"
                class="mt-4 flex items-center justify-between text-sm"
            >
                <div class="text-gray-600">
                    Showing
                    <span class="font-medium">{{
                        (pageIndex - 1) * pageSize + 1
                    }}</span>
                    –
                    <span class="font-medium">{{
                        Math.min(pageIndex * pageSize, filtered.length)
                    }}</span>
                    of <span class="font-medium">{{ filtered.length }}</span>
                </div>
                <div
                    class="inline-flex rounded-lg border border-gray-300 overflow-hidden"
                >
                    <button
                        class="px-3 py-1.5 disabled:opacity-50"
                        :disabled="pageIndex === 1"
                        @click="pageIndex--"
                    >
                        Prev
                    </button>
                    <button
                        class="px-3 py-1.5 disabled:opacity-50 border-l border-gray-300"
                        :disabled="pageIndex * pageSize >= filtered.length"
                        @click="pageIndex++"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- ADD MODAL -->
            <Teleport to="body">
                <Transition name="fade">
                    <div
                        v-if="showAdd"
                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                    >
                        <div
                            class="absolute inset-0 bg-black/50"
                            @click="showAdd = false"
                        />
                        <div
                            role="dialog"
                            aria-modal="true"
                            class="relative w-full max-w-2xl rounded-2xl bg-white border border-gray-200 shadow-xl"
                        >
                            <div
                                class="flex items-center justify-between p-6 border-b border-gray-100"
                            >
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Add Doctor
                                </h3>
                                <button
                                    class="rounded-lg p-1 text-gray-500 hover:bg-gray-100"
                                    @click="showAdd = false"
                                    aria-label="Close"
                                >
                                    ✖️
                                </button>
                            </div>
                            <div class="p-6">
                                <form
                                    @submit.prevent="submit"
                                    class="space-y-4"
                                    novalidate
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Full Name</label
                                        >
                                        <input
                                            type="text"
                                            v-model.trim="form.name"
                                            class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Dr. Jane Doe"
                                        />
                                        <p
                                            v-if="errors.name"
                                            class="form-error mt-1 text-sm text-red-600"
                                        >
                                            {{ errors.name[0] }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Email Address</label
                                        >
                                        <input
                                            type="email"
                                            v-model.trim="form.email"
                                            class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="name@example.com"
                                        />
                                        <p
                                            v-if="errors.email"
                                            class="form-error mt-1 text-sm text-red-600"
                                        >
                                            {{ errors.email[0] }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Password</label
                                        >
                                        <div class="mt-1 relative">
                                            <input
                                                :type="
                                                    showPassword
                                                        ? 'text'
                                                        : 'password'
                                                "
                                                v-model="form.password"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Minimum 8 characters"
                                            />
                                            <button
                                                type="button"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                                                @click="
                                                    showPassword = !showPassword
                                                "
                                                :aria-pressed="showPassword"
                                                :title="
                                                    showPassword
                                                        ? 'Hide password'
                                                        : 'Show password'
                                                "
                                            >
                                                <span v-if="showPassword"
                                                    >🙈</span
                                                >
                                                <span v-else>👁️</span>
                                            </button>
                                        </div>
                                        <p
                                            v-if="errors.password"
                                            class="form-error mt-1 text-sm text-red-600"
                                        >
                                            {{ errors.password[0] }}
                                        </p>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Designation</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="form.designation"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Senior Consultant"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Speciality</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="form.speciality"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Cardiology"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Phone</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="form.phone"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="+8801XXXXXXXXX"
                                            />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >About</label
                                            >
                                            <textarea
                                                v-model.trim="form.about"
                                                rows="3"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Brief professional background"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div
                                        v-if="hasErrors"
                                        class="rounded-xl border border-red-200 bg-red-50 text-red-700 px-3 py-2 text-sm"
                                    >
                                        <span class="font-medium"
                                            >Please fix the highlighted
                                            fields.</span
                                        >
                                    </div>
                                    <div
                                        class="pt-2 flex items-center justify-end gap-3"
                                    >
                                        <button
                                            type="button"
                                            class="rounded-xl border border-gray-300 px-4 py-2 hover:bg-gray-50"
                                            @click="showAdd = false"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            :disabled="submitting"
                                            class="rounded-xl bg-blue-600 text-white px-4 py-2 font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            {{
                                                submitting
                                                    ? "Adding…"
                                                    : "Add Doctor"
                                            }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- EDIT MODAL -->
            <Teleport to="body">
                <Transition name="fade">
                    <div
                        v-if="showEdit"
                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                    >
                        <div
                            class="absolute inset-0 bg-black/50"
                            @click="showEdit = false"
                        />
                        <div
                            role="dialog"
                            aria-modal="true"
                            class="relative w-full max-w-2xl rounded-2xl bg-white border border-gray-200 shadow-xl"
                        >
                            <div
                                class="flex items-center justify-between p-6 border-b border-gray-100"
                            >
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Edit Doctor
                                </h3>
                                <button
                                    class="rounded-lg p-1 text-gray-500 hover:bg-gray-100"
                                    @click="showEdit = false"
                                    aria-label="Close"
                                >
                                    ✖️
                                </button>
                            </div>
                            <div class="p-6">
                                <form
                                    @submit.prevent="saveEdit"
                                    class="space-y-4"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Full Name</label
                                        >
                                        <input
                                            type="text"
                                            v-model.trim="editForm.name"
                                            class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Email Address</label
                                        >
                                        <input
                                            type="email"
                                            v-model.trim="editForm.email"
                                            class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Password</label
                                        >
                                        <div class="mt-1 relative">
                                            <input
                                                :type="
                                                    showEditPassword
                                                        ? 'text'
                                                        : 'password'
                                                "
                                                v-model="editForm.password"
                                                class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Leave blank to keep current password"
                                            />
                                            <button
                                                type="button"
                                                class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                                                @click="
                                                    showEditPassword =
                                                        !showEditPassword
                                                "
                                            >
                                                <span v-if="showEditPassword"
                                                    >🙈</span
                                                ><span v-else>👁️</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Designation</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="
                                                    editForm.designation
                                                "
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Speciality</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="
                                                    editForm.speciality
                                                "
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >Phone</label
                                            >
                                            <input
                                                type="text"
                                                v-model.trim="editForm.phone"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700"
                                                >About</label
                                            >
                                            <textarea
                                                v-model.trim="editForm.about"
                                                rows="3"
                                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div
                                        class="pt-2 flex items-center justify-end gap-3"
                                    >
                                        <button
                                            type="button"
                                            class="rounded-xl border border-gray-300 px-4 py-2 hover:bg-gray-50"
                                            @click="showEdit = false"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-blue-600 text-white px-4 py-2 font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        >
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- DELETE CONFIRM -->
            <Teleport to="body">
                <Transition name="fade">
                    <div
                        v-if="showDelete"
                        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                    >
                        <div
                            class="absolute inset-0 bg-black/50"
                            @click="showDelete = false"
                        />
                        <div
                            class="relative w-full max-w-md rounded-2xl bg-white border border-gray-200 shadow-xl p-6"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">
                                Delete doctor?
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                This will also remove the user account for
                                <span class="font-medium">{{
                                    toDelete?.name
                                }}</span
                                >. This action cannot be undone.
                            </p>
                            <div
                                class="mt-6 flex items-center justify-end gap-3"
                            >
                                <button
                                    class="rounded-xl border border-gray-300 px-4 py-2 hover:bg-gray-50"
                                    @click="showDelete = false"
                                >
                                    Cancel
                                </button>
                                <button
                                    class="rounded-xl bg-red-600 text-white px-4 py-2 font-medium hover:bg-red-700"
                                    @click="removeRow"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- TOASTS -->
            <div
                class="fixed bottom-4 right-4 z-[60] space-y-2 w-full max-w-sm"
            >
                <TransitionGroup name="slide-up">
                    <div
                        v-for="t in toasts"
                        :key="t.id"
                        class="rounded-xl border px-4 py-3 shadow-md"
                        :class="{
                            'bg-white border-gray-200 text-gray-900':
                                t.type === 'info',
                            'bg-green-50 border-green-200 text-green-800':
                                t.type === 'success',
                            'bg-red-50 border-red-200 text-red-800':
                                t.type === 'error',
                            'bg-yellow-50 border-yellow-200 text-yellow-800':
                                t.type === 'warn',
                        }"
                    >
                        <div class="font-medium">{{ t.title }}</div>
                        <div class="text-sm">{{ t.message }}</div>
                    </div>
                </TransitionGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/***** Transitions *****/
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
    opacity: 0;
    transform: translateY(6px);
}
</style>
