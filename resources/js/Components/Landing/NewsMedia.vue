<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const newsItems = ref([]);

const fetchNews = async () => {
    try {
        const response = await axios.get("/api/news");
        newsItems.value = response.data;
    } catch (error) {
        console.error("Error fetching news:", error);
    }
};

const imageUrl = (img) => {
    if (!img) return null;
    if (typeof img === "string") {
        if (img.startsWith("http://") || img.startsWith("https://")) return img;
        if (img.startsWith("/")) return img;
    }
    return "/storage/" + img;
};

onMounted(() => {
    fetchNews();
});
</script>

<template>
    <section id="news" class="py-10 md:py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="text-card flex flex-col md:flex-row md:justify-between md:items-center"
            >
                <div class="mb-4 md:mb-0">
                    <h2
                        class="text-2xl md:text-4xl font-semibold text-gray-900"
                    >
                        News & Media
                    </h2>
                    <p
                        class="text-base md:text-lg text-gray-600 max-w-lg md:max-w-2xl mt-2"
                    >
                        Stay updated with the latest news, innovations, and
                        community initiatives from Xet Hospital.
                    </p>
                </div>
                <a
                    href="/news-all"
                    class="bg-blue-600 text-white px-6 md:px-8 py-2 md:py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300 text-center"
                >
                    All News
                </a>
            </div>

            <div v-if="newsItems.length === 0" class="text-center">
                <p class="text-gray-500">Loading news...</p>
            </div>

            <div v-else class="marquee-container space-y-6">
                <!-- Top Row -->
                <div class="marquee-track">
                    <div class="marquee-segment flex gap-4">
                        <a
                            v-for="news in newsItems.slice(
                                0,
                                Math.ceil(newsItems.length)
                            )"
                            :key="'top-' + (news.id || news.title)"
                            :href="`/news/${news.id}`"
                            target="_blank"
                            class="news-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden"
                        >
                            <img
                                :src="imageUrl(news.image)"
                                :alt="news.title"
                                class="w-full h-32 md:h-44 object-cover"
                            />

                            <div class="p-4 md:p-6">
                                <div
                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 gap-2"
                                >
                                    <span
                                        class="px-2 md:px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs md:text-sm font-semibold self-start"
                                    >
                                        {{ news.category }}
                                    </span>
                                    <span
                                        class="text-gray-500 text-xs md:text-sm"
                                        >{{
                                            new Date(
                                                news.date
                                            ).toLocaleDateString("en-US", {
                                                month: "short",
                                                day: "numeric",
                                                year: "numeric",
                                            })
                                        }}</span
                                    >
                                </div>

                                <h3
                                    class="text-lg md:text-xl font-bold text-gray-900 mb-3 leading-tight"
                                >
                                    {{ news.title }}
                                </h3>

                                <p
                                    class="text-gray-600 text-sm md:text-base leading-relaxed mb-4"
                                >
                                    {{ news.excerpt }}
                                </p>

                                <span
                                    class="text-blue-600 font-semibold text-sm md:text-base"
                                >
                                    Read More →
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-card {
    margin: 0 20px;
    margin-bottom: 20px;
}

@media (min-width: 768px) {
    .text-card {
        margin: 0 50px;
        margin-bottom: 30px;
    }
}

@media (min-width: 1024px) {
    .text-card {
        margin: 0 130px;
        margin-bottom: 30px;
    }
}

.news-card {
    width: 280px;
    height: 380px;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
}

@media (max-width: 768px) {
    .news-card {
        width: 100%;
        max-width: 320px;
        height: auto;
        min-height: 380px;
    }
}

.news-card:hover {
    transform: translateY(-4px);
}

/* --- Continuous Scroll --- */
.marquee-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.marquee-track {
    display: flex;
    width: max-content;
    animation: scroll-right 60s linear infinite;
}

.marquee-track.reverse {
    animation: scroll-left 60s linear infinite;
}

.marquee-segment {
    display: flex;
    gap: 12px;
}

@media (max-width: 768px) {
    .marquee-segment {
        gap: 8px;
    }
}

/* adjust speed by changing duration (lower = faster) */
@keyframes scroll-right {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@keyframes scroll-left {
    0% {
        transform: translateX(-50%);
    }
    1000% {
        transform: translateX(0);
    }
}
</style>
