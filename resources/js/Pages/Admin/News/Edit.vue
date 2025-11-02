<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import { ref, onMounted, nextTick } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import axios from "axios";

/**
 * Props from server
 */
const props = defineProps({
    news: { type: Object, required: true },
});

/**
 * Forms
 */
const editForm = useForm({
    title: props.news.title || "",
    excerpt: props.news.excerpt || "",
    content: props.news.content || "",
    image: null,
    category: props.news.category || "",
    date: props.news.date
        ? props.news.date.slice(0, 10)
        : new Date().toISOString().slice(0, 10),
});

const imagePreviewEdit = ref(
    props.news.image ? `/storage/${props.news.image}` : null
);

const editorRef = ref(null);

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

function onPickEdit(e) {
    const file = e?.target?.files?.[0];
    editForm.image = file || null;
    imagePreviewEdit.value = file ? URL.createObjectURL(file) : null;
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
                const quill = editorRef.value?.getQuill();
                const range = quill?.getSelection();
                const index = range ? range.index : quill.getLength();
                quill.insertEmbed(index, "image", imageUrl);
            } catch (error) {
                console.error("Image upload failed:", error);
                alert("Image upload failed. Please try again.");
            }
        }
    };
}

function submitEdit() {
    editForm.transform((data) => ({ ...data, _method: "put" }));
    editForm.post(route("admin.news.update", props.news.id), {
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
    document.getElementById("edit-title")?.focus();
    // Set content after mount to ensure Quill is ready
    nextTick(() => {
        if (editorRef.value) {
            editorRef.value.setHTML(props.news.content || "");
        }
    });
});
</script>

<template>
    <AppLayout title="Edit News">
        <div class="flex px-4 py-4 items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit News</h2>
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
                                rows="2"
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
                        <div>
                            <label
                                class="mb-1 block text-sm font-medium text-gray-700"
                                >Content</label
                            >
                            <QuillEditor
                                ref="editorRef"
                                v-model:content="editForm.content"
                                content-type="html"
                                :options="editorOptions"
                                class="quill-field"
                                required
                            />
                            <p
                                v-if="editForm.errors.content"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ editForm.errors.content }}
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
                            <Link
                                :href="route('admin.news.index')"
                                class="rounded-xl border border-gray-300 px-4 py-2 text-sm shadow-sm hover:bg-gray-50"
                            >
                                Cancel
                            </Link>
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
        </div>
    </AppLayout>
</template>
<style scoped>
:deep(.quill-field .ql-editor) {
    @apply min-h-[300px];
}
</style>
