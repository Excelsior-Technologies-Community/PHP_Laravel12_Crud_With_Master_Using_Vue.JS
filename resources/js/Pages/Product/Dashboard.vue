<script setup>
import { computed } from 'vue'

const props = defineProps({
    statistics: {
        type: Object,
        required: true
    },

    categoryStats: {
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

    <div class="min-h-screen bg-gray-100 py-10 px-4">

        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        📊 Product Dashboard
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Product, category and inventory overview.
                    </p>
                </div>

                <a
                    href="/product"
                    class="btn-primary"
                >
                    ← Product List
                </a>

            </div>

            <!-- Main Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

                <!-- Products -->
                <div class="stat-card">

                    <div class="icon bg-blue-100 text-blue-600">
                        📦
                    </div>

                    <div>
                        <p class="stat-label">
                            Total Products
                        </p>

                        <p class="stat-number">
                            {{ statistics.total_products }}
                        </p>
                    </div>

                </div>

                <!-- Categories -->
                <div class="stat-card">

                    <div class="icon bg-purple-100 text-purple-600">
                        🏷️
                    </div>

                    <div>
                        <p class="stat-label">
                            Categories
                        </p>

                        <p class="stat-number">
                            {{ statistics.total_categories }}
                        </p>
                    </div>

                </div>

                <!-- Stock -->
                <div class="stat-card">

                    <div class="icon bg-green-100 text-green-600">
                        📦
                    </div>

                    <div>
                        <p class="stat-label">
                            Total Stock
                        </p>

                        <p class="stat-number">
                            {{ statistics.total_stock }}
                        </p>
                    </div>

                </div>

                <!-- Inventory Value -->
                <div class="stat-card">

                    <div class="icon bg-indigo-100 text-indigo-600">
                        💰
                    </div>

                    <div>
                        <p class="stat-label">
                            Inventory Value
                        </p>

                        <p class="stat-number text-xl">
                            ₹{{ inventoryValue }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- Stock Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                <div class="status-card border-green-200 bg-green-50">

                    <p class="text-green-700 font-medium">
                        In Stock
                    </p>

                    <p class="text-3xl font-bold text-green-800 mt-2">
                        {{ statistics.in_stock }}
                    </p>

                </div>

                <div class="status-card border-yellow-200 bg-yellow-50">

                    <p class="text-yellow-700 font-medium">
                        Low Stock
                    </p>

                    <p class="text-3xl font-bold text-yellow-800 mt-2">
                        {{ statistics.low_stock }}
                    </p>

                </div>

                <div class="status-card border-red-200 bg-red-50">

                    <p class="text-red-700 font-medium">
                        Out of Stock
                    </p>

                    <p class="text-3xl font-bold text-red-800 mt-2">
                        {{ statistics.out_of_stock }}
                    </p>

                </div>

            </div>

            <!-- Two columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Category statistics -->
                <div class="panel">

                    <div class="panel-header">
                        <h2>
                            🏷️ Products by Category
                        </h2>
                    </div>

                    <div
                        v-if="categoryStats.length === 0"
                        class="empty"
                    >
                        No category data available.
                    </div>

                    <div v-else>

                        <div
                            v-for="category in categoryStats"
                            :key="category.id"
                            class="category-row"
                        >

                            <div class="flex justify-between mb-2">

                                <span class="font-medium text-gray-700">
                                    {{ category.name }}
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ category.products_count }}
                                </span>

                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2">

                                <div
                                    class="bg-blue-600 h-2 rounded-full"
                                    :style="{
                                        width:
                                            Math.min(
                                                100,
                                                (
                                                    category.products_count /
                                                    Math.max(
                                                        ...categoryStats.map(
                                                            c =>
                                                                c.products_count
                                                        ),
                                                        1
                                                    )
                                                ) * 100
                                            ) + '%'
                                    }"
                                ></div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Low stock -->
                <div class="panel">

                    <div class="panel-header">
                        <h2>
                            ⚠️ Low Stock Products
                        </h2>
                    </div>

                    <div
                        v-if="lowStockProducts.length === 0"
                        class="empty text-green-600"
                    >
                        ✓ No low stock products.
                    </div>

                    <div v-else>

                        <div
                            v-for="product in lowStockProducts"
                            :key="product.id"
                            class="product-row"
                        >

                            <div>

                                <p class="font-semibold text-gray-800">
                                    {{ product.name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ product.category?.name ?? 'N/A' }}
                                </p>

                            </div>

                            <span class="stock-warning">
                                {{ product.stock_quantity }} left
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Out of stock -->
            <div class="panel mt-6">

                <div class="panel-header">
                    <h2>
                        🚫 Out of Stock Products
                    </h2>
                </div>

                <div
                    v-if="outOfStockProducts.length === 0"
                    class="empty text-green-600"
                >
                    ✓ No products are currently out of stock.
                </div>

                <div v-else class="overflow-x-auto">

                    <table class="w-full">

                        <thead>

                            <tr>
                                <th class="table-head">
                                    Product
                                </th>

                                <th class="table-head">
                                    Category
                                </th>

                                <th class="table-head">
                                    Price
                                </th>

                                <th class="table-head text-center">
                                    Stock
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr
                                v-for="product in outOfStockProducts"
                                :key="product.id"
                            >

                                <td class="table-cell font-semibold">
                                    {{ product.name }}
                                </td>

                                <td class="table-cell">
                                    {{ product.category?.name ?? 'N/A' }}
                                </td>

                                <td class="table-cell">
                                    ₹{{ Number(product.price).toFixed(2) }}
                                </td>

                                <td class="table-cell text-center">

                                    <span class="out-stock">
                                        0
                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>

.stat-card {
    @apply bg-white rounded-xl shadow-md p-5
           flex items-center gap-4;
}

.icon {
    @apply w-12 h-12 rounded-xl flex items-center
           justify-center text-2xl;
}

.stat-label {
    @apply text-sm text-gray-500;
}

.stat-number {
    @apply text-2xl font-bold text-gray-800 mt-1;
}

.status-card {
    @apply rounded-xl border p-5;
}

.panel {
    @apply bg-white rounded-xl shadow-md overflow-hidden;
}

.panel-header {
    @apply px-5 py-4 border-b bg-gray-50;
}

.panel-header h2 {
    @apply text-lg font-semibold text-gray-800;
}

.category-row {
    @apply px-5 py-4 border-b last:border-b-0;
}

.product-row {
    @apply px-5 py-4 border-b last:border-b-0
           flex items-center justify-between gap-4;
}

.stock-warning {
    @apply px-3 py-1 rounded-full bg-yellow-100
           text-yellow-700 text-sm font-semibold;
}

.out-stock {
    @apply px-3 py-1 rounded-full bg-red-100
           text-red-700 text-sm font-semibold;
}

.empty {
    @apply p-8 text-center text-gray-500;
}

.table-head {
    @apply px-5 py-3 text-left bg-gray-50
           border-b font-semibold text-gray-700;
}

.table-cell {
    @apply px-5 py-4 border-b text-gray-700;
}

.btn-primary {
    @apply bg-blue-600 text-white px-5 py-3 rounded-lg
           hover:bg-blue-700 transition;
}

</style>