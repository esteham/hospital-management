<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted, nextTick } from "vue";

/**
 * Props from server
 */
const props = defineProps({
    news: { type: Array, default: () => [] },
});

/**
 * Local reactive state
 */
const rawNews = ref(props.news ?? []);
const query = ref("");
const categoryFilter = ref("");
const dateFrom = ref("");
const dateTo = ref("");
const sortKey = ref("date"); // 'date' | 'title' | 'category'
const sortDir = ref("desc"); // 'asc' | 'desc'
const perPage = ref(10);
const page = ref(1);

// UI state
const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const deletingId = ref(null);
const editing = ref(null);
const globalBusy = ref(false);

// Toasts
const toasts = ref([]);
function toast(message, type = "success") {
    const id = Date.now() + Math.random();
    toasts.value.push({ id, message, type });
    setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    }, 3500);
}

/**
 * Helpers
 */
const imageUrl = (path) => {
    if (!path) return null;
    if (typeof path !== "string") return null;
    if (path.startsWith("http://") || path.startsWith("https://")) return path;
    if (path.startsWith("/")) return path; // e.g. /storage/...
    return `/storage/${path}`; // fallback to public storage
};

const formatDate = (iso) => {
    try {
        return new Date(iso).toLocaleDateString(undefined, {
            year: "numeric",
            month: "short",
            day: "2-digit",
        });
    } catch (e) {
        return iso;
    }
};

/**
 * Filters & Sorting
 */
const categories = computed(() => {
    const set = new Set(
        rawNews.value.map((n) => (n.category || "").trim()).filter(Boolean)
    );
    return Array.from(set).sort();
});

const filtered = computed(() => {
    let list = [...rawNews.value];

    // search
    if (query.value.trim()) {
        const q = query.value.toLowerCase();
        list = list.filter((n) =>
            [n.title, n.excerpt, n.category]
                .map((x) => (x || "").toLowerCase())
                .some((s) => s.includes(q))
        );
    }

    // category
    if (categoryFilter.value) {
        list = list.filter((n) => (n.category || "") === categoryFilter.value);
    }

    // date range
    if (dateFrom.value) {
        const from = new Date(dateFrom.value);
        list = list.filter(
            (n) => new Date(n.date || n.published_at || 0) >= from
        );
    }
    if (dateTo.value) {
        const to = new Date(dateTo.value);
        // include the entire end day
        to.setHours(23, 59, 59, 999);
        list = list.filter(
            (n) => new Date(n.date || n.published_at || 0) <= to
        );
    }

    // sort
    list.sort((a, b) => {
        let av, bv;
        if (sortKey.value === "date") {
            av = new Date(a.date || a.published_at || 0).getTime();
            bv = new Date(b.date || b.published_at || 0).getTime();
        } else if (sortKey.value === "title") {
            av = (a.title || "").toLowerCase();
            bv = (b.title || "").toLowerCase();
        } else {
            av = (a.category || "").toLowerCase();
            bv = (b.category || "").toLowerCase();
        }
        if (av < bv) return sortDir.value === "asc" ? -1 : 1;
        if (av > bv) return sortDir.value === "asc" ? 1 : -1;
        return 0;
    });

    return list;
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filtered.value.length / perPage.value))
);

const paged = computed(() => {
    const start = (page.value - 1) * perPage.value;
    return filtered.value.slice(start, start + perPage.value);
});

watch(
    [query, categoryFilter, dateFrom, dateTo, sortKey, sortDir, perPage],
    () => {
        page.value = 1; // reset page on any filter change
    }
);

/**
 * Forms
 */
const today = new Date().toISOString().slice(0, 10);

const createForm = useForm({
    title: "",
    excerpt: "",
    image: null,
    category: "",
    date: today,
});

const editForm = useForm({
    title: "",
    excerpt: "",
    image: null,
    category: "",
    date: today,
});

const imagePreviewCreate = ref(null);
const imagePreviewEdit = ref(null);

function onPickCreate(e) {
    const file = e?.target?.files?.[0];
    createForm.image = file || null;
    imagePreviewCreate.value = file ? URL.createObjectURL(file) : null;
}
function onPickEdit(e) {
    const file = e?.target?.files?.[0];
    editForm.image = file || null;
    imagePreviewEdit.value = file ? URL.createObjectURL(file) : null;
}

function openCreate() {
    createForm.reset();
    imagePreviewCreate.value = null;
    showCreate.value = true;
    nextTick(() => document.getElementById("create-title")?.focus());
}

function submitCreate() {
    globalBusy.value = true;
    createForm.post(route("admin.news.store"), {
        forceFormData: true,
        onSuccess: () => {
            showCreate.value = false;
            toast("News created");
        },
        onError: () => toast("Please fix the highlighted fields", "error"),
        onFinish: () => (globalBusy.value = false),
    });
}

function openEdit(item) {
    editing.value = item;
    editForm.title = item.title || "";
    editForm.excerpt = item.excerpt || "";
    editForm.image = null; // keep empty unless user picks new
    editForm.category = item.category || "";
    editForm.date = item.date ? item.date.slice(0, 10) : today;
    imagePreviewEdit.value = item.image ? imageUrl(item.image) : null;
    showEdit.value = true;
    nextTick(() => document.getElementById("edit-title")?.focus());
}

function submitEdit() {
    if (!editing.value) return;
    globalBusy.value = true;
    editForm.transform((data) => ({ ...data, _method: "put" }));
    editForm.post(route("admin.news.update", editing.value.id), {
        forceFormData: true,
        onSuccess: () => {
            showEdit.value = false;
            toast("Changes saved");
        },
        onError: () => toast("Please fix the highlighted fields", "error"),
        onFinish: () => (globalBusy.value = false),
    });
}

function askDelete(id) {
    deletingId.value = id;
    showDelete.value = true;
}

function confirmDelete() {
    if (!deletingId.value) return;
    const id = deletingId.value;
    globalBusy.value = true;
    router.delete(route("admin.news.destroy", id), {
        preserveScroll: true,
        onSuccess: () => {
            rawNews.value = rawNews.value.filter((n) => n.id !== id);
            toast("News deleted");
        },
        onError: () => toast("Failed to delete", "error"),
        onFinish: () => {
            showDelete.value = false;
            deletingId.value = null;
            globalBusy.value = false;
        },
    });
}

onMounted(() => {
    // If server updates the list via redirect, keep local in sync
    watch(
        () => props.news,
        (val) => {
            if (Array.isArray(val)) rawNews.value = val;
        },
        { immediate: true }
    );
});
</script>

<template>
    <AppLayout title="News">
        <div class="flex px-4 py-4 items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">News Management</h2>
            <div class="flex items-center gap-2">
                <button
                    @click="openCreate"
                    class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                    >
                        <path
                            d="M12 5c.552 0 1 .448 1 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H6a1 1 0 110-2h5V6c0-.552.448-1 1-1z"
                        />
                    </svg>
                    Add News
                </button>
            </div>
        </div>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Filters -->
                <div
                    class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-gray-100"
                >
                    <div
                        class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6"
                    >
                        <div class="lg:col-span-2">
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >Search</label
                            >
                            <input
                                v-model="query"
                                type="search"
                                placeholder="Search title, excerpt, category"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >Category</label
                            >
                            <select
                                v-model="categoryFilter"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">All</option>
                                <option
                                    v-for="c in categories"
                                    :key="c"
                                    :value="c"
                                >
                                    {{ c }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >From</label
                            >
                            <input
                                v-model="dateFrom"
                                type="date"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >To</label
                            >
                            <input
                                v-model="dateTo"
                                type="date"
                                class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Sort</label
                                >
                                <select
                                    v-model="sortKey"
                                    class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="date">Date</option>
                                    <option value="title">Title</option>
                                    <option value="category">Category</option>
                                </select>
                            </div>
                            <div class="pt-6">
                                <button
                                    @click="
                                        sortDir =
                                            sortDir === 'asc' ? 'desc' : 'asc'
                                    "
                                    class="inline-flex h-[38px] items-center justify-center rounded-xl border border-gray-300 px-3 text-sm shadow-sm hover:bg-gray-50"
                                >
                                    <span v-if="sortDir === 'asc'">Asc</span>
                                    <span v-else>Desc</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Image
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Title
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Category
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Date
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                <tr
                                    v-for="item in paged"
                                    :key="item.id"
                                    class="hover:bg-gray-50/50"
                                >
                                    <td class="px-6 py-3">
                                        <div
                                            v-if="item.image"
                                            class="h-12 w-16 overflow-hidden rounded-lg ring-1 ring-gray-200"
                                        >
                                            <img
                                                :src="imageUrl(item.image)"
                                                alt="news image"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <div
                                            v-else
                                            class="flex h-12 w-16 items-center justify-center rounded-lg bg-gray-100 text-[10px] uppercase tracking-wider text-gray-400 ring-1 ring-gray-200"
                                        >
                                            No image
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900">
                                            {{ item.title }}
                                        </div>
                                        <div
                                            class="line-clamp-1 text-xs text-gray-500"
                                        >
                                            {{ item.excerpt }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-700">
                                        {{ item.category }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-700">
                                        {{ formatDate(item.date) }}
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div
                                            class="inline-flex items-center gap-2"
                                        >
                                            <button
                                                @click="openEdit(item)"
                                                class="rounded-xl border border-gray-300 px-3 py-1.5 text-sm text-gray-700 shadow-sm hover:bg-gray-50"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="askDelete(item.id)"
                                                class="rounded-xl bg-red-50 px-3 py-1.5 text-sm text-red-700 ring-1 ring-inset ring-red-200 hover:bg-red-100"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="paged.length === 0">
                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center text-sm text-gray-500"
                                    >
                                        No news found. Try adjusting your
                                        filters.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex items-center justify-between gap-4 border-t border-gray-100 p-4"
                    >
                        <div class="text-sm text-gray-600">
                            Page {{ page }} of {{ totalPages }}
                        </div>
                        <div class="flex items-center gap-2">
                            <select
                                v-model.number="perPage"
                                class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option :value="5">5</option>
                                <option :value="10">10</option>
                                <option :value="25">25</option>
                                <option :value="50">50</option>
                            </select>
                            <div class="flex items-center gap-2">
                                <button
                                    :disabled="page === 1"
                                    @click="page = Math.max(1, page - 1)"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm shadow-sm enabled:hover:bg-gray-50 disabled:opacity-50"
                                >
                                    Prev
                                </button>
                                <button
                                    :disabled="page === totalPages"
                                    @click="
                                        page = Math.min(totalPages, page + 1)
                                    "
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm shadow-sm enabled:hover:bg-gray-50 disabled:opacity-50"
                                >
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <Teleport to="body">
            <transition name="fade">
                <div
                    v-if="showCreate"
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-black/40"
                        @click="showCreate = false"
                    />
                    <div
                        class="relative z-10 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl"
                    >
                        <div class="mb-4 flex items-start justify-between">
                            <h3 class="text-lg font-semibold">Create News</h3>
                            <button
                                @click="showCreate = false"
                                class="rounded-full p-1.5 hover:bg-gray-100"
                            >
                                <span class="sr-only">Close</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    class="h-5 w-5"
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
                        <form @submit.prevent="submitCreate" class="space-y-4">
                            <div>
                                <label
                                    for="create-title"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Title</label
                                >
                                <input
                                    id="create-title"
                                    v-model="createForm.title"
                                    type="text"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <p
                                    v-if="createForm.errors.title"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createForm.errors.title }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Excerpt</label
                                >
                                <textarea
                                    v-model="createForm.excerpt"
                                    rows="4"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <p
                                    v-if="createForm.errors.excerpt"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createForm.errors.excerpt }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label
                                        class="mb-1 block text-sm font-medium text-gray-700"
                                        >Category</label
                                    >
                                    <input
                                        v-model="createForm.category"
                                        type="text"
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <p
                                        v-if="createForm.errors.category"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ createForm.errors.category }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="mb-1 block text-sm font-medium text-gray-700"
                                        >Date</label
                                    >
                                    <input
                                        v-model="createForm.date"
                                        type="date"
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <p
                                        v-if="createForm.errors.date"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ createForm.errors.date }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Image (optional)</label
                                >
                                <div class="flex items-center gap-4">
                                    <label
                                        class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-dashed border-gray-300 px-4 py-2 text-sm hover:bg-gray-50"
                                    >
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            @change="onPickCreate"
                                        />
                                        Choose file
                                    </label>
                                    <img
                                        v-if="imagePreviewCreate"
                                        :src="imagePreviewCreate"
                                        class="h-12 w-16 rounded-lg object-cover ring-1 ring-gray-200"
                                    />
                                </div>
                                <p
                                    v-if="createForm.errors.image"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ createForm.errors.image }}
                                </p>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    type="button"
                                    @click="showCreate = false"
                                    class="rounded-xl border border-gray-300 px-4 py-2 text-sm shadow-sm hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="createForm.processing"
                                    class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700 disabled:opacity-60"
                                >
                                    <svg
                                        v-if="createForm.processing"
                                        class="h-4 w-4 animate-spin"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                            fill="none"
                                            opacity=".25"
                                        />
                                        <path
                                            d="M22 12a10 10 0 00-10-10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                            fill="none"
                                            stroke-linecap="round"
                                        />
                                    </svg>
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </Teleport>

        <!-- Edit Modal -->
        <Teleport to="body">
            <transition name="fade">
                <div
                    v-if="showEdit"
                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-black/40"
                        @click="showEdit = false"
                    />
                    <div
                        class="relative z-10 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl"
                    >
                        <div class="mb-4 flex items-start justify-between">
                            <h3 class="text-lg font-semibold">Edit News</h3>
                            <button
                                @click="showEdit = false"
                                class="rounded-full p-1.5 hover:bg-gray-100"
                            >
                                <span class="sr-only">Close</span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    class="h-5 w-5"
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
                        <form @submit.prevent="submitEdit" class="space-y-4">
                            <div>
                                <label
                                    for="edit-title"
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Title</label
                                >
                                <input
                                    id="edit-title"
                                    v-model="editForm.title"
                                    type="text"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <p
                                    v-if="editForm.errors.title"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ editForm.errors.title }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Excerpt</label
                                >
                                <textarea
                                    v-model="editForm.excerpt"
                                    rows="4"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <p
                                    v-if="editForm.errors.excerpt"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ editForm.errors.excerpt }}
                                </p>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label
                                        class="mb-1 block text-sm font-medium text-gray-700"
                                        >Category</label
                                    >
                                    <input
                                        v-model="editForm.category"
                                        type="text"
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <p
                                        v-if="editForm.errors.category"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ editForm.errors.category }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="mb-1 block text-sm font-medium text-gray-700"
                                        >Date</label
                                    >
                                    <input
                                        v-model="editForm.date"
                                        type="date"
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <p
                                        v-if="editForm.errors.date"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ editForm.errors.date }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="mb-1 block text-sm font-medium text-gray-700"
                                    >Image (optional)</label
                                >
                                <div class="flex items-center gap-4">
                                    <label
                                        class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-dashed border-gray-300 px-4 py-2 text-sm hover:bg-gray-50"
                                    >
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            @change="onPickEdit"
                                        />
                                        Choose file
                                    </label>
                                    <img
                                        v-if="imagePreviewEdit"
                                        :src="imagePreviewEdit"
                                        class="h-12 w-16 rounded-lg object-cover ring-1 ring-gray-200"
                                    />
                                </div>
                                <p
                                    v-if="editForm.errors.image"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ editForm.errors.image }}
                                </p>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button
                                    type="button"
                                    @click="showEdit = false"
                                    class="rounded-xl border border-gray-300 px-4 py-2 text-sm shadow-sm hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    :disabled="editForm.processing"
                                    class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700 disabled:opacity-60"
                                >
                                    <svg
                                        v-if="editForm.processing"
                                        class="h-4 w-4 animate-spin"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                            fill="none"
                                            opacity=".25"
                                        />
                                        <path
                                            d="M22 12a10 10 0 00-10-10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                            fill="none"
                                            stroke-linecap="round"
                                        />
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </Teleport>

        <!-- Delete Modal -->
        <transition name="fade">
            <div
                v-if="showDelete"
                class="fixed inset-0 z-50 grid place-items-center p-4"
            >
                <div
                    class="absolute inset-0 bg-black/40"
                    @click="showDelete = false"
                />
                <div
                    class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl"
                >
                    <h3 class="text-lg font-semibold">Delete this news?</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        This action cannot be undone.
                    </p>
                    <div class="mt-6 flex justify-end gap-2">
                        <button
                            @click="showDelete = false"
                            class="rounded-xl border border-gray-300 px-4 py-2 text-sm shadow-sm hover:bg-gray-50"
                        >
                            Cancel
                        </button>
                        <button
                            @click="confirmDelete"
                            class="rounded-2xl bg-red-600 px-4 py-2 text-white shadow hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Toasts -->
        <div
            class="pointer-events-none fixed inset-x-0 top-4 z-[60] mx-auto flex w-full max-w-xl flex-col items-center gap-2 px-4"
        >
            <div
                v-for="t in toasts"
                :key="t.id"
                class="pointer-events-auto flex w-full items-start gap-3 rounded-2xl bg-white p-3 shadow-lg ring-1 ring-gray-100"
            >
                <div
                    :class="[
                        'mt-0.5 h-2.5 w-2.5 rounded-full',
                        t.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                    ]"
                />
                <div class="text-sm text-gray-800">{{ t.message }}</div>
                <button
                    class="ml-auto rounded-full p-1 hover:bg-gray-100"
                    @click="toasts = toasts.filter((x) => x.id !== t.id)"
                >
                    <span class="sr-only">Dismiss</span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        class="h-4 w-4"
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
        </div>

        <!-- Global busy overlay -->
        <transition name="fade">
            <div
                v-if="globalBusy"
                class="fixed inset-0 z-40 grid place-items-center bg-black/20"
            >
                <div
                    class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow"
                >
                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24">
                        <circle
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                            fill="none"
                            opacity=".25"
                        />
                        <path
                            d="M22 12a10 10 0 00-10-10"
                            stroke="currentColor"
                            stroke-width="4"
                            fill="none"
                            stroke-linecap="round"
                        />
                    </svg>
                    <span class="text-sm">Working…</span>
                </div>
            </div>
        </transition>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
/* line clamp until Tailwind plugin present */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
