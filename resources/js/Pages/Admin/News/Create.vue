<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import axios from "axios";

/**
 * Forms
 */
const today = new Date().toISOString().slice(0, 10);

const createForm = useForm({
    title: "",
    excerpt: "",
    content: "",
    image: null,
    category: "",
    date: today,
});

const imagePreviewCreate = ref(null);

const editorOptions = ref({
    modules: {
        toolbar: {
            container: [
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline", "strike"],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ script: "sub" }, { script: "super" }],
                [{ indent: "-1" }, { indent: "+1" }],
                [{ direction: "rtl" }],
                [{ color: [] }, { background: [] }],
                [{ align: [] }],
                ["link", "image", "video"],
                ["clean"],
            ],
            handlers: {
                image: imageHandler,
            },
        },
    },
});

function onPickCreate(e) {
    const file = e?.target?.files?.[0];
    createForm.image = file || null;
    imagePreviewCreate.value = file ? URL.createObjectURL(file) : null;
}

function imageHandler() {
    const input = document.createElement("input");
    input.setAttribute("type", "file");
    input.setAttribute("accept", "image/*");
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        if (file) {
            const formData = new FormData();
            formData.append("image", file);

            try {
                const response = await axios.post(
                    route("admin.news.upload-image"),
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                const imageUrl = response.data.url;
                const quill = this.quill;
                const range = quill.getSelection();
                const index = range ? range.index : quill.getLength();
                quill.insertEmbed(index, "image", imageUrl);
            } catch (error) {
                console.error("Image upload failed:", error);
                alert("Image upload failed. Please try again.");
            }
        }
    };
}

function submitCreate() {
    createForm.post(route("admin.news.store"), {
        forceFormData: true,
        onSuccess: () => {
            router.visit(route("admin.news.index"));
        },
        onError: () => {
            // errors handled by form
        },
    });
}

onMounted(() => {
    document.getElementById("create-title")?.focus();
});
</script>

<template>
    <AppLayout title="Create News">
        <div class="flex px-4 py-4 items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Create News</h2>
            <div class="flex items-center gap-2">
                <Link
                    :href="route('admin.news.index')"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gray-600 px-4 py-2 text-white shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Back to News
                </Link>
            </div>
        </div>

        <div class="py-8">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
                >
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
                                rows="2"
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
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >Content</label
                            >
                            <QuillEditor
                                v-model="createForm.content"
                                :options="editorOptions"
                                class="quill-field"
                                required
                            />
                            <p
                                v-if="createForm.errors.content"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ createForm.errors.content }}
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
                            <Link
                                :href="route('admin.news.index')"
                                class="rounded-xl border border-gray-300 px-4 py-2 text-sm shadow-sm hover:bg-gray-50"
                            >
                                Cancel
                            </Link>
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
        </div>
    </AppLayout>
</template>
<style scoped>
:deep(.quill-field .ql-editor) {
    @apply min-h-[500px];
}
</style>
