<script setup>
import { useForm } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const props = defineProps({
    categories: {
        type: Array,
        default: () => []
    },
    sizes: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    name: '',
    details: '',
    price: '',
    category_id: '',
    size_id: '',
    stock_quantity: 0,
    low_stock_threshold: 5
})

const store = () => {
    form.post('/product')
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 pb-16">
        <!-- Unified Navbar -->
        <AppHeader activeTab="products" />

        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 border border-slate-200/80">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">📦</span>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">Create New Product</h1>
                            <p class="text-xs text-slate-500 mt-0.5">Add a new product with category, size, and inventory settings.</p>
                        </div>
                    </div>
                    <a
                        href="/product"
                        class="text-xs font-semibold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition"
                    >
                        ← Back to List
                    </a>
                </div>

                <form @submit.prevent="store" class="space-y-6">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                            :class="form.errors.name ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                            placeholder="e.g. Slim Fit Denim Jacket"
                            autofocus
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.name }}</p>
                    </div>

                    <!-- Details -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Product Details & Description <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            v-model="form.details"
                            rows="3"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                            :class="form.errors.details ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                            placeholder="Enter material, features, specifications..."
                        ></textarea>
                        <p v-if="form.errors.details" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.details }}</p>
                    </div>

                    <!-- Price & Categories & Sizes Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Price -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Price (₹) <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.price"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                :class="form.errors.price ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.price" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.price }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select
                                v-model="form.category_id"
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                :class="form.errors.category_id ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                            >
                                <option value="">Select category</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.category_id" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.category_id }}</p>
                        </div>

                        <!-- Size Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Size / Dimension
                            </label>
                            <select
                                v-model="form.size_id"
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                :class="form.errors.size_id ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                            >
                                <option value="">Free Size / None</option>
                                <option
                                    v-for="size in sizes"
                                    :key="size.id"
                                    :value="size.id"
                                >
                                    {{ size.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.size_id" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.size_id }}</p>
                        </div>
                    </div>

                    <!-- Inventory Management -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                        <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <span>📊</span>
                            <span>Stock & Inventory Settings</span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                    Stock Quantity <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.stock_quantity"
                                    type="number"
                                    min="0"
                                    class="w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                />
                                <p v-if="form.errors.stock_quantity" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.stock_quantity }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                    Low Stock Threshold <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.low_stock_threshold"
                                    type="number"
                                    min="0"
                                    class="w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                />
                                <p v-if="form.errors.low_stock_threshold" class="mt-1 text-xs text-rose-600 font-semibold">{{ form.errors.low_stock_threshold }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/product"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl shadow-md hover:shadow-lg transition disabled:opacity-50"
                        >
                            <span v-if="form.processing" class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                            <span>Save Product</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
