<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const props = defineProps({
    banners: Array,
});

const currentBanner = ref(0);
// Auto slide banners
onMounted(() => {
    setInterval(() => {
        currentBanner.value = (currentBanner.value + 1) % banners.value.length;
    }, 5000);
});

const nextBanner = () => {
    currentBanner.value = (currentBanner.value + 1) % banners.value.length;
};

const prevBanner = () => {
    currentBanner.value =
        (currentBanner.value - 1 + banners.value.length) % banners.value.length;
};
const banners = ref([
    {
        id: 1,
        title: "Advanced Cardiac Care",
        subtitle: "World-class heart treatment with 98% success rate",
        image: "https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
        cta: "Book Consultation",
        link: "#cardiology",
    },
    {
        id: 2,
        title: "24/7 Emergency Services",
        subtitle: "Immediate medical attention when you need it most",
        image: "https://images.unsplash.com/photo-1584467735871-8db9ac8d55b8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
        cta: "Emergency Contact",
        link: "#emergency",
    },
    {
        id: 3,
        title: "Health Check Packages",
        subtitle: "Comprehensive health screenings for preventive care",
        image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80",
        cta: "View Packages",
        link: "#health-packages",
    },
]);
</script>

<template>
    <section id="home" class="relative h-[600px] overflow-hidden">
        <div
            v-for="(banner, index) in banners"
            :key="banner.id"
            class="absolute inset-0 transition-opacity duration-1000"
            :class="index === currentBanner ? 'opacity-100' : 'opacity-0'"
        >
            <div class="absolute inset-0 bg-black/40 z-10"></div>
            <img
                :src="banner.image"
                :alt="banner.title"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 z-20 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                    <div class="max-w-2xl">
                        <h2
                            class="text-5xl lg:text-6xl font-black mb-6 leading-tight"
                        >
                            {{ banner.title }}
                        </h2>
                        <p class="text-xl lg:text-2xl mb-8 text-gray-200">
                            {{ banner.subtitle }}
                        </p>
                        <a
                            :href="banner.link"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105"
                        >
                            {{ banner.cta }}
                            <svg
                                class="w-5 h-5 ml-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slider Controls -->
        <button
            @click="prevBanner"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 z-30 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-colors duration-200"
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
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </button>
        <button
            @click="nextBanner"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 z-30 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-colors duration-200"
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
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </button>

        <!-- Slider Indicators -->
        <div
            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex space-x-2"
        >
            <button
                v-for="(banner, index) in banners"
                :key="banner.id"
                @click="currentBanner = index"
                class="w-3 h-3 rounded-full transition-all duration-300"
                :class="
                    index === currentBanner
                        ? 'bg-white scale-125'
                        : 'bg-white/50'
                "
            ></button>
        </div>
    </section>
</template>
