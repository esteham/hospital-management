<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="flex flex-col items-center justify-center px-4">
            <div
                class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 transition-all duration-300"
            >
                <h2 class="text-2xl font-semibold text-center mb-2">
                    Forgot Your Password?
                </h2>
                <p
                    class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6"
                >
                    No worries — just enter your email address below and we’ll
                    send you a reset link.
                </p>

                <div
                    v-if="status"
                    class="mb-4 text-sm text-green-600 bg-green-50 dark:bg-green-800/30 rounded-lg p-3 text-center"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel for="email" value="Email Address" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-gray-50 dark:bg-gray-800 focus:border-indigo-500 focus:ring-indigo-500 transition-all text-white"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@example.com"
                        />
                        <InputError
                            class="mt-2 text-red-600"
                            :message="form.errors.email"
                        />
                    </div>

                    <div class="pt-2">
                        <PrimaryButton
                            class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl py-2.5 transition-all duration-200 disabled:opacity-50"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Email Password Reset Link
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a
                        href="/login"
                        class="text-sm text-indigo-200 hover:text-indigo-300 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors"
                    >
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
