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

<template>
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-card">
                <h2 class="text-3xl font-semibold text-gray-900">
                    Why Choose
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Xet Hospital?
                    </span>
                </h2>
                <p class="text-l text-gray-600 max-w-2xl mt-2">
                    Experience healthcare excellence with our patient-centered
                    approach and cutting-edge medical technology.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="text-center p-4 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-200 hover:border-blue-300 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2"
                >
                    <div class="text-4xl mb-4">{{ feature.icon }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        {{ feature.title }}
                    </h3>
                    <p class="text-gray-600 text-l leading-relaxed">
                        {{ feature.desc }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-card {
    margin: 0 100px;
    margin-bottom: 10px;
}
</style>
