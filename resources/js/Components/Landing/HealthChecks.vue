<script setup>
import Background from "@/assets/images/background/background1.png";

const healths = [
    {
        name: "Basic Health Check",
        price: "৳2,500",
        features: [
            "Blood Tests",
            "Urine Analysis",
            "Basic Consultation",
            "BMI Calculation",
        ],
        popular: false,
    },
    {
        name: "Executive Health Check",
        price: "৳5,000",
        features: [
            "Complete Blood Count",
            "Liver Function Test",
            "Cardiac Screening",
            "Diabetes Panel",
            "Doctor Consultation",
        ],
        popular: true,
    },
    {
        name: "Comprehensive Health Check",
        price: "৳8,000",
        features: [
            "All Executive Features",
            "Cancer Markers",
            "Advanced Cardiac Tests",
            "Full Body Checkup",
            "Specialist Consultation",
        ],
        popular: false,
    },
];
</script>

<template>
    <section
        id="health-s"
        class="py-20 bg-fixed bg-cover bg-center"
        :style="{ backgroundImage: `url(${Background})` }"
    >
        <div class="absolute inset-0 bg-white/85 pointer-events-none z-0"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">
                    Health Check
                    <span
                        class="text-transparent bg-clip-text from-blue-600 to-cyan-500"
                    >
                        s
                    </span>
                </h2>
                <p class="text-xl text-white max-w-3xl mx-auto leading-relaxed">
                    Comprehensive health screening s designed for preventive
                    care and early detection.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div
                    v-for="health in healths"
                    :key="health.name"
                    class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2"
                    :class="
                        health.popular
                            ? 'border-blue-500 scale-105'
                            : 'border-gray-200'
                    "
                >
                    <!-- Popular Badge -->
                    <div
                        v-if="health.popular"
                        class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2 rounded-full font-semibold text-sm"
                    >
                        Most Popular
                    </div>

                    <div class="p-8">
                        <h3
                            class="text-2xl font-bold text-gray-900 text-center mb-4"
                        >
                            {{ health.name }}
                        </h3>
                        <div
                            class="text-3xl font-black text-blue-600 text-center mb-6"
                        >
                            {{ health.price }}
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li
                                v-for="feature in health.features"
                                :key="feature"
                                class="flex items-center text-gray-600"
                            >
                                <svg
                                    class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>

                        <button
                            class="w-full py-3 rounded-xl font-semibold transition-all duration-300"
                            :class="
                                health.popular
                                    ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white hover:shadow-lg'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            "
                        >
                            Select
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
