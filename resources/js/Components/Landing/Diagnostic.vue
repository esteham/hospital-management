<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import api from "@/lib/api";

const diagnosticServices = ref([]);

const fetchDiagnosticServices = async () => {
    try {
        const response = await api.get("/api/diagnostic-services");
        diagnosticServices.value = response.data;
    } catch (error) {
        console.error("Error fetching diagnostic services:", error);
    }
};

onMounted(() => {
    fetchDiagnosticServices();
});
</script>

<template>
    <section class="diagnostic-services pt-16">
        <div class="mx-auto overflow-hidden">
            <div class="text-card">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-900">
                    Diagnostic
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Services </span
                    >🧪
                </h2>
                <p class="text-base text-gray-600 max-w-2xl mt-2">
                    Advanced diagnostic services with state-of-the-art equipment
                    for accurate and timely results.
                </p>
            </div>

            <div v-if="diagnosticServices.length === 0" class="text-center">
                <p class="text-gray-500">Loading diagnostic services...</p>
            </div>

            <div v-else class="marquee-container space-y-2">
                <!-- Top Row -->
                <div class="marquee-track">
                    <div class="marquee-segment flex gap-2">
                        <Link
                            v-for="service in diagnosticServices.slice(
                                0,
                                Math.ceil(diagnosticServices.length / 2)
                            )"
                            :key="'top-' + service.id"
                            :href="`/diagnostic/service/${service.id}`"
                            class="service-card flex bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition"
                        >
                            <div
                                :style="{
                                    backgroundImage: `url(${
                                        service.image ||
                                        '/images/default-service.png'
                                    })`,
                                    backgroundSize: 'cover',
                                    backgroundPosition: 'center',
                                }"
                            ></div>
                            <div class="flex-1">
                                <h3
                                    class="text-blue-700 text-sm font-semibold mb-1"
                                >
                                    {{ service.name }}
                                </h3>

                                <div
                                    class="text-xs text-gray-600 leading-tight"
                                >
                                    {{
                                        service.description
                                            ? service.description.substring(
                                                  0,
                                                  50
                                              ) + "..."
                                            : "No description available"
                                    }}
                                </div>
                                <div
                                    class="text-xs text-green-600 font-medium mt-1"
                                >
                                    ${{ service.price }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Bottom Row -->
                <div class="marquee-track reverse">
                    <div class="marquee-segment flex gap-2">
                        <Link
                            v-for="service in diagnosticServices.slice(
                                Math.ceil(diagnosticServices.length / 2)
                            )"
                            :key="'bottom-' + service.id"
                            :href="`/diagnostic/service/${service.id}`"
                            class="service-card flex bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition"
                        >
                            <div
                                :style="{
                                    backgroundImage: `url(${
                                        service.image ||
                                        '/images/default-service.png'
                                    })`,
                                }"
                            ></div>
                            <div class="flex-1">
                                <h3
                                    class="text-blue-700 text-sm font-semibold mb-1"
                                >
                                    {{ service.name }}
                                </h3>

                                <div
                                    class="text-xs text-gray-600 leading-tight"
                                >
                                    {{
                                        service.description
                                            ? service.description.substring(
                                                  0,
                                                  50
                                              ) + "..."
                                            : "No description available"
                                    }}
                                </div>
                                <div
                                    class="text-xs text-green-600 font-medium mt-1"
                                >
                                    ${{ service.price }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-card {
    margin: 0 20px 40px;
}

@media (min-width: 768px) {
    .text-card {
        margin: 0 50px 40px;
    }
}

@media (min-width: 1024px) {
    .text-card {
        margin: 0 130px 40px;
    }
}

.diagnostic-services {
    background-color: #fff;
}

.service-card {
    width: 280px;
    height: 120px;
    display: flex;
    transition: transform 0.3s ease;
    align-items: stretch;
}

@media (min-width: 640px) {
    .service-card {
        width: 300px;
        height: 130px;
    }
}

@media (min-width: 1024px) {
    .service-card {
        width: 300px;
        height: 125px;
    }
}

.service-card > div:first-child {
    width: 100px;
    height: 100%;
    border-radius: 0.375rem;
    overflow: hidden;
    flex-shrink: 0;
    background-size: cover;
    background-position: center;
}

@media (min-width: 640px) {
    .service-card > div:first-child {
        width: 112px;
    }
}

.service-card > .flex-1 {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-grow: 1;
    min-width: 0;
    padding: 0 8px;
    word-break: break-word;
}

@media (min-width: 640px) {
    .service-card > .flex-1 {
        padding: 0 12px;
    }
}

.service-card:hover {
    transform: translateY(-4px);
}

/* --- Continuous Scroll --- */
.marquee-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.marquee-track {
    display: flex;
    width: max-content;
    animation: scroll-right 60s linear infinite;
}

.marquee-segment {
    display: flex;
    gap: 12px;
}

@media (min-width: 640px) {
    .marquee-segment {
        gap: 18px;
    }
}

/* adjust speed by changing duration (lower = faster) */
@keyframes scroll-right {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-30%);
    }
}
</style>
