<template>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Why Choose
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Xet Hospital?
                    </span>
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Experience healthcare excellence with our patient-centered
                    approach and cutting-edge medical technology.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-200 hover:border-blue-300 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2"
                >
                    <div class="text-5xl mb-6">{{ feature.icon }}</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ feature.title }}
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ feature.desc }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const features = [
    {
        title: "Expert Specialists",
        desc: "200+ renowned medical specialists across all departments",
        icon: "👨‍⚕️",
    },
    {
        title: "Advanced Technology",
        desc: "State-of-the-art medical equipment and facilities",
        icon: "🔬",
    },
    {
        title: "24/7 Emergency",
        desc: "Round-the-clock emergency and critical care services",
        icon: "🚨",
    },
    {
        title: "Patient First",
        desc: "Personalized care plans and compassionate treatment",
        icon: "❤️",
    },
];
</script>
