<script setup>
import AppLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";

const props = defineProps({
    healthCheck: Object,
});

const form = useForm({
    name: "",
    price: "",
    features: [],
    popular: false,
});

const featuresText = ref("");

onMounted(() => {
    form.name = props.healthCheck.name;
    form.price = props.healthCheck.price;
    form.features = props.healthCheck.features;
    form.popular = props.healthCheck.popular;
    featuresText.value = props.healthCheck.features.join("\n");
});

watch(featuresText, (newValue) => {
    form.features = newValue
        .split("\n")
        .map((feature) => feature.trim())
        .filter((feature) => feature);
});

const submit = () => {
    form.put(route("admin.health-checks.update", props.healthCheck.id), {
        onSuccess: () => {
            // Handle success
        },
    });
};
</script>

<template>
    <AppLayout title="Edit Health Check">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Health Check
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700"
                                    >Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label
                                    for="price"
                                    class="block text-sm font-medium text-gray-700"
                                    >Price</label
                                >
                                <input
                                    v-model="form.price"
                                    type="text"
                                    id="price"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                />
                                <div
                                    v-if="form.errors.price"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.price }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label
                                    for="features"
                                    class="block text-sm font-medium text-gray-700"
                                    >Features</label
                                >
                                <textarea
                                    v-model="featuresText"
                                    id="features"
                                    rows="5"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter each feature on a new line"
                                    required
                                ></textarea>
                                <div
                                    v-if="form.errors.features"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.features }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    <input
                                        v-model="form.popular"
                                        type="checkbox"
                                        class="mr-2"
                                    />
                                    Popular
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <Link
                                    :href="route('admin.health-checks.index')"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    :disabled="form.processing"
                                >
                                    Update Health Check
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
