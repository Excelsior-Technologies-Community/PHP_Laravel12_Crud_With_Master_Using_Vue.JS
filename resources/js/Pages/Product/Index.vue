<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    products: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0,
        }),
    },

    categories: {
        type: Array,
        default: () => [],
    },

    filters: {
        type: Object,
        default: () => ({}),
    },
})

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const search = ref(props.filters.search ?? '')
const categoryId = ref(props.filters.category_id ?? '')
const stockStatus = ref(props.filters.stock_status ?? '')
const minPrice = ref(props.filters.min_price ?? '')
const maxPrice = ref(props.filters.max_price ?? '')

const sortBy = ref(
    props.filters.sort_by ?? 'created_at'
)

const sortOrder = ref(
    props.filters.sort_order ?? 'desc'
)

/*
|--------------------------------------------------------------------------
| Selected Products
|--------------------------------------------------------------------------
*/

const selectedProducts = ref([])

/*
|--------------------------------------------------------------------------
| Search Timer
|--------------------------------------------------------------------------
*/

let searchTimer = null

/*
|--------------------------------------------------------------------------
| Apply Filters
|--------------------------------------------------------------------------
*/

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
                max_price: maxPrice.value,
                sort_by: sortBy.value,
                sort_order: sortOrder.value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        )
    }, 300)
}

/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/

const changeSorting = () => {
    router.get(
        '/product',
        {
            search: search.value,
            category_id: categoryId.value,
            stock_status: stockStatus.value,
            min_price: minPrice.value,
            max_price: maxPrice.value,
            sort_by: sortBy.value,
            sort_order: sortOrder.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

/*
|--------------------------------------------------------------------------
| Reset Filters
|--------------------------------------------------------------------------
*/

const resetFilters = () => {
    search.value = ''
    categoryId.value = ''
    stockStatus.value = ''
    minPrice.value = ''
    maxPrice.value = ''
    sortBy.value = 'created_at'
    sortOrder.value = 'desc'

    selectedProducts.value = []

    router.get(
        '/product',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

/*
|--------------------------------------------------------------------------
| Delete Single Product
|--------------------------------------------------------------------------
*/

const deleteProduct = (id) => {
    if (
        confirm(
            'Are you sure you want to delete this product?'
        )
    ) {
        router.delete(`/product/${id}`, {
            preserveScroll: true,
        })
    }
}

/*
|--------------------------------------------------------------------------
| Select Product
|--------------------------------------------------------------------------
*/

const toggleProduct = (id) => {
    if (selectedProducts.value.includes(id)) {
        selectedProducts.value =
            selectedProducts.value.filter(
                item => item !== id
            )
    } else {
        selectedProducts.value.push(id)
    }
}

/*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/

const selectAll = () => {
    if (
        selectedProducts.value.length ===
        props.products.data.length
    ) {
        selectedProducts.value = []
    } else {
        selectedProducts.value =
            props.products.data.map(
                product => product.id
            )
    }
}

/*
|--------------------------------------------------------------------------
| Bulk Delete
|--------------------------------------------------------------------------
*/

const bulkDelete = () => {
    if (selectedProducts.value.length === 0) {
        alert(
            'Please select at least one product.'
        )

        return
    }

    if (
        confirm(
            `Delete ${selectedProducts.value.length} selected product(s)?`
        )
    ) {
        router.delete(
            '/products/bulk-delete',
            {
                data: {
                    ids: selectedProducts.value,
                },

                preserveScroll: true,

                onSuccess: () => {
                    selectedProducts.value = []
                },
            }
        )
    }
}

/*
|--------------------------------------------------------------------------
| Duplicate Product
|--------------------------------------------------------------------------
*/

const duplicateProduct = (id) => {
    if (
        confirm(
            'Do you want to duplicate this product?'
        )
    ) {
        router.post(
            `/product/${id}/duplicate`,
            {},
            {
                preserveScroll: true,
            }
        )
    }
}

/*
|--------------------------------------------------------------------------
| Export CSV
|--------------------------------------------------------------------------
*/

const exportProducts = () => {
    const params = new URLSearchParams()

    if (search.value) {
        params.append(
            'search',
            search.value
        )
    }

    if (categoryId.value) {
        params.append(
            'category_id',
            categoryId.value
        )
    }

    if (stockStatus.value) {
        params.append(
            'stock_status',
            stockStatus.value
        )
    }

    if (minPrice.value) {
        params.append(
            'min_price',
            minPrice.value
        )
    }

    if (maxPrice.value) {
        params.append(
            'max_price',
            maxPrice.value
        )
    }

    window.location.href =
        `/products/export?${params.toString()}`
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

const goToPage = (url) => {
    if (!url) {
        return
    }

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

/*
|--------------------------------------------------------------------------
| Stock Class
|--------------------------------------------------------------------------
*/

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

```
<div class="min-h-screen bg-gray-100 py-10 px-4">

    <div class="max-w-7xl mx-auto">

        <!-- Header -->

        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6"
        >

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    📦 Products
                </h1>

                <p class="text-gray-500 mt-1">
                    Manage products, search and inventory.
                </p>

            </div>


            <div class="flex flex-wrap gap-3">

                <a
                    href="/product-dashboard"
                    class="dashboard-btn"
                >
                    📊 Dashboard
                </a>

                <button
                    @click="exportProducts"
                    class="export-btn"
                >
                    📥 Export CSV
                </button>

                <a
                    href="/product/create"
                    class="btn-primary"
                >
                    + Add Product
                </a>

            </div>

        </div>


        <!-- SUCCESS MESSAGE -->

        <div
            v-if="page.props.flash?.success"
            class="mb-6 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm"
        >

            <div class="font-medium">
                ✅ {{ page.props.flash.success }}
            </div>

            <button
                type="button"
                @click="page.props.flash.success = null"
                class="text-green-700 hover:text-green-900 font-bold text-xl leading-none"
            >
                ×
            </button>

        </div>


        <!-- ERROR MESSAGE -->

        <div
            v-if="page.props.flash?.error"
            class="mb-6 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between shadow-sm"
        >

            <div class="font-medium">
                ❌ {{ page.props.flash.error }}
            </div>

            <button
                type="button"
                @click="page.props.flash.error = null"
                class="text-red-700 hover:text-red-900 font-bold text-xl leading-none"
            >
                ×
            </button>

        </div>


        <!-- Filters -->

        <div
            class="bg-white shadow-md rounded-xl p-5 mb-6"
        >

            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4"
            >

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


            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4"
            >

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


            <!-- Sorting -->

            <div
                class="mt-5 pt-5 border-t grid grid-cols-1 md:grid-cols-3 gap-4"
            >

                <div>

                    <label class="filter-label">
                        Sort By
                    </label>

                    <select
                        v-model="sortBy"
                        @change="changeSorting"
                        class="filter-input"
                    >

                        <option value="created_at">
                            Date Added
                        </option>

                        <option value="name">
                            Product Name
                        </option>

                        <option value="price">
                            Price
                        </option>

                        <option value="stock_quantity">
                            Stock Quantity
                        </option>

                    </select>

                </div>


                <div>

                    <label class="filter-label">
                        Sort Order
                    </label>

                    <select
                        v-model="sortOrder"
                        @change="changeSorting"
                        class="filter-input"
                    >

                        <option value="desc">
                            Descending
                        </option>

                        <option value="asc">
                            Ascending
                        </option>

                    </select>

                </div>

            </div>

        </div>


        <!-- Bulk Actions -->

        <div
            v-if="selectedProducts.length > 0"
            class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
        >

            <div class="text-red-700 font-medium">

                {{ selectedProducts.length }}
                product(s) selected

            </div>

            <button
                @click="bulkDelete"
                class="bulk-delete-btn"
            >
                🗑️ Delete Selected
            </button>

        </div>


        <!-- Product Count -->

        <div
            class="mb-4 text-sm text-gray-600 flex justify-between items-center"
        >

            <div>

                Showing

                <strong>
                    {{ products.from ?? 0 }}
                </strong>

                -

                <strong>
                    {{ products.to ?? 0 }}
                </strong>

                of

                <strong>
                    {{ products.total ?? 0 }}
                </strong>

                product(s)

            </div>

        </div>


        <!-- Empty -->

        <div
            v-if="products.data.length === 0"
            class="bg-white rounded-xl shadow-md text-center py-16"
        >

            <div class="text-5xl mb-4">
                📦
            </div>

            <h2
                class="text-xl font-semibold text-gray-700"
            >
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

                            <th class="th text-center">

                                <input
                                    type="checkbox"
                                    :checked="
                                        selectedProducts.length ===
                                        products.data.length &&
                                        products.data.length > 0
                                    "
                                    @change="selectAll"
                                />

                            </th>

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
                            v-for="product in products.data"
                            :key="product.id"
                            class="hover:bg-gray-50"
                        >

                            <!-- Checkbox -->

                            <td class="td text-center">

                                <input
                                    type="checkbox"
                                    :checked="
                                        selectedProducts.includes(
                                            product.id
                                        )
                                    "
                                    @change="
                                        toggleProduct(
                                            product.id
                                        )
                                    "
                                />

                            </td>


                            <!-- Product -->

                            <td class="td">

                                <div
                                    class="font-semibold text-gray-800"
                                >
                                    {{ product.name }}
                                </div>

                                <div
                                    class="text-sm text-gray-500 mt-1"
                                >
                                    {{ product.details }}
                                </div>

                            </td>


                            <!-- Price -->

                            <td class="td font-medium">

                                ₹{{
                                    Number(
                                        product.price
                                    ).toFixed(2)
                                }}

                            </td>


                            <!-- Category -->

                            <td class="td">

                                <span
                                    class="category-badge"
                                >
                                    {{
                                        product.category?.name ??
                                        'N/A'
                                    }}
                                </span>

                            </td>


                            <!-- Stock -->

                            <td class="td text-center">

                                <span
                                    class="font-semibold"
                                >
                                    {{
                                        product.stock_quantity
                                    }}
                                </span>

                                <div
                                    class="text-xs text-gray-500"
                                >
                                    Alert at
                                    {{
                                        product.low_stock_threshold
                                    }}
                                </div>

                            </td>


                            <!-- Status -->

                            <td class="td text-center">

                                <span
                                    class="status-badge"
                                    :class="
                                        stockClass(product)
                                    "
                                >
                                    {{
                                        product.stock_status
                                    }}
                                </span>

                            </td>


                            <!-- Actions -->

                            <td
                                class="td text-center whitespace-nowrap"
                            >

                                <a
                                    :href="`/product/${product.id}/edit`"
                                    class="action-edit"
                                >
                                    Edit
                                </a>


                                <button
                                    @click="
                                        duplicateProduct(
                                            product.id
                                        )
                                    "
                                    class="action-copy"
                                >
                                    Copy
                                </button>


                                <button
                                    @click="
                                        deleteProduct(
                                            product.id
                                        )
                                    "
                                    class="action-delete"
                                >
                                    Delete
                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- Pagination -->

            <div
                class="px-5 py-4 border-t flex flex-wrap gap-2 justify-center"
            >

                <button
                    v-for="(link, index) in products.links"
                    :key="index"
                    :disabled="!link.url"
                    @click="goToPage(link.url)"
                    class="pagination-btn"
                    :class="{
                        'pagination-active': link.active,
                        'pagination-disabled': !link.url
                    }"
                    v-html="link.label"
                />

            </div>

        </div>

    </div>

</div>
```

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

.export-btn {
    @apply bg-green-600 text-white px-5 py-3 rounded-lg
           hover:bg-green-700 transition;
}

.bulk-delete-btn {
    @apply bg-red-600 text-white px-5 py-2 rounded-lg
           hover:bg-red-700 transition;
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
    @apply text-blue-600 hover:underline mr-3;
}

.action-copy {
    @apply text-purple-600 hover:underline mr-3;
}

.action-delete {
    @apply text-red-600 hover:underline;
}

.pagination-btn {
    @apply px-3 py-2 border rounded-lg text-sm
           bg-white hover:bg-gray-100;
}

.pagination-active {
    @apply bg-blue-600 text-white border-blue-600;
}

.pagination-disabled {
    @apply opacity-40 cursor-not-allowed;
}

</style>
