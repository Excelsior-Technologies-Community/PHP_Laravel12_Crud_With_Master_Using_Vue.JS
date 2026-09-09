<script setup>
import { Link } from '@inertiajs/vue3'
import FlashMessage from '@/Components/FlashMessage.vue'

defineProps({
    activeTab: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <FlashMessage />
    <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <Link href="/product-dashboard" class="flex items-center gap-2 text-xl font-extrabold text-indigo-600 hover:text-indigo-700 transition">
                        <span class="text-2xl">🛍️</span>
                        <span>ProductMaster</span>
                    </Link>
                </div>

                <!-- Center Navigation Tabs -->
                <nav class="flex items-center gap-1 sm:gap-2">
                    <Link
                        href="/product-dashboard"
                        :class="[
                            'flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg text-sm font-semibold transition',
                            activeTab === 'dashboard' 
                                ? 'bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-xs' 
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                        ]"
                    >
                        <span>📊</span>
                        <span class="hidden sm:inline">Dashboard</span>
                    </Link>

                    <Link
                        href="/product"
                        :class="[
                            'flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg text-sm font-semibold transition',
                            activeTab === 'products' 
                                ? 'bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-xs' 
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                        ]"
                    >
                        <span>📦</span>
                        <span>Products</span>
                    </Link>

                    <Link
                        href="/category"
                        :class="[
                            'flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg text-sm font-semibold transition',
                            activeTab === 'categories' 
                                ? 'bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-xs' 
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                        ]"
                    >
                        <span>🏷️</span>
                        <span>Categories</span>
                    </Link>

                    <Link
                        href="/size"
                        :class="[
                            'flex items-center gap-2 px-3 sm:px-4 py-2 rounded-lg text-sm font-semibold transition',
                            activeTab === 'sizes' 
                                ? 'bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-xs' 
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                        ]"
                    >
                        <span>📏</span>
                        <span>Sizes</span>
                    </Link>
                </nav>

                <!-- Quick Action Buttons -->
                <div class="flex items-center gap-2">
                    <Link
                        href="/product/create"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-xs transition"
                    >
                        <span class="text-base font-bold">+</span>
                        <span class="hidden sm:inline">New Product</span>
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>
