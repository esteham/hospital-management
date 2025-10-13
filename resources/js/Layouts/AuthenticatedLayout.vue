<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps({ title: { type: String, default: ''} })

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null)
</script>

<template>
    
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center space-x-6">
                            <Link :href="route('dashboard')" class="text-gray-700 hover:text-gray-900 font-medium">
                            Home
                            </Link>

                            <Link
                                v-if="user && user.role === 'doctor'"
                                href="/doctor" class="text-gray-700 hover:text-gray-900"
                            >
                            Doctor Portal                                
                            </Link>

                            <Link
                                v-if="user && user.role==='doctor'"
                                href="/doctor/schedules"
                                class="text-gray-700 hover:text-gray-900"
                            >
                               My Schedules 
                            </Link>

                            <!-- Admin-only -->
                            <Link
                                v-if="user && user.role === 'admin'"
                                href="/admin/doctors"
                                class="text-gray-700 hover:text-gray-900">
                                Admin: Doctors
                            </Link>

                            <Link v-if="user && user.role === 'admin'" href="/admin/schedules">Show Doctors Schedules</Link>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span v-if="user" class="text-sm text-gray-700">{{ user.name }}</span>
                            <Link
                                v-if="user"
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm text-gray-600 hover:text-gray-900"
                            >
                                Log Out
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

        <header v-if="title" class="bg-white shadow">
            <div class="max-w-7x1 mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
            </div>
        </header>

        <main>
            <div class="max-w-7x1 mx-auto py-6 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>
</template>
