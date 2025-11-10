<script setup>
import Background from "@/assets/images/background/background1.png";
const corporateServices = [
    {
        title: "Corporate Health Checkups",
        description: "Comprehensive health screening s for your employees",
        icon: "🏢",
    },
    {
        title: "On-site Medical Facilities",
        description: "Set up medical facilities at your workplace",
        icon: "⚕️",
    },
    {
        title: "Health Awareness Programs",
        description: "Educational sessions on workplace health and safety",
        icon: "📚",
    },
    {
        title: "Emergency Response Training",
        description: "First aid and emergency response training for staff",
        icon: "🆘",
    },
];
</script>

<template>
    <section
        class="py-10 md:py-14 bg-gradient-to-br from-blue-100 to-yellow-100"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-card">
                <h2 class="text-2xl md:text-4xl font-semibold text-gray-900">
                    Corporate Services
                </h2>
                <p
                    class="text-base md:text-lg text-white max-w-lg md:max-w-2xl mt-2"
                >
                    Comprehensive healthcare solutions for your organization and
                    employees.
                </p>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8"
            >
                <div
                    v-for="service in corporateServices"
                    :key="service.title"
                    class="text-center p-4 md:p-6 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 hover:bg-white/80 transition-all duration-500 transform hover:-translate-y-2"
                >
                    <div class="text-4xl md:text-5xl mb-4 md:mb-6">
                        {{ service.icon }}
                    </div>

                    <h3 class="text-base md:text-lg font-bold mb-4">
                        {{ service.title }}
                    </h3>

                    <p
                        class="text-blue-700 text-sm md:text-base leading-relaxed"
                    >
                        {{ service.description }}
                    </p>
                </div>
            </div>

            <div class="text-center mt-8 md:mt-12">
                <a
                    href="#contact"
                    class="inline-flex items-center px-6 md:px-8 py-3 md:py-4 bg-white text-blue-600 rounded-xl font-semibold text-base md:text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                    Contact Corporate Desk
                    <svg
                        class="w-4 h-4 md:w-5 md:h-5 ml-2"
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
    </section>
</template>

<style scoped>
.text-card {
    margin: 0 20px;
    margin-bottom: 30px;
}

@media (min-width: 768px) {
    .text-card {
        margin: 0 50px;
        margin-bottom: 50px;
    }
}

@media (min-width: 1024px) {
    .text-card {
        margin: 0 100px;
        margin-bottom: 50px;
    }
}
</style>
