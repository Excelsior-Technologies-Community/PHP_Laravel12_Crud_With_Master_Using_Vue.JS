<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    products: {
        type: Array,
        default: () => []
    },

    categories: {
        type: Array,
        default: () => []
    },

    filters: {
        type: Object,
        default: () => ({})
    }
})

const search = ref(props.filters.search ?? '')
const categoryId = ref(props.filters.category_id ?? '')
const stockStatus = ref(props.filters.stock_status ?? '')
const minPrice = ref(props.filters.min_price ?? '')
const maxPrice = ref(props.filters.max_price ?? '')

let searchTimer = null

const applyFilters = () => {

    clearTimeout(searchTimer)

    searchTimer = setTimeout(() => {

        router.get(
            '/product',
            {
                search: search.value,
                category_id: categoryId.value,
                stock_status: stockStatus.value,
                min_price: minPrice.value,
                max_price: maxPrice.value
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true
            }
        )

    }, 300)
}

const resetFilters = () => {

    search.value = ''
    categoryId.value = ''
    stockStatus.value = ''
    minPrice.value = ''
    maxPrice.value = ''

    router.get(
        '/product',
        {},
        {
            preserveState: true,
            preserveScroll: true
        }
    )
}

const deleteProduct = (id) => {

    if (
        confirm(
            'Are you sure you want to delete this product?'
        )
    ) {
        router.delete(`/product/${id}`)
    }
}

const stockClass = (product) => {

    if (product.stock_quantity <= 0) {
        return 'bg-red-100 text-red-700'
    }

    if (
        product.stock_quantity <=
        product.low_stock_threshold
    ) {
        return 'bg-yellow-100 text-yellow-700'
    }

    return 'bg-green-100 text-green-700'
}
</script>

<template>

    <div class="min-h-screen bg-gray-100 py-10 px-4">

        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        📦 Products
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Manage products, search and inventory.
                    </p>
                </div>

                <div class="flex gap-3">

                    <a
                        href="/product-dashboard"
                        class="dashboard-btn"
                    >
                        📊 Dashboard
                    </a>

                    <a
                        href="/product/create"
                        class="btn-primary"
                    >
                        + Add Product
                    </a>

                </div>

            </div>

            <!-- Filters -->
            <div class="bg-white shadow-md rounded-xl p-5 mb-6">

                <div class="flex items-center justify-between mb-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        🔎 Search & Filters
                    </h2>

                    <button
                        @click="resetFilters"
                        class="text-sm text-gray-500 hover:text-red-600"
                    >
                        Reset Filters
                    </button>

                </div>

                <!-- Updated Filter Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">

                    <!-- Search -->
                    <div class="lg:col-span-2">

                        <label class="filter-label">
                            Search
                        </label>

                        <input
                            v-model="search"
                            @input="applyFilters"
                            type="text"
                            class="filter-input"
                            placeholder="Search product name or details..."
                        />

                    </div>

                    <!-- Category -->
                    <div>

                        <label class="filter-label">
                            Category
                        </label>

                        <select
                            v-model="categoryId"
                            @change="applyFilters"
                            class="filter-input"
                        >

                            <option value="">
                                All Categories
                            </option>

                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>

                        </select>

                    </div>

                    <!-- Stock -->
                    <div>

                        <label class="filter-label">
                            Stock Status
                        </label>

                        <select
                            v-model="stockStatus"
                            @change="applyFilters"
                            class="filter-input"
                        >

                            <option value="">
                                All Stock
                            </option>

                            <option value="in_stock">
                                In Stock
                            </option>

                            <option value="low_stock">
                                Low Stock
                            </option>

                            <option value="out_of_stock">
                                Out of Stock
                            </option>

                        </select>

                    </div>

                    <!-- Min Price -->
                    <div>

                        <label class="filter-label">
                            Min Price
                        </label>

                        <input
                            v-model="minPrice"
                            @input="applyFilters"
                            type="number"
                            min="0"
                            class="filter-input"
                            placeholder="₹ Min"
                        />

                    </div>

                    <!-- Max Price -->
                    <div>

                        <label class="filter-label">
                            Max Price
                        </label>

                        <input
                            v-model="maxPrice"
                            @input="applyFilters"
                            type="number"
                            min="0"
                            class="filter-input"
                            placeholder="₹ Max"
                        />

                    </div>

                </div>

            </div>

            <!-- Product count -->
            <div class="mb-4 text-sm text-gray-600">

                Showing
                <strong>{{ products.length }}</strong>
                product(s)

            </div>

            <!-- Empty -->
            <div
                v-if="products.length === 0"
                class="bg-white rounded-xl shadow-md text-center py-16"
            >

                <div class="text-5xl mb-4">
                    📦
                </div>

                <h2 class="text-xl font-semibold text-gray-700">
                    No products found
                </h2>

                <p class="text-gray-500 mt-2">
                    Try changing your search or filters.
                </p>

            </div>

            <!-- Table -->
            <div
                v-else
                class="bg-white shadow-md rounded-xl overflow-hidden"
            >

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="th">
                                    Product
                                </th>

                                <th class="th">
                                    Price
                                </th>

                                <th class="th">
                                    Category
                                </th>

                                <th class="th text-center">
                                    Stock
                                </th>

                                <th class="th text-center">
                                    Status
                                </th>

                                <th class="th text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="hover:bg-gray-50"
                            >

                                <td class="td">

                                    <div class="font-semibold text-gray-800">
                                        {{ product.name }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ product.details }}
                                    </div>

                                </td>

                                <td class="td font-medium">
                                    ₹{{ Number(product.price).toFixed(2) }}
                                </td>

                                <td class="td">

                                    <span class="category-badge">
                                        {{ product.category?.name ?? 'N/A' }}
                                    </span>

                                </td>

                                <td class="td text-center">

                                    <span class="font-semibold">
                                        {{ product.stock_quantity }}
                                    </span>

                                    <div class="text-xs text-gray-500">
                                        Alert at {{ product.low_stock_threshold }}
                                    </div>

                                </td>

                                <td class="td text-center">

                                    <span
                                        class="status-badge"
                                        :class="stockClass(product)"
                                    >

                                        {{ product.stock_status }}

                                    </span>

                                </td>

                                <td class="td text-center whitespace-nowrap">

                                    <a
                                        :href="`/product/${product.id}/edit`"
                                        class="action-edit"
                                    >
                                        Edit
                                    </a>

                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="action-delete"
                                    >
                                        Delete
                                    </button>

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

.filter-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}

.filter-input {
    @apply w-full border border-gray-300 rounded-lg px-3 py-2
           focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.th {
    @apply px-5 py-4 border-b text-left font-semibold text-gray-700;
}

.td {
    @apply px-5 py-4 border-b text-gray-700;
}

.btn-primary {
    @apply bg-blue-600 text-white px-5 py-3 rounded-lg
           hover:bg-blue-700 transition;
}

.dashboard-btn {
    @apply bg-purple-600 text-white px-5 py-3 rounded-lg
           hover:bg-purple-700 transition;
}

.category-badge {
    @apply inline-flex px-3 py-1 rounded-full
           bg-blue-100 text-blue-700 text-sm;
}

.status-badge {
    @apply inline-flex px-3 py-1 rounded-full
           text-sm font-semibold;
}

.action-edit {
    @apply text-blue-600 hover:underline mr-4;
}

.action-delete {
    @apply text-red-600 hover:underline;
}

</style>