<script setup>
const newsItems = [
    {
        title: "Xet Hospital Launches Advanced Cancer Center",
        date: "Dec 15, 2024",
        excerpt:
            "New state-of-the-art cancer treatment facility now open with latest technology.",
        image: "https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
        category: "Facility Update",
    },
    {
        title: "Breakthrough in Minimally Invasive Surgery",
        date: "Dec 10, 2024",
        excerpt:
            "Our surgeons successfully perform complex procedures with faster recovery times.",
        image: "https://images.unsplash.com/photo-1584467735871-8db9ac8d55b8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
        category: "Medical Innovation",
    },
    {
        title: "Free Health Camp for Underprivileged Communities",
        date: "Dec 5, 2024",
        excerpt:
            "Xet Hospital organizes health screening camp serving 500+ patients.",
        image: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
        category: "Community Service",
    },
];
</script>

<template>
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
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

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    v-for="news in newsItems"
                    :key="news.title"
                    class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden"
                >
                    <img
                        :src="news.image"
                        :alt="news.title"
                        class="w-full h-48 object-cover"
                    />

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold"
                            >
                                {{ news.category }}
                            </span>
                            <span class="text-gray-500 text-sm">{{
                                news.date
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
                            href="#"
                            class="text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200"
                        >
                            Read More →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
