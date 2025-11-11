<script setup>
import { ref } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Header from "@/Components/Landing/Header.vue";
import Logo from "@/assets/images/logo/logo.png";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Login - Xet Specialized Hospital" />
    <!-- Modern Header -->
    <Header :canLogin="canLogin" :canRegister="canRegister" />

    <!-- Main Container -->
    <div class="min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div
            class="flex-1 flex flex-col justify-center py-8 md:py-12 px-4 sm:px-6 lg:px-20 xl:px-24"
        >
            <div class="mx-auto w-full max-w-sm lg:w-96 px-4 md:px-0">
                <!-- Header -->
                <div class="text-center lg:text-left">
                    <!-- <div
                        class="flex items-center justify-center lg:justify-start space-x-3 mb-8"
                    >
                        <img :src="Logo" class="w-12 h-12" />

                        <div>
                            <h2
                                class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent"
                            >
                                Xet Hospital
                            </h2>
                            <p class="text-sm text-gray-500">
                                Management System
                            </p>
                        </div>
                    </div> -->

                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Welcome back
                    </h2>
                    <p class="mt-2 text-sm md:text-base text-gray-600">
                        Access the hospital management portal
                    </p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mt-4 md:mt-6 p-3 md:p-4 rounded-xl bg-green-50 border border-green-200 animate-fade-in"
                >
                    <div class="flex items-center">
                        <svg
                            class="w-5 h-5 text-green-500 mr-2"
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
                        <span class="text-sm font-medium text-green-800">{{
                            status
                        }}</span>
                    </div>
                </div>

                <!-- Login Form -->
                <form
                    @submit.prevent="submit"
                    class="mt-6 md:mt-8 space-y-4 md:space-y-6"
                >
                    <div class="space-y-4">
                        <!-- Email Field -->
                        <div>
                            <InputLabel
                                for="email"
                                value="Email Address"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            />
                            <div class="relative">
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Enter your email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    :class="{
                                        'border-red-500 focus:ring-red-500':
                                            form.errors.email,
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
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
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
                                    class="block w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm md:text-base"
                                    placeholder="Enter your password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
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
                                        class="h-5 w-5 text-gray-400"
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
                                        class="h-5 w-5 text-gray-400"
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
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0"
                    >
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                                class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Remember me</span
                            >
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <PrimaryButton
                            class="w-full flex justify-center py-2 md:py-3 px-3 md:px-4 border border-transparent rounded-xl shadow-sm text-sm md:text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
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
                                        ? "Signing in..."
                                        : "Sign in to portal"
                                }}
                            </span>
                        </PrimaryButton>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center mt-3 md:mt-4">
                        <p class="text-sm md:text-base text-gray-600">
                            Don't have an account?
                            <Link
                                :href="route('register')"
                                class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200"
                            >
                                Sign up
                            </Link>
                        </p>
                    </div>

                    <!-- Support Contact -->
                    <div
                        class="text-center pt-3 md:pt-4 border-t border-gray-200"
                    >
                        <p class="text-xs md:text-sm text-gray-500">
                            Need help accessing your account?
                            <a
                                href="mailto:support@xethospital.com"
                                class="font-medium text-blue-600 hover:text-blue-500"
                            >
                                Contact support
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side - Hospital Branding -->
        <div
            class="hidden lg:flex flex-1 relative bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-600 overflow-hidden"
        >
            <div class="absolute inset-0 bg-black/10"></div>
            <div
                class="relative flex flex-col justify-center px-6 md:px-12 max-w-2xl"
            >
                <!-- Hospital Info -->
                <div class="text-white">
                    <img :src="Logo" class="w-20 h-20 mb-5" />

                    <h1 class="text-2xl md:text-4xl font-bold mb-4">
                        Xet Specialized Hospital
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-6">
                        Management & Support System
                    </p>

                    <div
                        class="space-y-3 md:space-y-4 text-blue-100 text-sm md:text-base"
                    >
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white flex-shrink-0"
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
                            <span>Secure patient data management</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white flex-shrink-0"
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
                            <span>Real-time medical records access</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 text-white flex-shrink-0"
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
                            <span>Staff collaboration tools</span>
                        </div>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="absolute bottom-4 md:bottom-8 left-6 md:left-12">
                    <div
                        class="flex items-center space-x-2 text-blue-200 text-xs md:text-sm"
                    >
                        <svg
                            class="w-3 h-3 md:w-4 md:h-4 flex-shrink-0"
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
                        <span>HIPAA Compliant • Encrypted Connection</span>
                    </div>
                </div>
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
