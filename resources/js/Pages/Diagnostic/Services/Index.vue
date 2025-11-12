<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, onMounted, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { useToast } from "vue-toastification";

/** Axios + CSRF */
const api = axios.create({ headers: { "X-Requested-With": "XMLHttpRequest" } });
const token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

/** Flash */
const page = usePage();
const flash = computed(() => page.props.value?.flash ?? {});

/** Toast */
const toast = useToast();

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
    image: "",
    price: "",
    category: "",
    sample_type: "",
    description: "",
    duration: "",
    home_collection_available: false,
    report_time: "",
});
const editForm = reactive({
    name: "",
    image: "",
    price: "",
    category: "",
    sample_type: "",
    description: "",
    duration: "",
    home_collection_available: false,
    report_time: "",
});
const errors = ref({});
const hasErrors = computed(
    () => !!errors.value && Object.keys(errors.value).length > 0
);

/** File refs */
const imageFile = ref(null);
const editImageFile = ref(null);

/** UI helpers */
const showDelete = ref(false);
const toDelete = ref(null);

/** Derived */
const filtered = computed(() => {
    const q = query.value.trim().toLowerCase();
    let arr = items.value.slice();
    if (q) {
        arr = arr.filter((d) =>
            [d.name, d.category, d.sample_type, d.description]
                .filter(Boolean)
                .some((v) => String(v).toLowerCase().includes(q))
        );
    }
    if (sortBy.value) {
        arr.sort((a, b) => {
            let aVal = a[sortBy.value];
            let bVal = b[sortBy.value];
            if (typeof aVal === "string") aVal = aVal.toLowerCase();
            if (typeof bVal === "string") bVal = bVal.toLowerCase();
            if (sortDir.value === "asc") {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });
    }
    return arr;
});

const paginated = computed(() => {
    const start = (pageIndex.value - 1) * pageSize.value;
    const end = start + pageSize.value;
    return filtered.value.slice(start, end);
});

const totalPages = computed(() =>
    Math.ceil(filtered.value.length / pageSize.value)
);

watch([query, sortBy, sortDir], () => {
    pageIndex.value = 1;
});

onMounted(() => {
    fetchData();
});

const fetchData = async () => {
    loading.value = true;
    try {
        const response = await api.get("/diagnostic/services/list");
        items.value = response.data;
    } catch (error) {
        console.error(error);
        toast.error("Failed to load services");
    } finally {
        loading.value = false;
    }
};

const submitAdd = async () => {
    submitting.value = true;
    errors.value = {};
    try {
        const formData = new FormData();
        Object.keys(form).forEach((key) => {
            if (form[key] !== null && form[key] !== undefined) {
                formData.append(key, form[key]);
            }
        });
        await api.post("/diagnostic/services", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        toast.success("Service added successfully!");
        showAdd.value = false;
        resetForm();
        fetchData();
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
            toast.error("Please fix the validation errors");
        } else {
            toast.error("Failed to add service");
        }
    } finally {
        submitting.value = false;
    }
};

const submitEdit = async () => {
    if (!editing.value || !editing.value.id) {
        console.error("No ID for editing!");
        return;
    }

    submitting.value = true;
    errors.value = {};

    try {
        const formData = new FormData();
        Object.keys(editForm).forEach((key) => {
            if (editForm[key] !== null && editForm[key] !== undefined) {
                formData.append(key, editForm[key]);
            }
        });
        // Add _method for PUT request
        formData.append("_method", "PUT");
        await api.post(`/diagnostic/services/${editing.value.id}`, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        toast.success("Service updated successfully!");
        showEdit.value = false;
        editing.value = null;
        await fetchData();
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
            toast.error("Please fix the validation errors");
        } else {
            toast.error("Failed to update service");
        }
    } finally {
        submitting.value = false;
    }
};

const deleteItem = async () => {
    try {
        await api.delete(`/diagnostic/services/${toDelete.value.id}`);
        toast.success("Service deleted successfully!");
        showDelete.value = false;
        toDelete.value = null;
        fetchData();
    } catch (error) {
        console.error(error);
        toast.error("Failed to delete service");
    }
};

const resetForm = () => {
    Object.assign(form, {
        name: "",
        image: "",
        price: "",
        category: "",
        sample_type: "",
        description: "",
        duration: "",
        home_collection_available: false,
        report_time: "",
    });
    errors.value = {};
};

const openEdit = (item) => {
    if (!item || !item.id) {
        console.error("Invalid item to edit", item);
        return;
    }

    editing.value = item;
    Object.assign(editForm, {
        name: item.name,
        image: item.image,
        price: item.price,
        category: item.category,
        sample_type: item.sample_type,
        description: item.description,
        duration: item.duration,
        home_collection_available: item.home_collection_available,
        report_time: item.report_time,
    });
    errors.value = {};
    showEdit.value = true;
};

const openDelete = (item) => {
    toDelete.value = item;
    showDelete.value = true;
};

const closeModal = () => {
    showAdd.value = false;
    showEdit.value = false;
    showDelete.value = false;
    editing.value = null;
    toDelete.value = null;
    errors.value = {};
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Diagnostic Services
                            </h1>
                            <p class="mt-2 text-sm text-gray-600">
                                Manage your diagnostic services and test
                                offerings
                            </p>
                        </div>
                        <button
                            @click="showAdd = true"
                            class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                            Add Service
                        </button>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div v-if="flash.success" class="mb-6">
                    <div
                        class="rounded-md bg-green-50 p-4 border border-green-200"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-green-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ flash.success }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="flash.error" class="mb-6">
                    <div class="rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-red-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ flash.error }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Controls -->
                <div
                    class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4"
                    >
                        <div class="flex-1">
                            <div class="relative rounded-md shadow-sm">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="query"
                                    type="text"
                                    placeholder="Search services..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                        </div>
                        <div
                            class="flex items-center space-x-4 text-sm text-gray-600"
                        >
                            <span>{{ filtered.length }} services found</span>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div
                    class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden"
                >
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        @click="
                                            sortBy = 'name';
                                            sortDir =
                                                sortDir === 'asc'
                                                    ? 'desc'
                                                    : 'asc';
                                        "
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-150 group"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <span>Name</span>
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
                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                                />
                                            </svg>
                                        </div>
                                    </th>
                                    <th
                                        @click="
                                            sortBy = 'category';
                                            sortDir =
                                                sortDir === 'asc'
                                                    ? 'desc'
                                                    : 'asc';
                                        "
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-150 group"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <span>Category</span>
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
                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                                />
                                            </svg>
                                        </div>
                                    </th>
                                    <th
                                        @click="
                                            sortBy = 'price';
                                            sortDir =
                                                sortDir === 'asc'
                                                    ? 'desc'
                                                    : 'asc';
                                        "
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-150 group"
                                    >
                                        <div
                                            class="flex items-center space-x-1"
                                        >
                                            <span>Price</span>
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
                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                                />
                                            </svg>
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading">
                                    <td
                                        colspan="4"
                                        class="px-6 py-8 text-center"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <svg
                                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600"
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
                                            <span class="text-gray-600"
                                                >Loading services...</span
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-else-if="paginated.length === 0"
                                    class="hover:bg-gray-50 transition-colors duration-150"
                                >
                                    <td
                                        colspan="4"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <p
                                            class="mt-2 text-sm font-medium text-gray-900"
                                        >
                                            No services found
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Try adjusting your search or add a
                                            new service.
                                        </p>
                                    </td>
                                </tr>
                                <tr
                                    v-else
                                    v-for="item in paginated"
                                    :key="item.id"
                                    class="hover:bg-gray-50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img
                                                v-if="item.image"
                                                :src="'/storage/' + item.image"
                                                alt="Service Image"
                                                class="w-8 h-10 rounded-lg object-cover mr-3"
                                            />
                                            <div>
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ item.name }}
                                                </div>
                                                <div
                                                    v-if="item.description"
                                                    class="text-sm text-gray-500 truncate max-w-xs"
                                                >
                                                    {{ item.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{
                                                item.category || "Uncategorized"
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900"
                                    >
                                        ৳{{
                                            Number(item.price).toLocaleString()
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <button
                                                @click="openEdit(item)"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    />
                                                </svg>
                                                Edit
                                            </button>
                                            <button
                                                @click="openDelete(item)"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="paginated.length > 0"
                        class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6"
                    >
                        <div class="flex items-center justify-between">
                            <div
                                class="flex-1 flex justify-between items-center"
                            >
                                <div class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{
                                        (pageIndex - 1) * pageSize + 1
                                    }}</span>
                                    to
                                    <span class="font-medium">{{
                                        Math.min(
                                            pageIndex * pageSize,
                                            filtered.length
                                        )
                                    }}</span>
                                    of
                                    <span class="font-medium">{{
                                        filtered.length
                                    }}</span>
                                    results
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        @click="pageIndex--"
                                        :disabled="pageIndex <= 1"
                                        class="relative inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        Previous
                                    </button>
                                    <span class="text-sm text-gray-700 px-3">
                                        Page {{ pageIndex }} of {{ totalPages }}
                                    </span>
                                    <button
                                        @click="pageIndex++"
                                        :disabled="pageIndex >= totalPages"
                                        class="relative inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div
            v-if="showAdd"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[999] transition-opacity duration-300"
            @click="closeModal"
        >
            <div
                class="relative top-10 mx-auto max-w-md transform transition-all duration-300"
                @click.stop
            >
                <div
                    class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
                >
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Add New Service
                            </h3>
                            <button
                                @click="closeModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
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
                    <form @submit.prevent="submitAdd" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Service Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter service name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Price (BDT)</label
                                >
                                <input
                                    v-model="form.price"
                                    type="number"
                                    step="1"
                                    placeholder="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Category</label
                                >
                                <input
                                    v-model="form.category"
                                    type="text"
                                    placeholder="Enter category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Sample Type</label
                                >
                                <input
                                    v-model="form.sample_type"
                                    type="text"
                                    placeholder="Enter sample type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Image</label
                                >
                                <input
                                    ref="imageFile"
                                    type="file"
                                    accept="image/*"
                                    @change="
                                        form.image = $event.target.files[0]
                                    "
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Description</label
                                >
                                <textarea
                                    v-model="form.description"
                                    placeholder="Enter service description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                ></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Duration (min)</label
                                    >
                                    <input
                                        v-model="form.duration"
                                        type="number"
                                        placeholder="Duration"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Report Time (hrs)</label
                                    >
                                    <input
                                        v-model="form.report_time"
                                        type="number"
                                        placeholder="Report time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    />
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input
                                    v-model="form.home_collection_available"
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label class="ml-2 block text-sm text-gray-700">
                                    Home Collection Available
                                </label>
                            </div>
                        </div>

                        <!-- Validation Errors -->
                        <div
                            v-if="hasErrors"
                            class="rounded-md bg-red-50 p-4 border border-red-200"
                        >
                            <div class="text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li
                                        v-for="(error, key) in errors"
                                        :key="key"
                                    >
                                        {{ error[0] }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200"
                        >
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                            >
                                <span
                                    v-if="submitting"
                                    class="flex items-center"
                                >
                                    <svg
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    Adding...
                                </span>
                                <span v-else>Add Service</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div
            v-if="showEdit"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[1] transition-opacity duration-300"
            @click="closeModal"
        >
            <div
                class="relative top-10 mx-auto max-w-md transform transition-all duration-300"
                @click.stop
            >
                <div
                    class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
                >
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Edit Service
                            </h3>
                            <button
                                @click="closeModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
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
                    <form @submit.prevent="submitEdit" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Service Name</label
                                >
                                <input
                                    v-model="editForm.name"
                                    type="text"
                                    placeholder="Enter service name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Price (BDT)</label
                                >
                                <input
                                    v-model="editForm.price"
                                    type="number"
                                    step="1"
                                    placeholder="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Category</label
                                >
                                <input
                                    v-model="editForm.category"
                                    type="text"
                                    placeholder="Enter category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Sample Type</label
                                >
                                <input
                                    v-model="editForm.sample_type"
                                    type="text"
                                    placeholder="Enter sample type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Image</label
                                >
                                <input
                                    ref="editImageFile"
                                    type="file"
                                    accept="image/*"
                                    @change="
                                        editForm.image = $event.target.files[0]
                                    "
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                />
                                <p
                                    v-if="editing?.image"
                                    class="mt-1 text-sm text-gray-500"
                                >
                                    Current image: {{ editing.image }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Description</label
                                >
                                <textarea
                                    v-model="editForm.description"
                                    placeholder="Enter service description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                ></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Duration (min)</label
                                    >
                                    <input
                                        v-model="editForm.duration"
                                        type="number"
                                        placeholder="Duration"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Report Time (hrs)</label
                                    >
                                    <input
                                        v-model="editForm.report_time"
                                        type="number"
                                        placeholder="Report time"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    />
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input
                                    v-model="editForm.home_collection_available"
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label class="ml-2 block text-sm text-gray-700">
                                    Home Collection Available
                                </label>
                            </div>
                        </div>

                        <!-- Validation Errors -->
                        <div
                            v-if="hasErrors"
                            class="rounded-md bg-red-50 p-4 border border-red-200"
                        >
                            <div class="text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li
                                        v-for="(error, key) in errors"
                                        :key="key"
                                    >
                                        {{ error[0] }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200"
                        >
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                            >
                                <span
                                    v-if="submitting"
                                    class="flex items-center"
                                >
                                    <svg
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                    Updating...
                                </span>
                                <span v-else>Update Service</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div
            v-if="showDelete"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[999] transition-opacity duration-300"
            @click="closeModal"
        >
            <div
                class="relative top-20 mx-auto max-w-sm transform transition-all duration-300"
                @click.stop
            >
                <div
                    class="bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden"
                >
                    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-6 w-6 text-red-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-red-800">
                                    Delete Service
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600">
                            Are you sure you want to delete
                            <span class="font-semibold text-gray-900">{{
                                toDelete?.name
                            }}</span
                            >? This action cannot be undone.
                        </p>
                        <div
                            class="mt-6 flex items-center justify-end space-x-3"
                        >
                            <button
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Cancel
                            </button>
                            <button
                                @click="deleteItem"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                            >
                                Delete Service
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Smooth transitions for modal backdrop */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* Custom scrollbar for modal */
.modal-content {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>
