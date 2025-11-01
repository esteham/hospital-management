<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    news: Array,
});

const news = ref(props.news ?? []);

// Modal state
const showCreate = ref(false);
const showEdit = ref(false);
const editing = ref(null);

// Create form
const createForm = useForm({
    title: "",
    excerpt: "",
    image: "",
    category: "",
    date: new Date().toISOString().slice(0, 10),
});

const openCreate = () => {
    createForm.reset();
    showCreate.value = true;
};

const submitCreate = () => {
    createForm.post(route("admin.news.store"), {
        onFinish: () => {
            // Inertia will follow redirects; close modal for local UX
            showCreate.value = false;
        },
    });
};

// Edit form
const editForm = useForm({
    title: "",
    excerpt: "",
    image: "",
    category: "",
    date: new Date().toISOString().slice(0, 10),
});

const openEdit = (item) => {
    editing.value = item;
    editForm.title = item.title || "";
    editForm.excerpt = item.excerpt || "";
    editForm.image = item.image || "";
    editForm.category = item.category || "";
    editForm.date = item.date
        ? item.date.slice(0, 10)
        : new Date().toISOString().slice(0, 10);
    showEdit.value = true;
};

const submitEdit = () => {
    if (!editing.value) return;
    // Send as POST but set X-HTTP-Method-Override so method spoofing works when body is multipart/form-data
    editForm.post(route("admin.news.update", editing.value.id), {
        headers: { "X-HTTP-Method-Override": "PUT" },
        onFinish: () => {
            showEdit.value = false;
        },
    });
};

const deleteNews = (id) => {
    if (confirm("Are you sure you want to delete this news item?")) {
        axios
            .delete(route("admin.news.destroy", id))
            .then((response) => {
                news.value = news.value.filter((n) => n.id !== id);
                alert("News deleted successfully.");
            })
            .catch((error) => {
                console.error(error);
                alert("Failed to delete news.");
            });
    }
};

const imageUrl = (path) => {
    if (!path) return null;
    try {
        if (path.startsWith("http://") || path.startsWith("https://"))
            return path;
        if (path.startsWith("/")) return path; // already an absolute path like /storage/...
    } catch (e) {
        // not a string
    }
    return "/storage/" + path;
};
</script>

<template>
    <AppLayout title="News">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                News Management
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">
                                News
                            </h3>
                            <button
                                @click="openCreate"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >
                                Add News
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Image
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Title
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Date
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr v-for="item in news" :key="item.id">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            <div
                                                v-if="item.image"
                                                class="w-16 h-12 overflow-hidden rounded"
                                            >
                                                <img
                                                    :src="imageUrl(item.image)"
                                                    class="w-full h-full object-cover"
                                                    alt="news image"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400"
                                            >
                                                No image
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ item.title }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ item.category }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                        >
                                            {{ item.date }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <button
                                                @click="openEdit(item)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="deleteNews(item.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create Modal -->
        <div
            v-if="showCreate"
            class="inset-0 z-50 flex items-center justify-center"
        >
            <div
                class="fixed inset-0 bg-black/50"
                @click="showCreate = false"
            ></div>
            <div
                class="bg-white rounded-lg shadow-lg z-50 max-w-2xl w-full p-6 mx-4"
            >
                <h3 class="text-lg font-medium mb-4">Create News</h3>
                <form @submit.prevent="submitCreate">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Title</label
                        >
                        <input
                            v-model="createForm.title"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="createForm.errors.title"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ createForm.errors.title }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Excerpt</label
                        >
                        <textarea
                            v-model="createForm.excerpt"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        ></textarea>
                        <div
                            v-if="createForm.errors.excerpt"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ createForm.errors.excerpt }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Category</label
                        >
                        <input
                            v-model="createForm.category"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="createForm.errors.category"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ createForm.errors.category }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Date</label
                        >
                        <input
                            v-model="createForm.date"
                            type="date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="createForm.errors.date"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ createForm.errors.date }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Image (optional)</label
                        >
                        <input
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            @change="
                                (e) => (createForm.image = e.target.files[0])
                            "
                        />
                        <div
                            v-if="createForm.errors.image"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ createForm.errors.image }}
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showCreate = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div
            v-if="showEdit"
            class="inset-0 z-[9999] flex items-center justify-center p-4"
        >
            <div
                class="fixed inset-0 bg-black/50"
                @click="showEdit = false"
            ></div>
            <div
                class="bg-white rounded-lg shadow-lg z-50 max-w-2xl w-full p-6 mx-4"
            >
                <h3 class="text-lg font-medium mb-4">Edit News</h3>
                <form @submit.prevent="submitEdit">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Title</label
                        >
                        <input
                            v-model="editForm.title"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="editForm.errors.title"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ editForm.errors.title }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Excerpt</label
                        >
                        <textarea
                            v-model="editForm.excerpt"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        ></textarea>
                        <div
                            v-if="editForm.errors.excerpt"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ editForm.errors.excerpt }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Category</label
                        >
                        <input
                            v-model="editForm.category"
                            type="text"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="editForm.errors.category"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ editForm.errors.category }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Date</label
                        >
                        <input
                            v-model="editForm.date"
                            type="date"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        />
                        <div
                            v-if="editForm.errors.date"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ editForm.errors.date }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"
                            >Image (optional)</label
                        >
                        <input
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            @change="
                                (e) => (editForm.image = e.target.files[0])
                            "
                        />
                        <div
                            v-if="editForm.errors.image"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ editForm.errors.image }}
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="showEdit = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="editForm.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
