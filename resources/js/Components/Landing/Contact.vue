<template>
    <section id="contact" class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-16 items-start"
            >
                <div>
                    <h2
                        class="text-2xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-2"
                    >
                        Get In Touch
                    </h2>
                    <p
                        class="text-base md:text-lg text-gray-600 leading-relaxed mb-6 md:mb-8"
                    >
                        Have questions or need to schedule an appointment? Our
                        team is here to help you with all your healthcare needs.
                    </p>

                    <div class="space-y-4 md:space-y-6">
                        <div
                            class="flex items-center gap-3 md:gap-4 p-4 md:p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
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
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="font-bold text-gray-900 text-sm md:text-base"
                                >
                                    Emergency
                                </h4>
                                <p class="text-gray-600 text-sm md:text-base">
                                    (555) 123-HELP
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-3 md:gap-4 p-4 md:p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="font-bold text-gray-900 text-sm md:text-base"
                                >
                                    Location
                                </h4>
                                <p class="text-gray-600 text-sm md:text-base">
                                    123 Medical Avenue, Healthcare District
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-3 md:gap-4 p-4 md:p-6 bg-gray-50 rounded-2xl"
                        >
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"
                            >
                                <svg
                                    class="w-5 h-5 md:w-6 md:h-6"
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
                            <div class="min-w-0 flex-1">
                                <h4
                                    class="font-bold text-gray-900 text-sm md:text-base"
                                >
                                    Working Hours
                                </h4>
                                <p class="text-gray-600 text-sm md:text-base">
                                    Emergency: 24/7 | OPD: 8AM-8PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50/30 p-6 md:p-8 rounded-2xl border border-blue-200 mt-8 lg:mt-0"
                >
                    <h3
                        class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6"
                    >
                        Send us a Message
                    </h3>
                    <form class="space-y-4 md:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >First Name</label
                                >
                                <input
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 text-sm md:text-base"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Last Name</label
                                >
                                <input
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 text-sm md:text-base"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Email</label
                            >
                            <input
                                type="email"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 text-sm md:text-base"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Message</label
                            >
                            <textarea
                                rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200 text-sm md:text-base resize-vertical"
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-3 md:py-4 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 text-sm md:text-base"
                        >
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
