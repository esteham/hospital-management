<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

import Header from "@/Components/Landing/Header.vue";
import Banner from "@/Components/Landing/BannerSlider.vue";
import WhyChoose from "@/Components/Landing/WhyChooseUs.vue";
import HealthCheck from "@/Components/Landing/HealthChecks.vue";
import PatientStories from "@/Components/Landing/PatientStories.vue";
import NewsMedia from "@/Components/Landing/NewsMedia.vue";
import Specialists from "@/Components/Landing/Specialists.vue";
import CorporateServices from "@/Components/Landing/CorporateServices.vue";
import Contact from "@/Components/Landing/Contact.vue";
import Footer from "@/Components/Landing/Footer.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome to Xet Specialized Hospital" />

    <!-- Modern Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Banner Slider Section -->
    <Banner />

    <!-- Why Choose Us Section -->
    <WhyChoose />

    <!-- Health Check Packages Section -->
    <HealthCheck />

    <!-- Shasthoshongi Blog Section -->
    <Specialists />

    <!-- News & Media Section -->
    <NewsMedia />

    <!-- Patient Stories Section -->
    <PatientStories />

    <!-- Corporate Services Section -->
    <CorporateServices />

    <!-- Contact Section -->
    <Contact />

    <!-- Modern Footer -->
    <Footer />
</template>
