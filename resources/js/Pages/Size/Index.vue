<script setup>
import { ref, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const props = defineProps({
    sizes: {
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
    filters: {
        type: Object,
        default: () => ({
            search: '',
            sort_by: 'id',
            sort_order: 'asc',
        }),
    },
    totalSizes: {
        type: Number,
        default: 0,
    },
    totalLinkedProducts: {
        type: Number,
        default: 0,
    },
})

// Search & filter state
const search = ref(props.filters.search ?? '')
const sortBy = ref(props.filters.sort_by ?? 'id')
const sortOrder = ref(props.filters.sort_order ?? 'asc')
let searchTimer = null

const performSearch = () => {
    clearTimeout(searchTimer)
    searchTimer = setTimeout(() => {
        router.get(
            '/size',
            {
                search: search.value,
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

const resetFilters = () => {
    search.value = ''
    sortBy.value = 'id'
    sortOrder.value = 'asc'
    router.get(
        '/size',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}

// Modal State for Real-Time Quick Add / Edit
const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = useForm({
    name: '',
})

const openAddModal = () => {
    isEditing.value = false
    editingId.value = null
    form.reset()
    form.clearErrors()
    showModal.value = true
}

const openEditModal = (size) => {
    isEditing.value = true
    editingId.value = size.id
    form.clearErrors()
    form.name = size.name
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.reset()
    form.clearErrors()
}

const submitForm = () => {
    if (isEditing.value) {
        form.put('/size/' + editingId.value, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
            },
        })
    } else {
        form.post('/size', {
            preserveScroll: true,
            onSuccess: () => {
                closeModal()
            },
        })
    }
}

// Delete Action
const deleteSize = (size) => {
    const message = size.products_count > 0
        ? 'Size "' + size.name + '" is used by ' + size.products_count + ' product(s). Are you sure you want to delete it?'
        : 'Are you sure you want to delete size "' + size.name + '"?'

    if (confirm(message)) {
        router.delete('/size/' + size.id, {
            preserveScroll: true,
        })
    }
}

const goToPage = (url) => {
    if (!url) return
    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 pb-16">
        <!-- Unified Navbar -->
        <AppHeader activeTab="sizes" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">📏</span>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Size Master Module
                            </h1>
                            <p class="text-sm text-slate-500 mt-0.5">
                                Manage clothing, footwear, and item dimensions with real-time updates.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        @click="openAddModal"
                        type="button"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200"
                    >
                        <span class="text-lg leading-none">+</span>
                        <span>Add New Size</span>
                    </button>

                    <a
                        href="/product"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-semibold text-sm rounded-xl shadow-xs hover:bg-slate-50 transition"
                    >
                        <span>📦 Products</span>
                    </a>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                        📏
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Sizes</p>
                        <p class="text-2xl font-black text-slate-800">{{ totalSizes }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        🔗
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Products with Size</p>
                        <p class="text-2xl font-black text-slate-800">{{ totalLinkedProducts }}</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl font-bold">
                        ⚡
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Real-Time Search</p>
                        <p class="text-sm font-semibold text-violet-700 mt-1">300ms Live Debounced</p>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Card -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs mb-6">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <div class="relative w-full sm:max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            🔍
                        </span>
                        <input
                            v-model="search"
                            @input="performSearch"
                            type="text"
                            placeholder="Search sizes (e.g. XL, 32, Small)..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        />
                        <button
                            v-if="search"
                            @click="() => { search = ''; performSearch() }"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600"
                        >
                            ✕
                        </button>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <select
                            v-model="sortBy"
                            @change="performSearch"
                            class="px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="id">Sort by ID</option>
                            <option value="name">Sort by Name (A-Z)</option>
                            <option value="products_count">Sort by Products Count</option>
                        </select>

                        <button
                            @click="resetFilters"
                            type="button"
                            class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table / Content Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Empty State -->
                <div v-if="sizes.data.length === 0" class="py-16 px-4 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl">
                        📏
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">No sizes found</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1 mb-6">
                        No size matches your current search filters. Create a new size to get started.
                    </p>
                    <button
                        @click="openAddModal"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition"
                    >
                        + Create First Size
                    </button>
                </div>

                <!-- Sizes Table -->
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-xs uppercase tracking-wider font-semibold text-slate-500">
                                <th class="py-3.5 px-6">ID</th>
                                <th class="py-3.5 px-6">Size Name / Code</th>
                                <th class="py-3.5 px-6">Linked Products</th>
                                <th class="py-3.5 px-6">Status Badge</th>
                                <th class="py-3.5 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <tr
                                v-for="size in sizes.data"
                                :key="size.id"
                                class="hover:bg-indigo-50/30 transition-colors duration-150 group"
                            >
                                <!-- ID -->
                                <td class="py-4 px-6 text-slate-400 font-mono text-xs">
                                    #{{ size.id }}
                                </td>

                                <!-- Size Name -->
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 font-black text-xs border border-indigo-100">
                                            {{ size.name.substring(0, 2).toUpperCase() }}
                                        </span>
                                        <span>{{ size.name }}</span>
                                    </div>
                                </td>

                                <!-- Linked Products -->
                                <td class="py-4 px-6">
                                    <span
                                        v-if="size.products_count > 0"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                    >
                                        📦 {{ size.products_count }} {{ size.products_count === 1 ? 'Product' : 'Products' }}
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500"
                                    >
                                        0 Products
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                        Active Master
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            @click="openEditModal(size)"
                                            type="button"
                                            class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition text-xs font-semibold"
                                            title="Quick Edit"
                                        >
                                            ✏️ Edit
                                        </button>

                                        <button
                                            @click="deleteSize(size)"
                                            type="button"
                                            class="p-2 text-rose-600 hover:text-rose-900 hover:bg-rose-50 rounded-lg transition text-xs font-semibold"
                                            title="Delete Size"
                                        >
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modern Pagination -->
                <div
                    v-if="sizes.total > 0"
                    class="py-4 px-6 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4"
                >
                    <p class="text-xs text-slate-500 font-medium">
                        Showing <span class="font-bold text-slate-800">{{ sizes.from ?? 0 }}</span> to
                        <span class="font-bold text-slate-800">{{ sizes.to ?? 0 }}</span> of
                        <span class="font-bold text-slate-800">{{ sizes.total }}</span> sizes
                    </p>

                    <div class="flex items-center gap-1.5 flex-wrap justify-center">
                        <button
                            v-for="(link, idx) in sizes.links"
                            :key="idx"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition"
                            :class="[
                                link.active
                                    ? 'bg-indigo-600 text-white shadow-xs font-bold'
                                    : link.url
                                        ? 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100'
                                        : 'bg-slate-100 text-slate-400 cursor-not-allowed opacity-60'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Modal (Create & Edit) -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4"
                @click.self="closeModal"
            >
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-100 relative">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">{{ isEditing ? '✏️' : '✨' }}</span>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ isEditing ? 'Edit Size' : 'Create New Size' }}
                            </h3>
                        </div>
                        <button
                            @click="closeModal"
                            type="button"
                            class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Size Name / Code <span class="text-rose-500">*</span>
                            </label>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="e.g. M - Medium, 34 (Waist), Free Size"
                                class="w-full px-4 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                :class="form.errors.name ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                                autofocus
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600 font-semibold">
                                {{ form.errors.name }}
                            </p>
                            <p class="mt-1.5 text-xs text-slate-400">
                                Examples: XS, Small, M, Large, XL, 28, 30, 32, Free Size
                            </p>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button
                                @click="closeModal"
                                type="button"
                                class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl shadow-md hover:shadow-lg transition disabled:opacity-50"
                            >
                                <span v-if="form.processing" class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                                <span>{{ isEditing ? 'Save Changes' : 'Create Size' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>
