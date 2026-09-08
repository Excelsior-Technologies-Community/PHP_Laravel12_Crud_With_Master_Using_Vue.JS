<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    product: {
        type: Object,
        required: true
    },

    categories: {
        type: Array,
        default: () => []
    }
})

const form = ref({
    name: props.product.name,
    details: props.product.details,
    price: props.product.price,
    category_id: props.product.category_id,
    stock_quantity: props.product.stock_quantity ?? 0,
    low_stock_threshold: props.product.low_stock_threshold ?? 5
})

const update = () => {
    router.put(
        `/product/${props.product.id}`,
        form.value
    )
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 py-10 px-4">

        <div class="max-w-2xl mx-auto">

            <div class="bg-white shadow-lg rounded-2xl p-8">

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">
                        ✏️ Edit Product
                    </h1>

                    <p class="text-gray-500 mt-2">
                        Update product and inventory information.
                    </p>
                </div>

                <!-- Product Name -->
                <div class="mb-5">
                    <label class="label">
                        Product Name
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        class="input"
                    />
                </div>

                <!-- Details -->
                <div class="mb-5">
                    <label class="label">
                        Product Details
                    </label>

                    <textarea
                        v-model="form.details"
                        rows="4"
                        class="input"
                    ></textarea>
                </div>

                <!-- Price -->
                <div class="mb-5">
                    <label class="label">
                        Price
                    </label>

                    <input
                        v-model="form.price"
                        type="number"
                        min="0"
                        step="0.01"
                        class="input"
                    />
                </div>

                <!-- Category -->
                <div class="mb-5">
                    <label class="label">
                        Category
                    </label>

                    <select
                        v-model="form.category_id"
                        class="input"
                    >
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <!-- Inventory -->
                <div class="border-t pt-6 mt-6">

                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        📦 Inventory Management
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="label">
                                Stock Quantity
                            </label>

                            <input
                                v-model="form.stock_quantity"
                                type="number"
                                min="0"
                                class="input"
                            />
                        </div>

                        <div>
                            <label class="label">
                                Low Stock Threshold
                            </label>

                            <input
                                v-model="form.low_stock_threshold"
                                type="number"
                                min="0"
                                class="input"
                            />
                        </div>

                    </div>

                    <!-- Current status -->
                    <div
                        class="mt-5 rounded-lg p-4"
                        :class="{
                            'bg-red-50 text-red-700':
                                form.stock_quantity <= 0,

                            'bg-yellow-50 text-yellow-700':
                                form.stock_quantity > 0 &&
                                form.stock_quantity <= form.low_stock_threshold,

                            'bg-green-50 text-green-700':
                                form.stock_quantity > form.low_stock_threshold
                        }"
                    >
                        <strong>Current Status:</strong>

                        <span v-if="form.stock_quantity <= 0">
                            Out of Stock
                        </span>

                        <span
                            v-else-if="
                                form.stock_quantity <=
                                form.low_stock_threshold
                            "
                        >
                            Low Stock
                        </span>

                        <span v-else>
                            In Stock
                        </span>
                    </div>

                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-8">

                    <a
                        href="/product"
                        class="btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        @click="update"
                        class="btn-primary"
                    >
                        Update Product
                    </button>

                </div>

            </div>

        </div>

    </div>
</template>

<style scoped>
.label {
    @apply block mb-2 font-medium text-gray-700;
}

.input {
    @apply w-full border border-gray-300 rounded-lg px-4 py-3
           focus:outline-none focus:ring-2 focus:ring-green-500;
}

.btn-primary {
    @apply bg-green-600 text-white px-6 py-3 rounded-lg
           hover:bg-green-700 transition;
}

.btn-secondary {
    @apply bg-gray-200 text-gray-700 px-6 py-3 rounded-lg
           hover:bg-gray-300 transition;
}
</style>