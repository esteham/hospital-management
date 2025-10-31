<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const form = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    preferredDate: "",
    preferredTime: "",
    speciality: "",
    additionalNotes: "",
});

const submitting = ref(false);

// Computed property for minimum date (today)
const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split("T")[0];
});

const submitForm = async () => {
    submitting.value = true;
    try {
        const formData = new FormData();
        formData.append("first_name", form.value.firstName);
        formData.append("last_name", form.value.lastName);
        formData.append("email", form.value.email);
        formData.append("phone", form.value.phone);
        formData.append("preferred_date", form.value.preferredDate);
        formData.append("preferred_time", form.value.preferredTime);
        formData.append("speciality", form.value.speciality);
        formData.append("additional_notes", form.value.additionalNotes || "");
        formData.append(
            "_token",
            document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
        );

        const response = await fetch("/appointments", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (response.ok) {
            alert("Appointment booked successfully!");
            // Reset form
            form.value = {
                firstName: "",
                lastName: "",
                email: "",
                phone: "",
                preferredDate: "",
                preferredTime: "",
                speciality: "",
                additionalNotes: "",
            };
        } else {
            alert(
                "Error booking appointment: " +
                    (result.message || "Unknown error")
            );
        }
    } catch (error) {
        console.error("Error:", error);
        alert("An error occurred while booking the appointment.");
    } finally {
        submitting.value = false;
    }
};

// Available time slots
const timeSlots = ref([
    "08:00 AM",
    "08:30 AM",
    "09:00 AM",
    "09:30 AM",
    "10:00 AM",
    "10:30 AM",
    "11:00 AM",
    "11:30 AM",
    "02:00 PM",
    "02:30 PM",
    "03:00 PM",
    "03:30 PM",
    "04:00 PM",
    "04:30 PM",
    "05:00 PM",
]);

// Medical specialties
const specialties = ref([
    { value: "cardiology", label: "Cardiology", icon: "❤️" },
    { value: "dermatology", label: "Dermatology", icon: "🔬" },
    { value: "neurology", label: "Neurology", icon: "🧠" },
    { value: "orthopedics", label: "Orthopedics", icon: "🦴" },
    { value: "general", label: "General Medicine", icon: "👨‍⚕️" },
    { value: "pediatrics", label: "Pediatrics", icon: "👶" },
    { value: "gynecology", label: "Gynecology", icon: "🌸" },
    { value: "ophthalmology", label: "Ophthalmology", icon: "👁️" },
    { value: "ent", label: "ENT Specialist", icon: "👂" },
    { value: "gastroenterology", label: "Gastroenterology", icon: "🩺" },
]);

// Benefits information
const benefits = ref([
    {
        icon: "⏰",
        title: "Quick Confirmation",
        description: "Get appointment confirmation within 2 hours",
    },
    {
        icon: "👨‍⚕️",
        title: "Expert Doctors",
        description: "Consult with specialized medical professionals",
    },
    {
        icon: "💻",
        title: "Online Consultation",
        description: "Option for virtual appointments available",
    },
    {
        icon: "📱",
        title: "Reminder Alerts",
        description: "Get SMS & email reminders before your appointment",
    },
]);

// Hospital stats
const hospitalStats = ref([
    { number: "50,000+", label: "Patients Treated" },
    { number: "200+", label: "Expert Doctors" },
    { number: "98%", label: "Success Rate" },
    { number: "24/7", label: "Emergency Care" },
]);
</script>

<template>
    <Head title="Book Your Appointment - Xet Specialized Hospital" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50/30"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full text-blue-700 text-sm font-semibold mb-4"
                >
                    <div
                        class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"
                    ></div>
                    Book Your Appointment
                </div>
                <h1
                    class="text-4xl lg:text-4xl font-black text-gray-900 mb-4 leading-tight"
                >
                    Schedule Your
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Medical Visit
                    </span>
                </h1>
                <p
                    class="text-l text-gray-700 leading-relaxed max-w-3xl mx-auto"
                >
                    Take the first step towards better health. Book your
                    appointment with our expert medical team and experience
                    world-class healthcare.
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Left Side - Information (4 columns) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Benefits Card -->
                    <div
                        class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-6">
                            Why Choose Xet Hospital?
                        </h3>
                        <div class="space-y-6">
                            <div
                                v-for="benefit in benefits"
                                :key="benefit.title"
                                class="flex items-start gap-4 p-4 rounded-xl bg-blue-50/50 hover:bg-blue-100/50 transition-colors duration-300"
                            >
                                <div class="text-2xl flex-shrink-0">
                                    {{ benefit.icon }}
                                </div>
                                <div>
                                    <h4
                                        class="font-semibold text-gray-900 mb-1"
                                    >
                                        {{ benefit.title }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ benefit.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <!-- <div
                        class="bg-gradient-to-br from-blue-600 to-cyan-500 rounded-3xl p-8 text-white"
                    >
                        <h3 class="text-2xl font-black mb-6">Our Excellence</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                v-for="stat in hospitalStats"
                                :key="stat.label"
                                class="text-center p-4 bg-white/10 rounded-xl backdrop-blur-sm"
                            >
                                <div class="text-2xl font-black mb-1">
                                    {{ stat.number }}
                                </div>
                                <div class="text-sm text-blue-100">
                                    {{ stat.label }}
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Emergency Contact -->
                    <div
                        class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-4">
                            Emergency?
                        </h3>
                        <p class="text-gray-600 mb-6">
                            For urgent medical attention, contact our emergency
                            services immediately.
                        </p>
                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-4 p-4 bg-red-50 rounded-xl border border-red-200"
                            >
                                <div
                                    class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600"
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
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Emergency Line
                                    </h4>
                                    <p class="text-red-600 font-bold">999</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl border border-blue-200"
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
                                    <h4 class="font-semibold text-gray-900">
                                        24/7 Support
                                    </h4>
                                    <p class="text-blue-600">
                                        Always available for emergencies
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Contact -->
                    <div
                        class="bg-gradient-to-br from-gray-900 to-blue-900 rounded-3xl p-8 text-white"
                    >
                        <h3 class="text-2xl font-black mb-6">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">
                                        Location
                                    </p>
                                    <p class="font-semibold">
                                        123 Medical Avenue
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
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
                                    <p class="text-sm text-blue-200">Phone</p>
                                    <p class="font-semibold">999</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">Email</p>
                                    <p class="font-semibold">
                                        appointments@xethospital.com
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Appointment Form (8 columns) -->
                <div class="lg:col-span-8">
                    <div
                        class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 lg:p-12"
                    >
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-black text-gray-900 mb-3">
                                Book Your Appointment
                            </h2>
                            <p class="text-gray-600">
                                Fill in your details and we'll get back to you
                                within 2 hours
                            </p>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-8">
                            <!-- Personal Information -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3"
                                >
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"
                                    >
                                        👤
                                    </div>
                                    Personal Information
                                </h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            First Name *
                                        </label>
                                        <input
                                            v-model="form.firstName"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400"
                                            placeholder="Enter your first name"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            Last Name *
                                        </label>
                                        <input
                                            v-model="form.lastName"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400"
                                            placeholder="Enter your last name"
                                        />
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            Email Address *
                                        </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400"
                                            placeholder="your@email.com"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            Phone Number *
                                        </label>
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400"
                                            placeholder="+88 (013) 1235-4567"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3"
                                >
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"
                                    >
                                        📅
                                    </div>
                                    Appointment Details
                                </h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            Preferred Date *
                                        </label>
                                        <input
                                            v-model="form.preferredDate"
                                            type="date"
                                            :min="minDate"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-3"
                                        >
                                            Preferred Time *
                                        </label>
                                        <select
                                            v-model="form.preferredTime"
                                            required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white"
                                        >
                                            <option value="">
                                                Select Time Slot
                                            </option>
                                            <option
                                                v-for="time in timeSlots"
                                                :key="time"
                                                :value="time"
                                            >
                                                {{ time }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-3"
                                    >
                                        Medical Speciality *
                                    </label>
                                    <select
                                        v-model="form.speciality"
                                        required
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white"
                                    >
                                        <option value="">
                                            Select Speciality
                                        </option>
                                        <option
                                            v-for="specialty in specialties"
                                            :key="specialty.value"
                                            :value="specialty.value"
                                        >
                                            {{ specialty.icon }}
                                            {{ specialty.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mt-6">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-3"
                                    >
                                        Additional Notes
                                    </label>
                                    <textarea
                                        v-model="form.additionalNotes"
                                        rows="4"
                                        placeholder="Please describe your symptoms, reason for visit, medical history, or any special requirements..."
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 resize-none"
                                    ></textarea>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Providing detailed information helps us
                                        serve you better
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-8 border-t border-gray-200">
                                <button
                                    type="submit"
                                    :disabled="submitting"
                                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition-all duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{
                                        submitting
                                            ? "📋 Booking..."
                                            : "📋 Book Appointment Now"
                                    }}
                                </button>
                                <p
                                    class="text-center text-sm text-gray-600 mt-4"
                                >
                                    By booking an appointment, you agree to our
                                    <a
                                        href="#"
                                        class="text-blue-600 hover:underline"
                                        >terms and conditions</a
                                    >
                                    and
                                    <a
                                        href="#"
                                        class="text-blue-600 hover:underline"
                                        >privacy policy</a
                                    >.
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 grid md:grid-cols-3 gap-6 text-center">
                        <div
                            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm"
                        >
                            <div
                                class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                Secure & Confidential
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Your information is protected
                            </p>
                        </div>
                        <div
                            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm"
                        >
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600"
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
                            <h4 class="font-semibold text-gray-900">
                                Quick Response
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Confirmation within 2 hours
                            </p>
                        </div>
                        <div
                            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm"
                        >
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900">
                                Expert Doctors
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Specialized medical care
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <Footer />
</template>
