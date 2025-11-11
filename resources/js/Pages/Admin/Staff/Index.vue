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
    department: "",
    phone: "",
    about: "",
    photo: null,
});
const editForm = reactive({
    name: "",
    email: "",
    password: "",
    designation: "",
    department: "",
    phone: "",
    about: "",
    photo: null,
});
const errors = ref({});
const hasErrors = computed(
    () => !!errors.value && Object.keys(errors.value).length > 0
);

//Form data helper
function toFormData(obj) {
    const fd = new FormData();
    for (const [key, value] of Object.entries(obj ?? {})) {
        if (value === undefined || value === null || value === "") continue;
        if (value instanceof File || value instanceof Blob) {
            fd.append(key, value);
        } else if (Array.isArray(value)) {
            value.forEach((v, i) => fd.append(`${key}[${i}]`, v));
        } else if (typeof value === "object") {
            // shallow-flatten objects: key[nested]=...
            for (const [k2, v2] of Object.entries(value)) {
                fd.append(`${key}[${k2}]`, v2 ?? "");
            }
        } else {
            fd.append(key, value);
        }
    }
    return fd;
}

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
        arr = arr.filter((s) =>
            [s.name, s.email, s.department, s.designation, s.phone]
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
        const { data } = await api.get("/admin/staff/list");
        items.value = Array.isArray(data) ? data : data?.data ?? [];
    } catch (e) {
        toast({
            type: "error",
            title: "Load failed",
            message: e?.response?.data?.message || "Could not load staff.",
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
        department: "",
        phone: "",
        about: "",
        photo: null,
    });
    errors.value = {};
}

async function submit() {
    if (form.photo && !form.photo.type?.startsWith("image/")) {
        errors.value = {
            ...errors.value,
            photo: ["Please upload a valid image file."],
        };
        return scrollToFirstError();
    }

    errors.value = validate(form);
    if (Object.keys(errors.value).length) return scrollToFirstError();
    submitting.value = true;
    try {
        const payload = {
            name: form.name,
            email: form.email,
            password: form.password,
            designation: form.designation,
            department: form.department,
            phone: form.phone,
            about: form.about,
            photo: form.photo || null,
        };

        const fd = toFormData(payload);

        const { data } = await api.post("/admin/staff", fd);

        items.value.unshift(data.staff ?? data);
        resetForm();
        showAdd.value = false;
        toast({
            type: "success",
            title: "Staff created",
            message: "Account created successfully.",
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
                    e?.response?.data?.message || "Could not create staff.",
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
        department: row.department ?? "",
        phone: row.phone ?? "",
        about: row.about ?? "",
        photo: null,
    });
    showEdit.value = true;
}

async function saveEdit() {
    if (!editing.value) return;

    if (editForm.photo && !editForm.photo.type?.startsWith("image/")) {
        errors.value = {
            ...errors.value,
            photo: ["Please upload a valid image file."],
        };
        return scrollToFirstError();
    }

    errors.value = validate(editForm, true);
    if (Object.keys(errors.value).length) return scrollToFirstError();

    try {
        const payload = {
            name: editForm.name,
            email: editForm.email,
            password: editForm.password || "",
            designation: editForm.designation,
            department: editForm.department,
            phone: editForm.phone,
            about: editForm.about,
        };
        const fd = toFormData(payload);

        if (editForm.photo) fd.append("photo", editForm.photo);

        fd.append("_method", "PUT");

        const { data } = await api.post(`/admin/staff/${editing.value.id}`, fd);

        const idx = items.value.findIndex((x) => x.id === editing.value.id);
        if (idx > -1) items.value[idx] = data.staff ?? data;

        showEdit.value = false;
        toast({ type: "success", title: "Saved", message: "Staff updated." });
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
        await api.delete(`/admin/staff/${toDelete.value.id}`);
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
    <AuthenticatedLayout title="Admin · Staff">
        <div class="p-4 sm:p-6 max-w-7xl mx-auto">
            <!-- Header -->
            <header
                class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold tracking-tight text-gray-900"
                    >
                        Staff
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Only existing staff are listed below.
                    </p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 text-white px-3 py-2 sm:px-4 font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
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
                        Add Staff
                    </button>
                    <button
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 px-3 py-2 sm:px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                <div class="relative w-full sm:w-80">
                    <input
                        v-model="query"
                        type="search"
                        placeholder="Search name, email, department…"
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
                <div class="flex flex-wrap items-center gap-2">
                    <label class="sr-only" for="sortBy">Sort by</label>
                    <select
                        id="sortBy"
                        v-model="sortBy"
                        class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm"
                    >
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="department">Department</option>
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
                            <th class="px-2 py-3 text-left font-medium sm:px-4">
                                Name
                            </th>
                            <th
                                class="px-2 py-3 text-left font-medium sm:px-4 hidden sm:table-cell"
                            >
                                Email
                            </th>
                            <th
                                class="px-2 py-3 text-left font-medium sm:px-4 hidden md:table-cell"
                            >
                                Department
                            </th>
                            <th
                                class="px-2 py-3 text-left font-medium sm:px-4 hidden md:table-cell"
                            >
                                Designation
                            </th>
                            <th
                                class="px-2 py-3 text-left font-medium sm:px-4 hidden lg:table-cell"
                            >
                                Phone
                            </th>
                            <th
                                class="px-2 py-3 text-right font-medium sm:px-4"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-if="loading">
                            <td colspan="6" class="px-2 py-6 sm:px-4">
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
                            v-for="item in paged"
                            :key="item.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-2 py-3 sm:px-4">
                                <div class="flex items-center gap-3">
                                    <img
                                        v-if="item.photo"
                                        :src="item.photo"
                                        alt="Staff photo"
                                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover"
                                    />
                                    <div>
                                        <div
                                            class="font-medium text-gray-900 text-sm sm:text-base"
                                        >
                                            {{ item.name }}
                                        </div>
                                        <div
                                            v-if="item.designation"
                                            class="text-xs text-gray-500 mt-1"
                                        >
                                            {{ item.designation }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td
                                class="px-2 py-3 text-gray-700 sm:px-4 hidden sm:table-cell"
                            >
                                {{ item.email }}
                            </td>
                            <td class="px-2 py-3 sm:px-4 hidden md:table-cell">
                                <span
                                    v-if="item.department"
                                    class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-medium"
                                    >{{ item.department }}</span
                                >
                                <span
                                    v-else
                                    class="text-gray-400 italic text-xs"
                                    >Not specified</span
                                >
                            </td>
                            <td class="px-2 py-3 sm:px-4 hidden md:table-cell">
                                <span
                                    v-if="item.designation"
                                    class="text-gray-700 text-sm"
                                    >{{ item.designation }}</span
                                >
                                <span
                                    v-else
                                    class="text-gray-400 italic text-xs"
                                    >Not provided</span
                                >
                            </td>
                            <td class="px-2 py-3 sm:px-4 hidden lg:table-cell">
                                <span
                                    v-if="item.phone"
                                    class="text-gray-700 text-sm"
                                    >{{ item.phone }}</span
                                >
                                <span
                                    v-else
                                    class="text-gray-400 italic text-xs"
                                    >Not provided</span
                                >
                            </td>
                            <td class="px-2 py-3 sm:px-4">
                                <div
                                    class="flex items-center justify-end gap-1 sm:gap-2"
                                >
                                    <button
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-2 py-1 sm:px-3 sm:py-1.5 hover:bg-gray-50 text-sm"
                                        @click="openEdit(item)"
                                    >
                                        ✏️ <span class="sr-only">Edit</span>
                                    </button>
                                    <button
                                        class="inline-flex items-center gap-1 rounded-lg border border-red-300 text-red-700 px-2 py-1 sm:px-3 sm:py-1.5 hover:bg-red-50 text-sm"
                                        @click="confirmDelete(item)"
                                    >
                                        🗑️ <span class="sr-only">Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!loading && filtered.length === 0">
                            <td
                                colspan="6"
                                class="px-2 py-12 text-center text-gray-500 sm:px-4"
                            >
                                <div class="mx-auto w-12 h-12 text-gray-300">
                                    👥
                                </div>
                                <div
                                    class="mt-2 font-medium text-sm sm:text-base"
                                >
                                    No staff found
                                </div>
                                <p class="text-xs sm:text-sm">
                                    Use the Add Staff button to create a new
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
                class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between text-sm"
            >
                <div class="text-gray-600 text-xs sm:text-sm">
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
                    class="inline-flex rounded-lg border border-gray-300 overflow-hidden self-center sm:self-auto"
                >
                    <button
                        class="px-3 py-1.5 disabled:opacity-50 text-xs sm:text-sm"
                        :disabled="pageIndex === 1"
                        @click="pageIndex--"
                    >
                        Prev
                    </button>
                    <button
                        class="px-3 py-1.5 disabled:opacity-50 border-l border-gray-300 text-xs sm:text-sm"
                        :disabled="pageIndex * pageSize >= filtered.length"
                        @click="pageIndex++"
                    >
                        Next
                    </button>
                </div>
            </div>

            <!-- Add Modal -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition-opacity duration-300"
                    leave-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="showAdd"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        @click.self="showAdd = false"
                    >
                        <div
                            class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl"
                        >
                            <h2
                                class="mb-4 text-xl font-semibold text-gray-900"
                            >
                                Add Staff
                            </h2>
                            <form @submit.prevent="submit" class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                        :class="{
                                            'border-red-500': errors.name,
                                        }"
                                    />
                                    <div
                                        v-if="errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                        :class="{
                                            'border-red-500': errors.email,
                                        }"
                                    />
                                    <div
                                        v-if="errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Password</label
                                    >
                                    <div class="relative">
                                        <input
                                            v-model="form.password"
                                            :type="
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 focus:border-blue-500 focus:ring-blue-500"
                                            :class="{
                                                'border-red-500':
                                                    errors.password,
                                            }"
                                        />
                                        <button
                                            type="button"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                                        >
                                            <svg
                                                v-if="showPassword"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                                />
                                            </svg>
                                            <svg
                                                v-else
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        v-if="errors.password"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.password[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Designation</label
                                    >
                                    <input
                                        v-model="form.designation"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Department</label
                                    >
                                    <input
                                        v-model="form.department"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Phone</label
                                    >
                                    <input
                                        v-model="form.phone"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >About</label
                                    >
                                    <textarea
                                        v-model="form.about"
                                        rows="3"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Photo</label
                                    >
                                    <input
                                        @change="
                                            form.photo = $event.target.files[0]
                                        "
                                        type="file"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <div
                                        v-if="errors.photo"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.photo[0] }}
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 pt-4">
                                    <button
                                        type="button"
                                        @click="showAdd = false"
                                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="submitting"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                                    >
                                        {{
                                            submitting
                                                ? "Creating..."
                                                : "Create Staff"
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Edit Modal -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition-opacity duration-300"
                    leave-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="showEdit"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        @click.self="showEdit = false"
                    >
                        <div
                            class="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl"
                        >
                            <h2
                                class="mb-4 text-xl font-semibold text-gray-900"
                            >
                                Edit Staff
                            </h2>
                            <form @submit.prevent="saveEdit" class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Name</label
                                    >
                                    <input
                                        v-model="editForm.name"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                        :class="{
                                            'border-red-500': errors.name,
                                        }"
                                    />
                                    <div
                                        v-if="errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        v-model="editForm.email"
                                        type="email"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                        :class="{
                                            'border-red-500': errors.email,
                                        }"
                                    />
                                    <div
                                        v-if="errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Password (leave blank to keep
                                        current)</label
                                    >
                                    <div class="relative">
                                        <input
                                            v-model="editForm.password"
                                            :type="
                                                showEditPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 focus:border-blue-500 focus:ring-blue-500"
                                            :class="{
                                                'border-red-500':
                                                    errors.password,
                                            }"
                                        />
                                        <button
                                            type="button"
                                            @click="
                                                showEditPassword =
                                                    !showEditPassword
                                            "
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                                        >
                                            <svg
                                                v-if="showEditPassword"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                                />
                                            </svg>
                                            <svg
                                                v-else
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        v-if="errors.password"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.password[0] }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Designation</label
                                    >
                                    <input
                                        v-model="editForm.designation"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Department</label
                                    >
                                    <input
                                        v-model="editForm.department"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Phone</label
                                    >
                                    <input
                                        v-model="editForm.phone"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >About</label
                                    >
                                    <textarea
                                        v-model="editForm.about"
                                        rows="3"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                                    ></textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Photo (leave blank to keep
                                        current)</label
                                    >
                                    <input
                                        @change="
                                            editForm.photo =
                                                $event.target.files[0]
                                        "
                                        type="file"
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    />
                                    <div
                                        v-if="errors.photo"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ errors.photo[0] }}
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 pt-4">
                                    <button
                                        type="button"
                                        @click="showEdit = false"
                                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                    >
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Delete Modal -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition-opacity duration-300"
                    leave-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="showDelete"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        @click.self="showDelete = false"
                    >
                        <div
                            class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
                        >
                            <h2
                                class="mb-4 text-xl font-semibold text-gray-900"
                            >
                                Delete Staff
                            </h2>
                            <p class="text-gray-600">
                                Are you sure you want to delete
                                <strong>{{ toDelete?.name }}</strong
                                >? This action cannot be undone.
                            </p>
                            <div class="flex justify-end gap-3 pt-4">
                                <button
                                    @click="showDelete = false"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="removeRow"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Toasts -->
            <div class="fixed top-4 right-4 z-50 space-y-2">
                <TransitionGroup
                    name="toast"
                    enter-active-class="transition-all duration-300"
                    leave-active-class="transition-all duration-300"
                    enter-from-class="opacity-0 translate-x-full"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 translate-x-full"
                >
                    <div
                        v-for="t in toasts"
                        :key="t.id"
                        class="flex items-start gap-3 rounded-lg border p-4 shadow-lg"
                        :class="{
                            'border-green-200 bg-green-50 text-green-800':
                                t.type === 'success',
                            'border-red-200 bg-red-50 text-red-800':
                                t.type === 'error',
                            'border-blue-200 bg-blue-50 text-blue-800':
                                t.type === 'info',
                        }"
                    >
                        <div class="flex-1">
                            <div class="font-medium">{{ t.title }}</div>
                            <div class="text-sm">{{ t.message }}</div>
                        </div>
                        <button
                            @click="
                                toasts = toasts.filter((x) => x.id !== t.id)
                            "
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </TransitionGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s;
}
.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
.toast-enter-to,
.toast-leave-from {
    opacity: 1;
    transform: translateX(0);
}
</style>
