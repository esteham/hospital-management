<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, computed, watch } from "vue";
import Header from "@/Components/Landing/Header.vue";
import Footer from "@/Components/Landing/Footer.vue";

const { canLogin, canRegister, laravelVersion, phpVersion, service, doctors } =
    defineProps({
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        service: Object,
        doctors: Array,
    });

const form = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    gender: "",
    age: "",
    bookingDate: "",
    bookingTime: "",
    address: "",
    doctorId: "",
    paymentMethod: "cash",
    additionalNotes: "",
});

const submitting = ref(false);
const showSuccessModal = ref(false);
const successBooking = ref(null);

// Computed property for minimum date (today)
const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split("T")[0];
});

const submitForm = async () => {
    submitting.value = true;
    try {
        const response = await fetch("/diagnostic/bookings", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                first_name: form.value.firstName,
                last_name: form.value.lastName,
                email: form.value.email,
                phone: form.value.phone,
                gender: form.value.gender,
                age: form.value.age,
                booking_date: form.value.bookingDate,
                booking_time: form.value.bookingTime,
                address: form.value.address,
                diagnostic_service_id: service.id,
                doctor_id: form.value.doctorId || null,
                payment_method: form.value.paymentMethod,
                additional_notes: form.value.additionalNotes || "",
            }),
        });

        const result = await response.json();

        if (response.ok) {
            successBooking.value = result.booking;
            showSuccessModal.value = true;
        } else {
            if (result.errors) {
                const errorMessages = Object.values(result.errors)
                    .flat()
                    .join(", ");
                alert("Please fix the following errors: " + errorMessages);
            } else {
                alert(
                    "Error booking diagnostic test: " +
                        (result.message || "Unknown error")
                );
            }
        }
    } catch (error) {
        console.error("Error:", error);
        alert(
            "An error occurred while booking the diagnostic test: " +
                error.message
        );
    } finally {
        submitting.value = false;
    }
};

// Available time slots
const timeSlots = ref([
    "9:00 AM",
    "9:30 AM",
    "10:00 AM",
    "10:30 AM",
    "11:00 AM",
    "11:30 AM",
    "12:00 PM",
    "12:30 PM",
    "1:00 PM",
    "1:30 PM",
    "2:00 PM",
    "2:30 PM",
    "3:00 PM",
    "3:30 PM",
    "4:00 PM",
    "4:30 PM",
    "5:00 PM",
    "5:30 PM",
    "6:00 PM",
    "6:30 PM",
    "7:00 PM",
    "7:30 PM",
    "8:00 PM",
]);

// Computed property for available dates (next 7 days)
const availableDates = computed(() => {
    const dates = [];
    const today = new Date();
    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        const value = date.toISOString().split("T")[0];
        const label = date.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "short",
            day: "numeric",
        });
        dates.push({ value, label });
    }
    return dates;
});

// Watch for doctor changes to reset date and time selection
watch(
    () => form.value.doctorId,
    (newDoctorId) => {
        if (newDoctorId !== form.value.doctorId) {
            form.value.bookingDate = "";
            form.value.bookingTime = "";
        }
    }
);

const downloadBookingDetails = async (bookingId) => {
    try {
        const response = await fetch(
            `/diagnostic/bookings/${bookingId}/download-pdf`
        );
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = `diagnostic_booking_${bookingId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
        } else {
            const result = await response.json();
            alert(
                "Error downloading PDF: " + (result.message || "Unknown error")
            );
        }
    } catch (error) {
        console.error("Download error:", error);
        alert("An error occurred while downloading the PDF.");
    }
};

const closeModal = () => {
    showSuccessModal.value = false;
    successBooking.value = null;
    // Reset form
    form.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        gender: "",
        age: "",
        bookingDate: "",
        bookingTime: "",
        address: "",
        doctorId: "",
        paymentMethod: "cash",
        additionalNotes: "",
    };
};

// Benefits information
const benefits = ref([
    {
        icon: "🧪",
        title: "Accurate Testing",
        description:
            "State-of-the-art diagnostic equipment for precise results",
    },
    {
        icon: "⏰",
        title: "Quick Results",
        description: "Fast turnaround time for test reports",
    },
    {
        icon: "🏠",
        title: "Home Collection",
        description: "Convenient sample collection at your doorstep",
    },
    {
        icon: "👨‍⚕️",
        title: "Expert Analysis",
        description: "Results analyzed by certified medical professionals",
    },
]);

// Hospital stats
const hospitalStats = ref([
    { number: "50,000+", label: "Tests Conducted" },
    { number: "98%", label: "Accuracy Rate" },
    { number: "24/7", label: "Lab Services" },
    { number: "15min", label: "Avg. Wait Time" },
]);
</script>

<template>
    <Head title="Book Diagnostic Test - Xet Specialized Hospital" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50/30"
    >
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full text-blue-700 text-sm font-semibold mb-4"
                >
                    <div
                        class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"
                    ></div>
                    Book Diagnostic Test
                </div>
                <h1
                    class="text-3xl md:text-4xl font-black text-gray-900 mb-4 leading-tight"
                >
                    Schedule Your
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Diagnostic Test
                    </span>
                </h1>
                <p
                    class="text-sm md:text-base text-gray-700 leading-relaxed max-w-3xl mx-auto"
                >
                    Book your {{ service?.name }} diagnostic test with our
                    expert medical team and get accurate results quickly.
                </p>
            </div>

            <!-- Main Content Grid -->
            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Left Side - Information (4 columns) -->
                <div class="hidden lg:block lg:col-span-3 space-y-8">
                    <!-- Service Info Card -->
                    <div
                        class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-6">
                            Test Details
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        {{ service?.name }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ service?.category || "General" }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600"
                                >
                                    ৳
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Price
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        ৳{{ service?.price }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600"
                                >
                                    ⏱️
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">
                                        Duration
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{
                                            service?.duration
                                                ? `${service.duration} min`
                                                : "N/A"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits Card -->
                    <div
                        class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8"
                    >
                        <h3 class="text-2xl font-black text-gray-900 mb-6">
                            Why Choose Us?
                        </h3>
                        <div class="space-y-6">
                            <div
                                v-for="benefit in benefits"
                                :key="benefit.title"
                                class="flex items-start gap-4 p-4 rounded-lg bg-blue-50/50 hover:bg-blue-100/50 transition-colors duration-300"
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
                    <div
                        class="bg-gradient-to-br from-blue-600 to-cyan-500 rounded-3xl p-8 text-white"
                    >
                        <h3 class="text-2xl font-black mb-6">Our Excellence</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                v-for="stat in hospitalStats"
                                :key="stat.label"
                                class="text-center p-4 bg-white/10 rounded-lg backdrop-blur-sm"
                            >
                                <div class="text-2xl font-black mb-1">
                                    {{ stat.number }}
                                </div>
                                <div class="text-sm text-blue-100">
                                    {{ stat.label }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Booking Form (8 columns) -->
                <div class="lg:col-span-9">
                    <div
                        class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 lg:p-12"
                    >
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-black text-gray-900 mb-1">
                                Book Your Test
                            </h2>
                            <p class="text-gray-600">
                                Fill in your details and we'll schedule your
                                diagnostic test
                            </p>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-8">
                            <!-- Test Details -->
                            <div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3"
                                >
                                    <div
                                        class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"
                                    >
                                        🧪
                                    </div>
                                    Test Information
                                </h3>

                                <div class="grid md:grid-cols-2 gap-6 mt-5">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Test Name</label
                                        >
                                        <input
                                            type="text"
                                            :value="service?.name"
                                            readonly
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-700 cursor-not-allowed"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Preferred Doctor (Optional)</label
                                        >
                                        <select
                                            v-model="form.doctorId"
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        >
                                            <option value="">
                                                Select Doctor (Optional)
                                            </option>
                                            <option
                                                v-for="doctor in doctors"
                                                :key="doctor.id"
                                                :value="doctor.id"
                                            >
                                                {{ doctor.user.name }} -
                                                {{ doctor.speciality }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-5">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Preferred Date *</label
                                        >
                                        <select
                                            v-model="form.bookingDate"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        >
                                            <option value="">
                                                Select Preferred Date
                                            </option>
                                            <option
                                                v-for="date in availableDates"
                                                :key="date.value"
                                                :value="date.value"
                                            >
                                                {{ date.label }}
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Preferred Time *</label
                                        >
                                        <select
                                            v-model="form.bookingTime"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
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
                            </div>

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
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >First Name *</label
                                        >
                                        <input
                                            v-model="form.firstName"
                                            type="text"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            placeholder="Enter your first name"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Last Name *</label
                                        >
                                        <input
                                            v-model="form.lastName"
                                            type="text"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            placeholder="Enter your last name"
                                        />
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Email Address *</label
                                        >
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            placeholder="your@email.com"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Phone Number *</label
                                        >
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            placeholder="+88 (013) 1235-4567"
                                        />
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Gender *</label
                                        >
                                        <select
                                            v-model="form.gender"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        >
                                            <option value="">
                                                Select Gender
                                            </option>
                                            <option value="male">Male</option>
                                            <option value="female">
                                                Female
                                            </option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Age *</label
                                        >
                                        <input
                                            v-model="form.age"
                                            type="number"
                                            required
                                            min="1"
                                            max="99"
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            placeholder="Enter your age"
                                        />
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-1"
                                        >Address *</label
                                    >
                                    <textarea
                                        v-model="form.address"
                                        required
                                        :disabled="submitting"
                                        rows="3"
                                        placeholder="Enter your complete address for sample collection..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 resize-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    ></textarea>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-gray-700 mb-1"
                                            >Payment Method *</label
                                        >
                                        <select
                                            v-model="form.paymentMethod"
                                            required
                                            :disabled="submitting"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 appearance-none bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
                                        >
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="online">
                                                Online
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-1"
                                        >Additional Notes</label
                                    >
                                    <textarea
                                        v-model="form.additionalNotes"
                                        :disabled="submitting"
                                        rows="4"
                                        placeholder="Any special instructions, medical history, or additional information..."
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 placeholder-gray-400 resize-none"
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
                                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition-all duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{
                                        submitting
                                            ? "📋 Booking..."
                                            : "📋 Book Diagnostic Test Now"
                                    }}
                                </button>
                                <p
                                    class="text-center text-sm text-gray-600 mt-4"
                                >
                                    By booking a test, you agree to our
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
                                class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4"
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
                                class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4"
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
                                Quick Results
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Fast turnaround time
                            </p>
                        </div>
                        <div
                            class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm"
                        >
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4"
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
                                Expert Staff
                            </h4>
                            <p class="text-gray-600 text-sm mt-2">
                                Certified professionals
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <Footer />

    <!-- Success Modal -->
    <div
        v-if="showSuccessModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click="closeModal"
    >
        <div
            class="bg-white rounded-xl shadow-2xl max-w-md w-full p-8 text-center relative"
            @click.stop
        >
            <!-- Close Button -->
            <button
                @click="closeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
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
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

            <!-- Success Icon -->
            <div
                class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6"
            >
                <svg
                    class="w-10 h-10 text-green-600"
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
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-black text-gray-900 mb-4">
                Test Booked Successfully!
            </h2>

            <!-- Message -->
            <p class="text-gray-600 mb-6 leading-relaxed">
                Your diagnostic test booking has been confirmed. You will
                receive a confirmation email and SMS with all the details.
            </p>

            <!-- Booking Details -->
            <div
                v-if="successBooking"
                class="bg-blue-50 rounded-lg p-4 mb-6 text-left"
            >
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking ID:</span>
                        <span class="font-semibold text-gray-900">{{
                            successBooking.id
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Test:</span>
                        <span class="font-semibold text-gray-900">{{
                            service?.name
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold text-gray-900">{{
                            new Date(
                                successBooking.booking_date
                            ).toLocaleDateString()
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-semibold text-gray-900">{{
                            successBooking.booking_time
                        }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold text-green-600">{{
                            successBooking.status
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <button
                @click="downloadBookingDetails(successBooking.id)"
                class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition-all duration-300 mb-4"
            >
                📄 Download Booking Details
            </button>

            <!-- Close Button -->
            <button
                @click="closeModal"
                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-300"
            >
                Close
            </button>
        </div>
    </div>
</template>
