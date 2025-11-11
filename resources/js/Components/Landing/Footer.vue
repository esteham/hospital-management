<script setup lang="ts">
import { HeartOff, Link } from "lucide-vue-next";
import Logo from "@/assets/images/logo/logo.png";

// Payment logos
import BkashLogo from "@/assets/images/payment/bkash.png";
import NagadLogo from "@/assets/images/payment/nagad.png";
import RocketLogo from "@/assets/images/payment/rocket.png";
import UpayLogo from "@/assets/images/payment/upay.png";
import VisaLogo from "@/assets/images/payment/visa.png";
import MasterCardLogo from "@/assets/images/payment/mastercard.png";
</script>

<template>
    <footer
        class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-12 sm:py-20"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 sm:gap-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-start sm:items-center gap-4 mb-6">
                        <img :src="Logo" class="w-12 h-12 sm:w-14 sm:h-14" />
                        <div>
                            <h4 class="text-xl sm:text-2xl font-bold">
                                Xet Specialized Hospital
                            </h4>
                            <p
                                class="text-gray-400 font-medium text-sm sm:text-base"
                            >
                                Excellence in Specialized Healthcare
                            </p>
                        </div>
                    </div>
                    <p
                        class="text-gray-400 leading-relaxed max-w-md text-sm sm:text-base"
                    >
                        Delivering world-class specialized healthcare with
                        compassion, innovation, and unwavering commitment to
                        patient well-being.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <!-- Social Links -->
                        <a
                            href="https://facebook.com/esteham"
                            target="_blank"
                            class="group w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-white transition-all duration-300 hover:scale-110"
                        >
                            <span class="sr-only">Facebook</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="28"
                                viewBox="0 0 72 72"
                                fill="none"
                            >
                                <path
                                    d="M46.4927 38.6403L47.7973 30.3588H39.7611V24.9759C39.7611 22.7114 40.883 20.4987 44.4706 20.4987H48.1756V13.4465C46.018 13.1028 43.8378 12.9168 41.6527 12.8901C35.0385 12.8901 30.7204 16.8626 30.7204 24.0442V30.3588H23.3887V38.6403H30.7204V58.671H39.7611V38.6403H46.4927Z"
                                    fill="#337FFF"
                                />
                            </svg>
                        </a>

                        <a
                            href="https://x.com/esteham6"
                            target="_blank"
                            class="group w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-white transition-all duration-300 hover:scale-110"
                        >
                            <span class="sr-only">Twitter</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="28"
                                viewBox="0 0 72 72"
                                fill="none"
                            >
                                <path
                                    d="M40.7568 32.1716L59.3704 11H54.9596L38.7974 29.383L25.8887 11H11L30.5205 38.7983L11 61H15.4111L32.4788 41.5869L46.1113 61H61L40.7557 32.1716H40.7568ZM34.7152 39.0433L32.7374 36.2752L17.0005 14.2492H23.7756L36.4755 32.0249L38.4533 34.7929L54.9617 57.8986H48.1865L34.7152 39.0443V39.0433Z"
                                    fill="black"
                                />
                            </svg>
                        </a>

                        <a
                            href="https://www.linkedin.com/in/esteham"
                            target="_blank"
                            class="group w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-white transition-all duration-300 hover:scale-110"
                        >
                            <span class="sr-only">LinkedIn</span>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="28"
                                height="28"
                                viewBox="0 0 72 72"
                                fill="none"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M14.6975 11C12.6561 11 11 12.6057 11 14.5838V57.4474C11 59.4257 12.6563 61.03 14.6975 61.03H57.3325C59.3747 61.03 61.03 59.4255 61.03 57.4468V14.5838C61.03 12.6057 59.3747 11 57.3325 11H14.6975ZM26.2032 30.345V52.8686H18.7167V30.345H26.2032ZM26.6967 23.3793C26.6967 25.5407 25.0717 27.2703 22.4615 27.2703L22.4609 27.2701H22.4124C19.8998 27.2701 18.2754 25.5405 18.2754 23.3791C18.2754 21.1686 19.9489 19.4873 22.5111 19.4873C25.0717 19.4873 26.6478 21.1686 26.6967 23.3793ZM37.833 52.8686H30.3471L30.3469 52.8694C30.3469 52.8694 30.4452 32.4588 30.3475 30.3458H37.8336V33.5339C38.8288 31.9995 40.6098 29.8169 44.5808 29.8169C49.5062 29.8169 53.1991 33.0363 53.1991 39.9543V52.8686H45.7133V40.8204C45.7133 37.7922 44.6293 35.7269 41.921 35.7269C39.8524 35.7269 38.6206 37.1198 38.0796 38.4653C37.8819 38.9455 37.833 39.6195 37.833 40.2918V52.8686Z"
                                    fill="#006699"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h5 class="text-lg font-bold mb-4 sm:mb-6">Quick Links</h5>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a
                                href="#home"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Home</a
                            >
                        </li>
                        <li>
                            <a
                                href="#services"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Services</a
                            >
                        </li>
                        <li>
                            <a
                                href="#health-s"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Health s</a
                            >
                        </li>
                        <li>
                            <a
                                href="#patient-stories"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Patient Stories</a
                            >
                        </li>
                        <li>
                            <a
                                href="#blog"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Blog</a
                            >
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h5 class="text-lg font-bold mb-4 sm:mb-6">Our Services</h5>
                    <ul class="space-y-2 sm:space-y-3">
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Emergency Care</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Cardiology</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Neurology</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Surgery</a
                            >
                        </li>
                        <li>
                            <a
                                href="#"
                                class="text-gray-400 hover:text-white text-sm sm:text-base"
                                >Diagnostics</a
                            >
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h5 class="text-lg font-bold mb-4 sm:mb-6">Stay Updated</h5>
                    <p class="text-gray-400 mb-4 text-sm sm:text-base">
                        Subscribe to our newsletter for the latest health tips,
                        news, and updates.
                    </p>
                    <div class="flex flex-col sm:flex-row">
                        <input
                            type="email"
                            placeholder="Enter your email"
                            class="flex-1 px-4 py-3 bg-gray-800 text-white rounded-t-lg sm:rounded-l-lg sm:rounded-t-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <button
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-b-lg sm:rounded-r-lg sm:rounded-b-none transition-all duration-200 hover:scale-105"
                        >
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="mt-12 sm:mt-16">
                <h5 class="text-lg sm:text-xl font-bold mb-4">We Accept</h5>
                <div
                    class="flex flex-wrap gap-4 sm:gap-6 items-center justify-center sm:justify-start"
                >
                    <img
                        :src="BkashLogo"
                        alt="bKash"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                    <img
                        :src="NagadLogo"
                        alt="Nagad"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                    <img
                        :src="RocketLogo"
                        alt="Rocket"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                    <img
                        :src="UpayLogo"
                        alt="Upay"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                    <img
                        :src="VisaLogo"
                        alt="Visa"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                    <img
                        :src="MasterCardLogo"
                        alt="MasterCard"
                        class="h-8 rounded object-contain hover:scale-110 transition-all duration-300"
                    />
                </div>
            </div>

            <!-- Copyright -->
            <div
                class="border-t border-gray-700 mt-8 sm:mt-10 pt-8 sm:pt-10 text-center"
            >
                <p class="text-gray-400 text-xs sm:text-sm">
                    &copy; {{ new Date().getFullYear() }} Xet Specialized
                    Hospital. All rights reserved. |
                    <a href="#" class="hover:text-white text-xs sm:text-sm"
                        >Privacy Policy</a
                    >
                    •
                    <a href="#" class="hover:text-white text-xs sm:text-sm"
                        >Terms of Service</a
                    >
                </p>
            </div>
        </div>
    </footer>
</template>
