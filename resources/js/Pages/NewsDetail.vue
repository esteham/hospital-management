<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Head, usePage } from "@inertiajs/vue3";

const page = usePage();
const news = ref(null);
const loading = ref(true);

const fetchNewsDetail = async () => {
    try {
        const response = await axios.get(`/api/news/${page.props.id}`);
        news.value = response.data;
    } catch (error) {
        console.error("Error fetching news detail:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchNewsDetail();
});
</script>

<template>
    <Head :title="news ? news.title : 'News Detail'" />

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div v-if="loading" class="text-center">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"
                ></div>
                <p class="mt-4 text-gray-600">Loading news...</p>
            </div>

            <div
                v-else-if="news"
                class="bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <img
                    :src="news.image"
                    :alt="news.title"
                    class="w-full h-64 object-cover"
                />

                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold"
                        >
                            {{ news.category }}
                        </span>
                        <span class="text-gray-500 text-sm">{{
                            new Date(news.date).toLocaleDateString("en-US", {
                                month: "short",
                                day: "numeric",
                                year: "numeric",
                            })
                        }}</span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-6">
                        {{ news.title }}
                    </h1>

                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-600 leading-relaxed mb-6">
                            {{ news.excerpt }}
                        </p>

                        <div
                            v-html="news.content"
                            class="text-gray-700 leading-relaxed"
                        ></div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    News Not Found
                </h2>
                <p class="text-gray-600">
                    The news article you're looking for doesn't exist.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.prose :deep(p) {
    margin-bottom: 1rem;
}

.prose :deep(h2) {
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose :deep(h3) {
    font-size: 1.25rem;
    font-weight: bold;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.prose :deep(ul) {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose :deep(ol) {
    list-style-type: decimal;
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}
</style>
