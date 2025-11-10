<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import api from "@/lib/api";

const doctors = ref([]);

const fetchDoctors = async () => {
    try {
        const response = await api.get("/api/doctors");
        doctors.value = response.data;
    } catch (error) {
        console.error("Error fetching doctors:", error);
    }
};

onMounted(() => {
    fetchDoctors();
});
</script>

<template>
    <section class="specialties-offered py-16">
        <div class="mx-auto overflow-hidden">
            <div class="text-card">
                <h2 class="text-3xl font-semibold text-gray-900">
                    Our
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500"
                    >
                        Specialists </span
                    >👨‍⚕️
                </h2>
                <p class="text-l text-gray-600 max-w-2xl mt-2">
                    Highly Experienced, Internationally Trained Specialists from
                    reputed medical colleges are the top choice of Bangladeshi
                    patients for their healthcare needs.
                </p>
            </div>

            <div v-if="doctors.length === 0" class="text-center">
                <p class="text-gray-500">Loading specialists...</p>
            </div>

            <div v-else class="marquee-container space-y-6">
                <!-- Top Row -->
                <div class="marquee-track">
                    <div class="marquee-segment flex gap-4">
                        <Link
                            v-for="doctor in doctors.slice(
                                0,
                                Math.ceil(doctors.length / 2)
                            )"
                            :key="'top-' + doctor.id"
                            :href="`/doctor/${doctor.id}`"
                            class="doctor-card flex items-center gap-4 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition"
                        >
                            <div
                                :style="{
                                    backgroundImage: `url(${
                                        doctor.user.photo ||
                                        '/images/default-doctor.png'
                                    })`,
                                    backgroundSize: 'cover',
                                    backgroundPosition: 'center',
                                }"
                            ></div>
                            <div class="flex-1">
                                <h3
                                    class="text-blue-700 text-sm font-semibold mb-1"
                                >
                                    {{ doctor.user.name }}
                                </h3>
                                <div class="text-xs text-gray-600 mb-1">
                                    {{ doctor.designation }}
                                </div>
                                <div
                                    class="text-xs text-gray-600 leading-tight"
                                >
                                    {{ doctor.speciality }}
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Bottom Row -->
                <div class="marquee-track reverse">
                    <div class="marquee-segment flex gap-4">
                        <<Link
                            v-for="doctor in doctors.slice(
                                Math.ceil(doctors.length / 2)
                            )"
                            :key="'bottom-' + doctor.id"
                            :href="`/doctor/${doctor.id}`"
                            class="doctor-card flex items-center gap-4 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition"
                        >
                            <div
                                :style="{
                                    backgroundImage: `url(${
                                        doctor.user.photo ||
                                        '/images/default-doctor.png'
                                    })`,
                                }"
                            ></div>
                            <div class="flex-1">
                                <h3
                                    class="text-blue-700 text-sm font-semibold mb-1"
                                >
                                    {{ doctor.user.name }}
                                </h3>
                                <div class="text-xs text-gray-600 mb-1">
                                    {{ doctor.designation }}
                                </div>
                                <div
                                    class="text-xs text-gray-600 leading-tight"
                                >
                                    {{ doctor.speciality }}
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
    margin: 0 130px;
    margin-bottom: 40px;
}
.specialties-offered {
    background-color: #fff;
}

.doctor-card {
    width: 340px;
    height: 180px;
    display: flex;
    transition: transform 0.3s ease;
    align-items: stretch;
}

.doctor-card > div:first-child {
    width: 112px;
    height: 100%;
    border-radius: 0.375rem;
    overflow: hidden;
    flex-shrink: 0;
    background-size: cover;
    background-position: center;
}

.doctor-card > .flex-1 {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-grow: 1;
    min-width: 0;
    padding: 0 12px;
    word-break: break-word;
}

.doctor-card:hover {
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
    gap: 18px;
}

/* adjust speed by changing duration (lower = faster) */
@keyframes scroll-right {
    1000% {
        transform: translateX(0);
    }
    0% {
        transform: translateX(-50%);
    }
}
</style>
