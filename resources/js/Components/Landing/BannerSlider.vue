<script setup>
import { ref, onMounted } from "vue";

// উদাহরণ ইমেজ (আপনারগুলোই ব্যবহার করছি)
import Cardiac from "@/assets/images/slide/cardiacs.png";
import Emergency from "@/assets/images/slide/emergency.png";
import Package from "@/assets/images/slide/package.png";
import Background from "@/assets/images/background/background1.png";

// স্লাইড ডেটা
const slides = ref([
    {
        id: 1,
        title: "Xet Specialized Hospital",
        subtitle:
            "Xet Specialized Hospital was founded by a group of like-minded individuals and specialist doctors with a vision to create a “One Stop Standard Quality Health Service Centre”. We prioritize transparency and are deeply committed to our work with unwavering dedication.",
        cta: "Connect with us on Messenger",
        link: "#messenger",
        image: Cardiac,
        // চাইলে ডাক্তারদের cutout foreground হিসেবে দিন:
        // overlay: DoctorsPng
    },
    {
        id: 2,
        title: "24/7 Emergency Services",
        subtitle:
            "Immediate medical attention when you need it most. Our emergency care is always ready.",
        cta: "Emergency Contact",
        link: "#emergency",
        image: Emergency,
    },
    {
        id: 3,
        title: "Health Check Packages",
        subtitle:
            "Comprehensive health screenings for preventive care. Choose the package that suits you.",
        cta: "View Packages",
        link: "#health-packages",
        image: Package,
    },
]);

const current = ref(0);

// Auto slide
onMounted(() => {
    setInterval(() => {
        current.value = (current.value + 1) % slides.value.length;
    }, 5000);
});

const go = (i) => (current.value = i);
const next = () => (current.value = (current.value + 1) % slides.value.length);
const prev = () =>
    (current.value =
        (current.value - 1 + slides.value.length) % slides.value.length);
</script>

<template>
    <!-- Light blue section like screenshot -->
    <!-- Light blue section like screenshot -->
    <section
        class="relative bg-fixed bg-cover bg-center h-[100vh] sm:h-[420px] lg:h-[88vh] overflow-hidden"
        :style="{
            backgroundImage: `url(${Background})`,
        }"
        aria-hidden="true"
    >
        <div class="absolute inset-0 bg-black-50/40"></div>
        <!-- Content wrapper: pull above bg -->
        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20"
        >
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Left: text block -->
                <div class="relative z-10">
                    <h2
                        class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-sky-800 leading-tight"
                    >
                        {{ slides[current].title }}
                    </h2>
                    <p
                        class="mt-5 text-slate-600 text-base sm:text-lg leading-relaxed max-w-2xl"
                    >
                        {{ slides[current].subtitle }}
                    </p>

                    <!-- Messenger style CTA -->
                    <a
                        :href="slides[current].link"
                        class="mt-8 inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold bg-gradient-to-r from-indigo-500 via-fuchsia-500 to-rose-400 text-white shadow-lg shadow-indigo-200/40 hover:shadow-xl transition-all duration-300 hover:-translate-y-[1px]"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            class="w-6 h-6"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                d="M12 2C6.48 2 2 6.02 2 11.07c0 2.72 1.35 5.15 3.51 6.8V22l3.21-1.77c1.01.28 2.08.43 3.19.43 5.52 0 10-4.02 10-9.07S17.52 2 12 2Zm.71 11.93-2.43-2.58-4.06 2.58 4.63-4.94 2.38 2.54 4.13-2.54-4.65 4.94Z"
                            />
                        </svg>
                        {{ slides[current].cta }}
                    </a>

                    <!-- Dots -->
                    <div class="mt-6 flex items-center gap-2">
                        <button
                            v-for="(s, i) in slides"
                            :key="s.id"
                            @click="go(i)"
                            class="h-2.5 w-2.5 rounded-full transition-all"
                            :class="
                                i === current
                                    ? 'bg-sky-700 w-6'
                                    : 'bg-sky-300 hover:bg-sky-400'
                            "
                            aria-label="Go to slide"
                        />
                    </div>
                </div>

                <!-- Right: rounded image card with slider inside -->
                <div class="relative">
                    <div
                        class="relative h-[360px] sm:h-[420px] lg:h-[350px] rounded-3xl overflow-hidden shadow-2xl ring-1 ring-sky-100 bg-white/40"
                    >
                        <!-- Slides -->
                        <div
                            v-for="(s, i) in slides"
                            :key="s.id"
                            class="absolute inset-0 transition-opacity duration-700"
                            :class="i === current ? 'opacity-100' : 'opacity-0'"
                        >
                            <!-- IMPORTANT: fill the card -->
                            <img
                                :src="s.image"
                                :alt="s.title"
                                class="w-full h-full object-cover"
                                decoding="async"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-tr from-sky-900/0 via-sky-900/0 to-sky-900/0 pointer-events-none"
                            ></div>

                            <img
                                v-if="s.overlay"
                                :src="s.overlay"
                                alt=""
                                class="absolute bottom-0 right-2 max-h-[95%] object-contain"
                                decoding="async"
                            />
                        </div>

                        <!-- Prev/Next -->
                        <button
                            @click="prev"
                            class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/60 backdrop-blur flex items-center justify-center hover:bg-white/80 transition"
                            aria-label="Previous slide"
                        >
                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
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
                            @click="next"
                            class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/60 backdrop-blur flex items-center justify-center hover:bg-white/80 transition"
                            aria-label="Next slide"
                        >
                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
