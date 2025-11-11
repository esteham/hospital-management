<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, reactive, onMounted, computed, watch } from "vue";
import axios from "axios";

/** Axios (CSRF + XHR) */
const api = axios.create({ headers: { "X-Requested-With": "XMLHttpRequest" } });
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

/** State */
const loading = ref(false);
const sending = ref(false);
const rows = ref([]);
const search = ref("");
const debounced = ref("");
const sortBy = ref("doctor");
const sortDir = ref("asc");

/** Pagination */
const page = ref(1);
const pageSize = ref(10);
const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredRows.value.length / pageSize.value))
);
const pagedRows = computed(() => {
    const start = (page.value - 1) * pageSize.value;
    return sortedRows.value.slice(start, start + pageSize.value);
});

/** Mention modal */
const isMentionOpen = ref(false);
const mentionForm = reactive({
    schedule_id: null,
    doctor_id: null,
    text: "",
});

/** Toasts */
const toast = reactive({ show: false, type: "success", message: "" });
function showToast(message, type = "success", timeout = 2000) {
    toast.message = message;
    toast.type = type;
    toast.show = true;
    setTimeout(() => (toast.show = false), timeout);
}

/** Data fetching */
async function loadList() {
    loading.value = true;
    try {
        const { data } = await api.get("/admin/schedules/list", {
            params: { search: debounced.value || "" },
        });
        rows.value = Array.isArray(data) ? data : [];
        // Reset to page 1 on fresh fetch
        page.value = 1;
    } catch (e) {
        showToast(
            e?.response?.data?.message || "Failed to load schedules",
            "error",
            3500
        );
    } finally {
        loading.value = false;
    }
}

/** Search: debounce user input (300ms) */
let t = null;
watch(search, (v) => {
    if (t) clearTimeout(t);
    t = setTimeout(() => {
        debounced.value = v;
        loadList();
    }, 300);
});

/** Sorting */
function toggleSort(key) {
    if (sortBy.value === key) {
        sortDir.value = sortDir.value === "asc" ? "desc" : "asc";
    } else {
        sortBy.value = key;
        sortDir.value = "asc";
    }
}
const sortedRows = computed(() => {
    const dir = sortDir.value === "asc" ? 1 : -1;
    const key = sortBy.value;
    return [...rows.value].sort((a, b) => {
        const va = (a?.[key] ?? "").toString().toLowerCase();
        const vb = (b?.[key] ?? "").toString().toLowerCase();
        if (va < vb) return -1 * dir;
        if (va > vb) return 1 * dir;
        return 0;
    });
});

/** Filtered (in case you extend client-side filtering later) */
const filteredRows = computed(() => sortedRows.value);

/** Mention modal controls */
function openMention(r) {
    isMentionOpen.value = true;
    mentionForm.schedule_id = r.id;
    mentionForm.doctor_id = r.doctor_id;
    mentionForm.text = "";
}
function closeMention() {
    isMentionOpen.value = false;
    mentionForm.schedule_id = null;
    mentionForm.doctor_id = null;
    mentionForm.text = "";
}

/** Send mention */
async function sendMention() {
    if (!mentionForm.text.trim()) {
        showToast("Write a message first", "error");
        return;
    }
    sending.value = true;
    try {
        await api.post("/admin/schedules/mention", {
            schedule_id: mentionForm.schedule_id,
            doctor_id: mentionForm.doctor_id,
            message: mentionForm.text.trim(),
        });
        showToast("Message sent");
        closeMention();
    } catch (e) {
        showToast(
            e?.response?.data?.message || "Failed to send message",
            "error",
            3500
        );
    } finally {
        sending.value = false;
    }
}

/** Helpers */
function resetSearch() {
    search.value = "";
    debounced.value = "";
    loadList();
}

/** Mount */
onMounted(loadList);
</script>

<template>
    <AuthenticatedLayout title="Doctors Schedules">
        <div class="space-y-5 px-4 py-3">
            <!-- Toolbar -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-80">
                        <input
                            v-model.trim="search"
                            @keyup.enter="loadList"
                            placeholder="Search by doctor or email"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            aria-label="Search schedules"
                        />
                        <span
                            class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                            >⌘K</span
                        >
                    </div>
                    <div class="flex gap-3">
                        <button
                            class="rounded-xl border bg-white px-4 py-2 text-sm font-medium shadow-sm transition hover:bg-gray-50 active:scale-[.98]"
                            @click="loadList"
                            :disabled="loading"
                        >
                            Search
                        </button>
                        <button
                            class="rounded-xl border px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            @click="resetSearch"
                            :disabled="loading"
                        >
                            Reset
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-500 sm:hidden"
                            >Rows per page</label
                        >
                        <select
                            v-model.number="pageSize"
                            class="rounded-xl border border-gray-300 bg-white px-6 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        >
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </div>
                    <button
                        class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[.98] w-full sm:w-auto"
                        @click="loadList"
                        :disabled="loading"
                    >
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-2 py-3 font-semibold sm:px-4">
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('doctor')"
                                    >
                                        Doctor
                                        <SortIcon
                                            :active="sortBy === 'doctor'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th
                                    class="px-2 py-3 font-semibold sm:px-4 hidden sm:table-cell"
                                >
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('email')"
                                    >
                                        Email
                                        <SortIcon
                                            :active="sortBy === 'email'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th class="px-2 py-3 font-semibold sm:px-4">
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('day')"
                                    >
                                        Day
                                        <SortIcon
                                            :active="sortBy === 'day'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th class="px-2 py-3 font-semibold sm:px-4">
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('time')"
                                    >
                                        Time
                                        <SortIcon
                                            :active="sortBy === 'time'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th
                                    class="px-2 py-3 font-semibold sm:px-4 hidden md:table-cell"
                                >
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('slot')"
                                    >
                                        Slot
                                        <SortIcon
                                            :active="sortBy === 'slot'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th
                                    class="px-2 py-3 font-semibold sm:px-4 hidden md:table-cell"
                                >
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('max')"
                                    >
                                        Max/day
                                        <SortIcon
                                            :active="sortBy === 'max'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th
                                    class="px-2 py-3 font-semibold sm:px-4 hidden md:table-cell"
                                >
                                    <button
                                        class="flex items-center gap-1 hover:underline"
                                        @click="toggleSort('fee')"
                                    >
                                        Fees
                                        <SortIcon
                                            :active="sortBy === 'fee'"
                                            :dir="sortDir"
                                        />
                                    </button>
                                </th>
                                <th
                                    class="px-2 py-3 font-semibold sm:px-4"
                                ></th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Loading skeleton -->
                            <tr
                                v-if="loading"
                                v-for="i in 6"
                                :key="'skeleton-' + i"
                                class="border-t"
                            >
                                <td class="px-2 py-3 sm:px-4" colspan="8">
                                    <div
                                        class="h-5 w-full animate-pulse rounded bg-gray-100"
                                    ></div>
                                </td>
                            </tr>

                            <!-- Rows -->
                            <tr
                                v-for="r in pagedRows"
                                :key="r.id"
                                class="border-t hover:bg-gray-50"
                            >
                                <td
                                    class="px-2 py-3 font-medium text-gray-800 sm:px-4"
                                >
                                    {{ r.doctor }}
                                </td>
                                <td
                                    class="px-2 py-3 text-gray-700 sm:px-4 hidden sm:table-cell"
                                >
                                    <a
                                        :href="'mailto:' + r.email"
                                        class="text-blue-600 hover:underline"
                                        >{{ r.email }}</a
                                    >
                                </td>
                                <td class="px-2 py-3 sm:px-4">{{ r.day }}</td>
                                <td class="px-2 py-3 sm:px-4">{{ r.time }}</td>
                                <td
                                    class="px-2 py-3 sm:px-4 hidden md:table-cell"
                                >
                                    {{ r.slot }}
                                </td>
                                <td
                                    class="px-2 py-3 sm:px-4 hidden md:table-cell"
                                >
                                    {{ r.max }}
                                </td>
                                <td
                                    class="px-2 py-3 sm:px-4 hidden md:table-cell"
                                >
                                    {{ r.fee }}
                                </td>
                                <td class="px-2 py-3 sm:px-4">
                                    <button
                                        class="rounded-lg border px-3 py-1.5 text-sm font-medium transition hover:bg-gray-100"
                                        @click="openMention(r)"
                                    >
                                        Mention
                                    </button>
                                </td>
                            </tr>

                            <!-- Empty state -->
                            <tr v-if="!loading && rows.length === 0">
                                <td colspan="8" class="px-2 py-12 sm:px-4">
                                    <div class="mx-auto max-w-md text-center">
                                        <div
                                            class="mx-auto mb-4 h-12 w-12 rounded-full bg-gray-100"
                                        ></div>
                                        <h3
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            No schedules found
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Try adjusting your search or refresh
                                            the list.
                                        </p>
                                        <div
                                            class="mt-4 flex items-center justify-center gap-2"
                                        >
                                            <button
                                                class="rounded-xl border px-4 py-2 text-sm font-medium transition hover:bg-gray-50"
                                                @click="resetSearch"
                                            >
                                                Clear filters
                                            </button>
                                            <button
                                                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                                                @click="loadList"
                                            >
                                                Refresh
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div
                    class="flex flex-col items-center justify-between gap-3 border-t bg-gray-50 px-2 py-3 text-sm sm:flex-row sm:px-4"
                >
                    <div class="text-gray-600">
                        Page <span class="font-semibold">{{ page }}</span> of
                        <span class="font-semibold">{{ totalPages }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="rounded-lg border px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40"
                            :disabled="page === 1"
                            @click="page = Math.max(1, page - 1)"
                        >
                            Prev
                        </button>
                        <button
                            class="rounded-lg border px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-40"
                            :disabled="page === totalPages"
                            @click="page = Math.min(totalPages, page + 1)"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mention Modal -->
            <transition name="fade">
                <div
                    v-if="isMentionOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
                    aria-modal="true"
                    role="dialog"
                >
                    <div
                        class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl mx-4"
                    >
                        <div class="flex items-start justify-between">
                            <h3 class="text-lg font-semibold">
                                Private message to doctor
                            </h3>
                            <button
                                class="rounded-lg p-1 hover:bg-gray-100"
                                @click="closeMention"
                                aria-label="Close"
                            >
                                ✕
                            </button>
                        </div>
                        <div class="mt-4">
                            <textarea
                                v-model="mentionForm.text"
                                rows="5"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                placeholder="Write a private message…"
                            />
                            <div
                                class="mt-2 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between text-xs text-gray-500"
                            >
                                <span
                                    >{{
                                        mentionForm.text.length
                                    }}
                                    characters</span
                                >
                                <span
                                    >Schedule #{{
                                        mentionForm.schedule_id
                                    }}</span
                                >
                            </div>
                        </div>
                        <div
                            class="mt-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end"
                        >
                            <button
                                class="rounded-xl border px-4 py-2 text-sm hover:bg-gray-50 w-full sm:w-auto"
                                @click="closeMention"
                            >
                                Cancel
                            </button>
                            <button
                                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:opacity-50 w-full sm:w-auto"
                                :disabled="sending || !mentionForm.text.trim()"
                                @click="sendMention"
                            >
                                {{ sending ? "Sending…" : "Send" }}
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Toast -->
            <transition name="slide-up">
                <div
                    v-if="toast.show"
                    :class="[
                        'fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-xl px-4 py-2 text-sm shadow-lg max-w-xs sm:max-w-sm text-center',
                        toast.type === 'error'
                            ? 'bg-red-600 text-white'
                            : 'bg-gray-900 text-white',
                    ]"
                    role="status"
                >
                    {{ toast.message }}
                </div>
            </transition>
        </div>
    </AuthenticatedLayout>
</template>

<script>
// Local inline component for sort chevron (no external deps)
export default {
    components: {
        SortIcon: {
            props: { active: Boolean, dir: String },
            template: `
        <span class="inline-flex h-4 w-4 items-center justify-center">
          <svg v-if="active && dir==='asc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-4 w-4 fill-current opacity-70"><path d="M10 5l5 6H5l5-6z"/></svg>
          <svg v-else-if="active && dir==='desc'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-4 w-4 fill-current opacity-70"><path d="M10 15l-5-6h10l-5 6z"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-4 w-4 fill-current opacity-30"><path d="M7 7h6l-3-3-3 3zm0 6l3 3 3-3H7z"/></svg>
        </span>
      `,
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
    transform: translateY(8px);
    opacity: 0;
}
</style>
