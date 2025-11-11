<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import api from "@/lib/api";

import Header from "@/Components/Landing/Header.vue";
import Logo from "@/assets/images/logo/logo.png";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);
const emailAvailable = ref(null);
const checkingEmail = ref(false);

const emailValid = computed(() => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return form.email ? emailRegex.test(form.email) : null;
});

// Watch for email changes and check availability
watch(
    () => form.email,
    async (newEmail) => {
        if (!newEmail || !emailValid.value) {
            emailAvailable.value = null;
            return;
        }

        checkingEmail.value = true;
        try {
            const response = await api.post("/api/email-check", {
                email: newEmail,
            });
            emailAvailable.value = response.data.available;
        } catch (error) {
            console.error("Email check failed:", error);
            emailAvailable.value = null;
        } finally {
            checkingEmail.value = false;
        }
    }
);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register - Xet Specialized Hospital" />
    <!-- Modern Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side - Registration Form -->
        <div
            class="hidden lg:flex flex-1 relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 order-1"
        >
            <div class="absolute inset-0 bg-black/10"></div>

            <div
                class="relative flex flex-col justify-center px-6 md:px-12 mt-0 max-w-lg md:max-w-xl"
            >
                <!-- Hospital Info -->
                <div class="text-white">
                    <div class="flex">
                        <img
                            :src="Logo"
                            class="w-16 h-16 md:w-20 md:h-20 mb-8 mr-5"
                        />

                        <div
                            class="flex items-center justify-center lg:justify-start space-x-3 mb-8"
                        >
                            <div>
                                <h2
                                    class="text-xl md:text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-500 bg-clip-text text-transparent"
                                >
                                    Xet Hospital
                                </h2>
                                <p class="text-xs md:text-sm text-black-500">
                                    Join Our Team
                                </p>
                            </div>
                        </div>
                    </div>

                    <h1 class="text-2xl md:text-4xl font-bold mb-4">
                        Join Xet Specialized Hospital
                    </h1>
                    <p class="text-lg md:text-xl text-green-100 mb-6">
                        Become Part of Our Medical Excellence
                    </p>

                    <div
                        class="space-y-3 md:space-y-4 text-green-100 text-sm md:text-base"
                    >
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white"
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
                            <span
                                >Secure access to patient management
                                systems</span
                            >
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white"
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
                            <span>Collaborate with medical professionals</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                            <span>Real-time access to medical records</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                            <span>HIPAA compliant secure platform</span>
                        </div>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="absolute bottom-4 md:bottom-8 left-6 md:left-12">
                    <div
                        class="flex items-center space-x-2 text-green-200 text-xs md:text-sm"
                    >
                        <svg
                            class="w-3 h-3 md:w-4 md:h-4"
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
                        <span>Enterprise Security • HIPAA Compliant</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Hospital Branding -->
        <div
            class="flex-1 flex flex-col justify-center py-8 md:py-12 px-4 sm:px-6 lg:px-20 xl:px-24 order-2"
        >
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Create Account
                    </h2>
                    <p class="mt-2 text-sm md:text-base text-gray-600">
                        Register for the hospital management system
                    </p>
                </div>

                <!-- Registration Form -->
                <form
                    @submit.prevent="submit"
                    class="mt-6 md:mt-8 space-y-4 md:space-y-6"
                >
                    <div class="space-y-4 md:space-y-5">
                        <!-- Name Field -->
                        <div>
                            <InputLabel
                                for="name"
                                value="Full Name"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            />
                            <div class="relative">
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Enter your full name"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.name,
                                    }"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <!-- Email Field -->
                        <div>
                            <InputLabel
                                for="email"
                                value="Work Email"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            />
                            <div class="relative">
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Enter your work email"
                                    v-model="form.email"
                                    required
                                    autocomplete="email"
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.email,
                                    }"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="checkingEmail"
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 animate-spin"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                    <svg
                                        v-else-if="emailValid === null"
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                        />
                                    </svg>
                                    <svg
                                        v-else-if="
                                            emailValid &&
                                            emailAvailable === null
                                        "
                                        class="h-4 w-4 md:h-5 md:w-5 text-green-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <svg
                                        v-else-if="emailValid && emailAvailable"
                                        class="h-4 w-4 md:h-5 md:w-5 text-green-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <svg
                                        v-else-if="
                                            emailValid && !emailAvailable
                                        "
                                        class="h-4 w-4 md:h-5 md:w-5 text-red-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-4 w-4 md:h-5 md:w-5 text-red-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                            <div
                                v-if="emailValid && emailAvailable !== null"
                                class="mt-1 text-sm"
                                :class="
                                    emailAvailable
                                        ? 'text-green-600'
                                        : 'text-red-600'
                                "
                            >
                                {{
                                    emailAvailable
                                        ? "Email is available"
                                        : "Email is already in use"
                                }}
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <InputLabel
                                for="password"
                                value="Password"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            />
                            <div class="relative">
                                <TextInput
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 pr-10 md:pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Create a strong password"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.password,
                                    }"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg
                                        v-if="showPassword"
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <InputLabel
                                for="password_confirmation"
                                value="Confirm Password"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            />
                            <div class="relative">
                                <TextInput
                                    id="password_confirmation"
                                    :type="
                                        showConfirmPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 pr-10 md:pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Confirm your password"
                                    v-model="form.password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.password_confirmation,
                                    }"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                    @click="
                                        showConfirmPassword =
                                            !showConfirmPassword
                                    "
                                >
                                    <svg
                                        v-if="showConfirmPassword"
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start space-x-3 pt-2">
                            <Checkbox
                                name="terms"
                                v-model:checked="form.terms"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5"
                            />
                            <div class="text-xs md:text-sm">
                                <label
                                    for="terms"
                                    class="font-medium text-gray-700"
                                >
                                    I agree to the
                                    <a
                                        href="#"
                                        class="text-green-600 hover:text-green-500 font-semibold"
                                        >Terms of Service</a
                                    >
                                    and
                                    <a
                                        href="#"
                                        class="text-green-600 hover:text-green-500 font-semibold"
                                        >Privacy Policy</a
                                    >
                                </label>
                                <p
                                    class="text-gray-500 mt-1 text-xs md:text-sm"
                                >
                                    Including HIPAA compliance and data
                                    protection guidelines
                                </p>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.terms" />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <PrimaryButton
                            class="w-full flex justify-center py-2 md:py-3 px-3 md:px-4 border border-transparent rounded-xl shadow-sm text-sm md:text-base font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing || !form.terms"
                        >
                            <span class="flex items-center">
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                {{
                                    form.processing
                                        ? "Creating Account..."
                                        : "Create Account"
                                }}
                            </span>
                        </PrimaryButton>
                    </div>

                    <!-- Login Link -->
                    <div
                        class="text-center pt-3 md:pt-4 border-t border-gray-200"
                    >
                        <p class="text-sm md:text-base text-gray-600">
                            Already have an account?
                            <Link
                                :href="route('login')"
                                class="font-semibold text-green-600 hover:text-green-500 transition-colors duration-200 ml-1"
                            >
                                Sign in here
                            </Link>
                        </p>
                    </div>

                    <!-- Support Contact -->
                    <div class="text-center">
                        <p class="text-xs md:text-sm text-gray-500">
                            Need help with registration?
                            <a
                                href="mailto:hr@xethospital.com"
                                class="font-medium text-green-600 hover:text-green-500"
                            >
                                Contact HR
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
