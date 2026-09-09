<script setup>
import { computed } from 'vue'
import AppHeader from '@/Components/AppHeader.vue'

const props = defineProps({
    statistics: {
        type: Object,
        required: true
    },
    categoryStats: {
        type: Array,
        default: () => []
    },
    sizeStats: {
        type: Array,
        default: () => []
    },
    lowStockProducts: {
        type: Array,
        default: () => []
    },
    outOfStockProducts: {
        type: Array,
        default: () => []
    }
})

const inventoryValue = computed(() => {
    return Number(
        props.statistics.total_inventory_value ?? 0
    ).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })
})
</script>

<template>
    <div class="min-h-screen bg-slate-50 pb-16">
        <!-- Unified Navbar -->
        <AppHeader activeTab="dashboard" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                        📊 Product Analytics & Dashboard
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Real-time overview of products, categories, size masters, and inventory health.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="/product"
                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition"
                    >
                        📦 Product List
                    </a>

                    <a
                        href="/size"
                        class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl shadow-xs transition"
                    >
                        📏 Size Master
                    </a>
                </div>
            </div>

            <!-- Main Top Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Total Products -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">
                        📦
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Products</p>
                        <p class="text-2xl font-black text-slate-800">{{ statistics.total_products }}</p>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-bold">
                        🏷️
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Categories</p>
                        <p class="text-2xl font-black text-slate-800">{{ statistics.total_categories }}</p>
                    </div>
                </div>

                <!-- Total Sizes -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                        📏
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sizes</p>
                        <p class="text-2xl font-black text-slate-800">{{ statistics.total_sizes ?? 0 }}</p>
                    </div>
                </div>

                <!-- Total Stock -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                        📊
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Units</p>
                        <p class="text-2xl font-black text-slate-800">{{ statistics.total_stock }}</p>
                    </div>
                </div>

                <!-- Inventory Value -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        💰
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Inventory Value</p>
                        <p class="text-xl font-black text-slate-800">₹{{ inventoryValue }}</p>
                    </div>
                </div>
            </div>

            <!-- Stock Status Health Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="p-5 rounded-2xl border border-emerald-200 bg-emerald-50/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-800">In Stock Products</p>
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="text-3xl font-black text-emerald-900 mt-2">{{ statistics.in_stock }}</p>
                    <p class="text-xs text-emerald-700 mt-1">Healthy inventory levels</p>
                </div>

                <div class="p-5 rounded-2xl border border-amber-200 bg-amber-50/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Low Stock Alert</p>
                        <span class="w-3 h-3 rounded-full bg-amber-500 animate-ping"></span>
                    </div>
                    <p class="text-3xl font-black text-amber-900 mt-2">{{ statistics.low_stock }}</p>
                    <p class="text-xs text-amber-700 mt-1">Below threshold, re-order soon</p>
                </div>

                <div class="p-5 rounded-2xl border border-rose-200 bg-rose-50/60 shadow-xs">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-rose-800">Out of Stock</p>
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    </div>
                    <p class="text-3xl font-black text-rose-900 mt-2">{{ statistics.out_of_stock }}</p>
                    <p class="text-xs text-rose-700 mt-1">Zero units available</p>
                </div>
            </div>

            <!-- Distribution & Alerts Breakdown (2x2 Grid) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Products by Category -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span>🏷️</span>
                            <span>Products by Category</span>
                        </h2>
                        <a href="/category" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            View All →
                        </a>
                    </div>

                    <div v-if="categoryStats.length === 0" class="py-8 text-center text-sm text-slate-400">
                        No category data available.
                    </div>

                    <div v-else class="space-y-3.5 max-h-72 overflow-y-auto pr-1">
                        <div
                            v-for="category in categoryStats"
                            :key="category.id"
                            class="group"
                        >
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-700">{{ category.name }}</span>
                                <span class="text-slate-900 font-bold">{{ category.products_count }} {{ category.products_count === 1 ? 'item' : 'items' }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div
                                    class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                    :style="{
                                        width: Math.min(100, (category.products_count / Math.max(...categoryStats.map(c => c.products_count), 1)) * 100) + '%'
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products by Size -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span>📏</span>
                            <span>Products by Size Master</span>
                        </h2>
                        <a href="/size" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            Manage Sizes →
                        </a>
                    </div>

                    <div v-if="sizeStats.length === 0" class="py-8 text-center text-sm text-slate-400">
                        No size data available.
                    </div>

                    <div v-else class="space-y-3.5 max-h-72 overflow-y-auto pr-1">
                        <div
                            v-for="size in sizeStats"
                            :key="size.id"
                            class="group"
                        >
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-700 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                    {{ size.name }}
                                </span>
                                <span class="text-slate-900 font-bold">{{ size.products_count }} {{ size.products_count === 1 ? 'product' : 'products' }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div
                                    class="bg-purple-600 h-2 rounded-full transition-all duration-500"
                                    :style="{
                                        width: Math.min(100, (size.products_count / Math.max(...sizeStats.map(s => s.products_count), 1)) * 100) + '%'
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock & Out of Stock Products Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Low Stock Products -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span>⚠️</span>
                            <span>Low Stock Products</span>
                        </h2>
                        <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                            {{ lowStockProducts.length }} items
                        </span>
                    </div>

                    <div v-if="lowStockProducts.length === 0" class="py-8 text-center text-xs font-medium text-emerald-600 bg-emerald-50/50 rounded-xl">
                        ✓ All stock levels are currently above alert threshold.
                    </div>

                    <div v-else class="divide-y divide-slate-100">
                        <div
                            v-for="product in lowStockProducts"
                            :key="product.id"
                            class="py-3 flex items-center justify-between gap-3"
                        >
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ product.name }}</p>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                    <span>{{ product.category?.name ?? 'No Category' }}</span>
                                    <span v-if="product.size" class="text-indigo-600 font-medium">• Size: {{ product.size.name }}</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-800 whitespace-nowrap">
                                {{ product.stock_quantity }} units left
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock Products -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span>🚫</span>
                            <span>Out of Stock Products</span>
                        </h2>
                        <span class="text-xs font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">
                            {{ outOfStockProducts.length }} items
                        </span>
                    </div>

                    <div v-if="outOfStockProducts.length === 0" class="py-8 text-center text-xs font-medium text-emerald-600 bg-emerald-50/50 rounded-xl">
                        ✓ No products are currently out of stock.
                    </div>

                    <div v-else class="divide-y divide-slate-100">
                        <div
                            v-for="product in outOfStockProducts"
                            :key="product.id"
                            class="py-3 flex items-center justify-between gap-3"
                        >
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ product.name }}</p>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                    <span>{{ product.category?.name ?? 'No Category' }}</span>
                                    <span v-if="product.size" class="text-indigo-600 font-medium">• Size: {{ product.size.name }}</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-100 text-rose-800 whitespace-nowrap">
                                Out of Stock
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
