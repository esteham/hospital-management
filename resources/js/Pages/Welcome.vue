<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

// Banner Slider Data
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

// Health Check Packages
const healthPackages = ref([
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
]);

// Patient Stories
const patientStories = ref([
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
]);

// News & Media
const newsItems = ref([
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
]);

// Shasthoshongi Blog
const blogPosts = ref([
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
]);

// Corporate Services
const corporateServices = ref([
    {
        title: "Corporate Health Checkups",
        description:
            "Comprehensive health screening packages for your employees",
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
]);
</script>

<template>
    <Head title="Welcome to Xet Specialized Hospital" />

    <!-- Modern Header -->
    <header
        class="bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Hospital Name -->
                <div class="flex items-center space-x-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-8 h-8 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent"
                        >
                            Xet Specialized Hospital
                        </h1>
                        <p class="text-sm text-gray-500 font-medium">
                            Excellence in Specialized Healthcare
                        </p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="hidden lg:flex space-x-10">
                    <a
                        href="#home"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Home
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#services"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Services
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#health-packages"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Health Packages
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#patient-stories"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Patient Stories
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                    <a
                        href="#blog"
                        class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200 relative group"
                    >
                        Blog
                        <span
                            class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-300"
                        ></span>
                    </a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    <div v-if="canLogin" class="flex space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-200"
                            >
                                Sign In
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-gradient-to-r from-green-600 to-emerald-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                            >
                                Get Started
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Banner Slider Section -->
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

    <!-- Why Choose Us Section -->
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
                    v-for="feature in [
                        {
                            title: 'Expert Specialists',
                            desc: '200+ renowned medical specialists across all departments',
                            icon: '👨‍⚕️',
                        },
                        {
                            title: 'Advanced Technology',
                            desc: 'State-of-the-art medical equipment and facilities',
                            icon: '🔬',
                        },
                        {
                            title: '24/7 Emergency',
                            desc: 'Round-the-clock emergency and critical care services',
                            icon: '🚨',
                        },
                        {
                            title: 'Patient First',
                            desc: 'Personalized care plans and compassionate treatment',
                            icon: '❤️',
                        },
                    ]"
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

    <!-- Health Check Packages Section -->
    <section
        id="health-packages"
        class="py-20 bg-gradient-to-br from-blue-50 to-cyan-50/30"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-4">
                    Health Check
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Packages
                    </span>
                </h2>
                <p
                    class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"
                >
                    Comprehensive health screening packages designed for
                    preventive care and early detection.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div
                    v-for=" in healthPackages"
                    :key="name"
                    class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2"
                    :class="
                        popular
                            ? 'border-blue-500 scale-105'
                            : 'border-gray-200'
                    "
                >
                    <!-- Popular Badge -->
                    <div
                        v-if="popular"
                        class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-2 rounded-full font-semibold text-sm"
                    >
                        Most Popular
                    </div>

                    <div class="p-8">
                        <h3
                            class="text-2xl font-bold text-gray-900 text-center mb-4"
                        >
                            {{ name }}
                        </h3>
                        <div
                            class="text-3xl font-black text-blue-600 text-center mb-6"
                        >
                            {{ price }}
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li
                                v-for="feature in features"
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
                                popular
                                    ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white hover:shadow-lg'
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            "
                        >
                            Select Package
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Patient Stories Section -->
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

    <!-- News & Media Section -->
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

    <!-- Shasthoshongi Blog Section -->
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

    <!-- Corporate Services Section -->
    <section
        class="py-20 bg-gradient-to-br from-blue-600 to-cyan-500 text-white"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black mb-4">
                    Corporate Services
                </h2>
                <p
                    class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed"
                >
                    Comprehensive healthcare solutions for your organization and
                    employees.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    v-for="service in corporateServices"
                    :key="service.title"
                    class="text-center p-8 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-500 transform hover:-translate-y-2"
                >
                    <div class="text-5xl mb-6">{{ service.icon }}</div>

                    <h3 class="text-xl font-bold mb-4">{{ service.title }}</h3>

                    <p class="text-blue-100 leading-relaxed">
                        {{ service.description }}
                    </p>
                </div>
            </div>

            <div class="text-center mt-12">
                <a
                    href="#contact"
                    class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold text-lg hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                    Contact Corporate Desk
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
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <div>
                    <h2
                        class="text-4xl lg:text-5xl font-black text-gray-900 mb-6"
                    >
                        Get In Touch
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        Have questions or need to schedule an appointment? Our
                        team is here to help you with all your healthcare needs.
                    </p>

                    <div class="space-y-6">
                        <div
                            class="flex items-center gap-4 p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600"
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
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    Emergency
                                </h4>
                                <p class="text-gray-600">(555) 123-HELP</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600"
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
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    Location
                                </h4>
                                <p class="text-gray-600">
                                    123 Medical Avenue, Healthcare District
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-4 p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600"
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    Working Hours
                                </h4>
                                <p class="text-gray-600">
                                    Emergency: 24/7 | OPD: 8AM-8PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50/30 p-8 rounded-2xl border border-blue-200"
                >
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                        Send us a Message
                    </h3>
                    <form class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >First Name</label
                                >
                                <input
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Last Name</label
                                >
                                <input
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Email</label
                            >
                            <input
                                type="email"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Message</label
                            >
                            <textarea
                                rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-4 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105"
                        >
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold">
                                Xet Specialized Hospital
                            </h4>
                            <p class="text-gray-400 font-medium">
                                Excellence in Specialized Healthcare
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-md">
                        Delivering world-class specialized healthcare with
                        compassion, innovation, and unwavering commitment to
                        patient well-being.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <a
                            href="#"
                            class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-blue-600 transition-colors duration-200"
                        >
                            <span class="sr-only">Facebook</span>
                            <!-- Facebook Icon -->
                        </a>
                        <a
                            href="#"
                            class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-blue-400 transition-colors duration-200"
                        >
                            <span class="sr-only">Twitter</span>
                            <!-- Twitter Icon -->
                        </a>
                        <a
                            href="#"
                            class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors duration-200"
                        >
                            <span class="sr-only">LinkedIn</span>
                            <!-- LinkedIn Icon -->
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h5 class="text-lg font-bold mb-6">Quick Links</h5>
                    <ul class="space-y-3">
                        <li>
                            <a
                                href="#home"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Home</a
                            >
                        </li>
                        <li>
                            <a
                                href="#services"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Services</a
                            >
                        </li>
                        <li>
                            <a
                                href="#health-packages"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Health Packages</a
                            >
                        </li>
                        <li>
                            <a
                                href="#patient-stories"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Patient Stories</a
                            >
                        </li>
                        <li>
                            <a
                                href="#blog"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Blog</a
                            >
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h5 class="text-lg font-bold mb-6">Our Services</h5>
                    <ul class="space-y-3">
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Emergency Care</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Cardiology</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Neurology</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Surgery</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white transition-colors duration-200"
                                >Diagnostics</a
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400">
                    &copy; 2024 Xet Specialized Hospital. All rights reserved. |
                    <a
                        href="#"
                        class="hover:text-white transition-colors duration-200"
                        >Privacy Policy</a
                    >
                    •
                    <a
                        href="#"
                        class="hover:text-white transition-colors duration-200"
                        >Terms of Service</a
                    >
                </p>
            </div>
        </div>
    </footer>
</template>

<style>
/* Custom styles for smooth scrolling and animations */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
}

/* Smooth transitions for banner slider */
.banner-slide-enter-active,
.banner-slide-leave-active {
    transition: opacity 1s ease-in-out;
}

.banner-slide-enter-from,
.banner-slide-leave-to {
    opacity: 0;
}
</style>
