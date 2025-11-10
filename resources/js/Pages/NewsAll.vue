<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const newsItems = ref([]);
const loading = ref(true);

const fetchNews = async () => {
    try {
        const response = await axios.get("/api/news");
        newsItems.value = response.data;
    } catch (error) {
        console.error("Error fetching news:", error);
    } finally {
        loading.value = false;
    }
};

const imageUrl = (img) => {
    if (!img) return null;
    if (typeof img === "string") {
        if (img.startsWith("http://") || img.startsWith("https://")) return img;
        if (img.startsWith("/")) return img; // already a full path like /storage/...
    }
    return "/storage/" + img;
};

onMounted(() => {
    fetchNews();
});
</script>

<template>
    <Head title="All News & Media" />
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="text-center mb-12 sm:mb-16">
                <h1
                    class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 mb-4"
                >
                    All News & Media
                </h1>
                <p
                    class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Stay updated with the latest news, innovations, and
                    community initiatives from Xet Hospital.
                </p>
            </div>

            <div v-if="loading" class="text-center">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"
                ></div>
                <p class="mt-4 text-gray-600">Loading news...</p>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"
            >
                <div
                    v-for="news in newsItems"
                    :key="news.id || news.title"
                    class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden cursor-pointer"
                    @click="$inertia.visit(`/news/${news.id}`)"
                >
                    <img
                        :src="imageUrl(news.image)"
                        :alt="news.title"
                        class="w-full h-48 object-cover"
                    />

                    <div class="p-4 sm:p-6">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2"
                        >
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold self-start"
                            >
                                {{ news.category }}
                            </span>
                            <span class="text-gray-500 text-sm">{{
                                new Date(news.date).toLocaleDateString(
                                    "en-US",
                                    {
                                        month: "short",
                                        day: "numeric",
                                        year: "numeric",
                                    }
                                )
                            }}</span>
                        </div>

                        <h3
                            class="text-lg sm:text-xl font-bold text-gray-900 mb-3 leading-tight"
                        >
                            {{ news.title }}
                        </h3>

                        <p
                            class="text-gray-600 leading-relaxed mb-4 text-sm sm:text-base"
                        >
                            {{ news.excerpt }}
                        </p>

                        <div
                            class="text-blue-600 font-semibold text-sm sm:text-base"
                        >
                            Read More →
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>
