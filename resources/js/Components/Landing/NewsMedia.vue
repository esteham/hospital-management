<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";

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
        if (img.startsWith("/")) return img; // already a full path like /storage/...
    }
    return "/storage/" + img;
};

onMounted(() => {
    fetchNews();
});
</script>

<template>
    <section class="py-14 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    News & Media
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Stay updated with the latest news, innovations, and
                    community initiatives from Xet Hospital.
                </p>
            </div>

            <div class="relative">
                <Swiper
                    :slides-per-view="1"
                    :space-between="30"
                    :breakpoints="{
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                    }"
                    :autoplay="{ delay: 3000, disableOnInteraction: false }"
                    :loop="true"
                    :pagination="{ clickable: true }"
                    :navigation="true"
                    class="news-swiper"
                >
                    <SwiperSlide
                        v-for="news in newsItems"
                        :key="news.id || news.title"
                    >
                        <div
                            class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden"
                        >
                            <img
                                :src="imageUrl(news.image)"
                                :alt="news.title"
                                class="w-full h-44 object-cover"
                            />

                            <div class="p-6">
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold"
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
                                    class="text-xl font-bold text-gray-900 mb-3 leading-tight"
                                >
                                    {{ news.title }}
                                </h3>

                                <p class="text-gray-600 leading-relaxed mb-4">
                                    {{ news.excerpt }}
                                </p>

                                <a
                                    :href="`/news/${news.id}`"
                                    target="_blank"
                                    class="text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200"
                                >
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>

                <div v-if="newsItems.length > 3" class="text-right">
                    <a
                        href="/news-all"
                        class="inline-block px-5 py-2 bg-blue-400 text-black font-semibold rounded-lg hover:bg-blue-600 transition-colors duration-200 mt-4"
                    >
                        Show All
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.news-swiper .swiper-pagination-bullet-active {
    background-color: #2563eb;
}

.news-swiper .swiper-button-next,
.news-swiper .swiper-button-prev {
    color: #2563eb;
}
</style>
