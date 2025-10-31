<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import Header from "@/Components/Landing/Header.vue";

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

const submitForm = () => {
    // Handle form submission
    console.log("Appointment booking form submitted:", form.value);
    // You can add API call here later
};

</script>

<template>
    <Head title="Book Your Appointment - Xet Specialized Hospital" />

    <!-- Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Content -->
    <main
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-cyan-50"
    >
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-2xl lg:text-3xl font-black text-gray-900 mb-6">
                    Book Your Appointment
                </h1>
                <p
                    class="text-l text-gray-600 leading-relaxed max-w-3xl mx-auto"
                >
                    Ready to take the next step in your healthcare journey?
                    Schedule an appointment with our expert doctors today. Our
                    team is here to provide personalized care tailored to your
                    needs.
                </p>
            </div>

            <!-- Appointment Form -->
            <div
                class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 lg:p-12"
            >
                <form @submit.prevent="submitForm" class="space-y-8">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">
                            Personal Information
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >First Name *</label
                                >
                                <input
                                    v-model="form.firstName"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                    placeholder="Enter your first name"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Last Name *</label
                                >
                                <input
                                    v-model="form.lastName"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                    placeholder="Enter your last name"
                                />
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Email *</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                    placeholder="your@email.com"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Phone Number *</label
                                >
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                    placeholder="+1 (555) 123-4567"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">
                            Appointment Details
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Preferred Date *</label
                                >
                                <input
                                    v-model="form.preferredDate"
                                    type="date"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Preferred Time *</label
                                >
                                <select
                                    v-model="form.preferredTime"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                >
                                    <option value="">Select Time</option>
                                    <option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Medical Speciality *</label
                            >
                            <select
                                v-model="form.speciality"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                            >
                                <option value="">Select Speciality</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="dermatology">Dermatology</option>
                                <option value="neurology">Neurology</option>
                                <option value="orthopedics">Orthopedics</option>
                                <option value="general">
                                    General Medicine
                                </option>
                                <option value="pediatrics">Pediatrics</option>
                                <option value="gynecology">Gynecology</option>
                                <option value="ophthalmology">
                                    Ophthalmology
                                </option>
                            </select>
                        </div>
                        <div class="mt-6">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Additional Notes</label
                            >
                            <textarea
                                v-model="form.additionalNotes"
                                rows="4"
                                placeholder="Please describe your symptoms, reason for visit, or any special requirements..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6 border-t border-gray-200">
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-4 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 text-lg"
                        >
                            Book Appointment
                        </button>
                        <p class="text-center text-sm text-gray-600 mt-4">
                            By booking an appointment, you agree to our terms
                            and conditions.
                        </p>
                    </div>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="mt-12 bg-white rounded-2xl p-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">
                    Need Help?
                </h3>
                <div class="grid md:grid-cols-3 gap-6 text-center">
                    <div>
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
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Call Us</h4>
                        <p class="text-gray-600">(555) 123-HELP</p>
                    </div>
                    <div>
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
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Email Us</h4>
                        <p class="text-gray-600">
                            appointments@xethospital.com
                        </p>
                    </div>
                    <div>
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
                                ></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">
                            Working Hours
                        </h4>
                        <p class="text-gray-600">Mon-Fri: 8AM-8PM</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
