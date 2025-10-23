<template>
    <section id="patient-stories" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Our Incredible Patients
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Share Their Stories
                    </span>
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Real stories from patients who have experienced exceptional
                    care at Xet Hospital.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    v-for="story in patientStories"
                    :key="story.name"
                    class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-200 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2"
                >
                    <!-- Star Rating -->
                    <div class="flex mb-4">
                        <span
                            v-for="star in 5"
                            :key="star"
                            class="text-yellow-400 text-lg"
                        >
                            {{ star <= story.rating ? "★" : "☆" }}
                        </span>
                    </div>

                    <p
                        class="text-gray-700 italic text-lg leading-relaxed mb-6"
                    >
                        "{{ story.story }}"
                    </p>

                    <div class="flex items-center">
                        <img
                            :src="story.image"
                            :alt="story.name"
                            class="w-12 h-12 rounded-full mr-4"
                        />
                        <div>
                            <h4 class="font-bold text-gray-900">
                                {{ story.name }}
                            </h4>
                            <p class="text-gray-600 text-sm">
                                {{ story.condition }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const patientStories = [
    {
        name: "Rahman Ahmed",
        condition: "Cardiac Surgery",
        story: "The care I received at Xet Hospital was exceptional. The cardiac team saved my life with their expertise and compassion.",
        image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80",
        rating: 5,
    },
    {
        name: "Fatima Begum",
        condition: "Orthopedic Treatment",
        story: "After my knee replacement surgery, I can walk without pain for the first time in years. Thank you Xet Hospital!",
        image: "https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80",
        rating: 5,
    },
    {
        name: "Jamal Uddin",
        condition: "Neurology",
        story: "The neurology department provided excellent care for my condition. The doctors were knowledgeable and caring.",
        image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80",
        rating: 4,
    },
];
</script>
