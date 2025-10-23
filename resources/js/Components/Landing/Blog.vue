<script setup>
const blogPosts = [
    {
        title: "Understanding Heart Disease: Prevention Tips",
        author: "Dr. Mohammad Ali",
        date: "Dec 12, 2024",
        readTime: "5 min read",
        excerpt:
            "Learn about common heart conditions and how to maintain cardiovascular health through lifestyle changes.",
        image: "https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
    },
    {
        title: "Diabetes Management: A Complete Guide",
        author: "Dr. Sabrina Chowdhury",
        date: "Dec 8, 2024",
        readTime: "7 min read",
        excerpt:
            "Comprehensive guide to managing diabetes through diet, exercise, and medication.",
        image: "https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
    },
    {
        title: "Mental Health Awareness in Bangladesh",
        author: "Dr. Anika Rahman",
        date: "Dec 3, 2024",
        readTime: "6 min read",
        excerpt:
            "Breaking the stigma around mental health and available treatment options.",
        image: "https://images.unsplash.com/photo-1590650516494-0c8e4a4dd61b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80",
    },
];
</script>

<template>
    <section id="blog" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Shasthoshongi
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Blog
                    </span>
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Health education and awareness articles from our expert
                    medical professionals.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    v-for="post in blogPosts"
                    :key="post.title"
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-200"
                >
                    <img
                        :src="post.image"
                        :alt="post.title"
                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
                    />

                    <div class="p-6">
                        <div
                            class="flex items-center justify-between text-sm text-gray-500 mb-3"
                        >
                            <span>By {{ post.author }}</span>
                            <span>{{ post.readTime }}</span>
                        </div>

                        <h3
                            class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-blue-600 transition-colors duration-200"
                        >
                            {{ post.title }}
                        </h3>

                        <p class="text-gray-600 leading-relaxed mb-4">
                            {{ post.excerpt }}
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-sm">{{
                                post.date
                            }}</span>
                            <a
                                href="#"
                                class="text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200"
                            >
                                Read Article →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
