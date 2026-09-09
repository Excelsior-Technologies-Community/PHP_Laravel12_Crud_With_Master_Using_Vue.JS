<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const props = defineProps({

    categories: {
        type: Object,

        default: () => ({
            data: [],
            links: [],
            current_page: 1,
            last_page: 1,
            total: 0,
        }),
    },

    filters: {
        type: Object,

        default: () => ({
            search: '',
        }),
    },

})


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const search = ref(
    props.filters.search ?? ''
)


let searchTimer = null


/*
|--------------------------------------------------------------------------
| Search Categories
|--------------------------------------------------------------------------
*/

const searchCategories = () => {

    clearTimeout(searchTimer)

    searchTimer = setTimeout(() => {

        router.get(
            '/category',
            {
                search: search.value,
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
| Reset Search
|--------------------------------------------------------------------------
*/

const resetSearch = () => {

    search.value = ''

    router.get(
        '/category',
        {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    )

}


/*
|--------------------------------------------------------------------------
| Delete Category
|--------------------------------------------------------------------------
*/

const deleteCategory = (id) => {

    if (
        confirm(
            'Are you sure you want to delete this category?'
        )
    ) {

        router.delete(
            `/category/${id}`,
            {
                preserveScroll: true,
            }
        )

    }

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

</script>


<template>
    <div class="min-h-screen bg-gray-100 pb-12">
        <!-- Unified Navbar -->
        <AppHeader activeTab="categories" />

        <div class="max-w-6xl mx-auto px-4">

            <!-- Header -->

            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6"
            >

                <div>

                    <h1
                        class="text-3xl font-bold text-gray-800"
                    >
                        📂 Categories
                    </h1>

                    <p
                        class="text-gray-500 mt-1"
                    >
                        Manage product categories.
                    </p>

                </div>


                <div
                    class="flex gap-3"
                >

                    <a
                        href="/product"
                        class="product-btn"
                    >
                        📦 Products
                    </a>

                    <a
                        href="/category/create"
                        class="btn-primary"
                    >
                        + Add Category
                    </a>

                </div>

            </div>


            <!-- Search -->

            <div
                class="bg-white shadow-md rounded-xl p-5 mb-6"
            >

                <div
                    class="flex flex-col md:flex-row gap-3"
                >

                    <input
                        v-model="search"
                        @input="searchCategories"
                        type="text"
                        placeholder="Search category..."
                        class="search-input"
                    />


                    <button
                        @click="resetSearch"
                        class="reset-btn"
                    >
                        Reset
                    </button>

                </div>

            </div>


            <!-- Statistics -->

            <div
                class="mb-4 text-sm text-gray-600"
            >

                Total Categories:

                <strong>
                    {{ categories.total ?? 0 }}
                </strong>

            </div>


            <!-- Empty -->

            <div
                v-if="categories.data.length === 0"
                class="bg-white shadow-md rounded-xl text-center py-16"
            >

                <div
                    class="text-5xl mb-4"
                >
                    📂
                </div>

                <h2
                    class="text-xl font-semibold text-gray-700"
                >
                    No categories found
                </h2>

                <p
                    class="text-gray-500 mt-2"
                >
                    Create a category or change your search.
                </p>

            </div>


            <!-- Table -->

            <div
                v-else
                class="bg-white shadow-md rounded-xl overflow-hidden"
            >

                <div
                    class="overflow-x-auto"
                >

                    <table
                        class="w-full"
                    >

                        <thead
                            class="bg-gray-100"
                        >

                            <tr>

                                <th class="th">
                                    ID
                                </th>

                                <th class="th">
                                    Category Name
                                </th>

                                <th
                                    class="th text-center"
                                >
                                    Products
                                </th>

                                <th
                                    class="th text-center"
                                >
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="category in categories.data"
                                :key="category.id"
                                class="hover:bg-gray-50"
                            >

                                <td class="td">
                                    {{ category.id }}
                                </td>


                                <td
                                    class="td font-semibold"
                                >
                                    {{ category.name }}
                                </td>


                                <!-- Product Count -->

                                <td
                                    class="td text-center"
                                >

                                    <span
                                        class="product-count"
                                    >
                                        {{
                                            category.products_count
                                        }}

                                    </span>

                                </td>


                                <!-- Actions -->

                                <td
                                    class="td text-center whitespace-nowrap"
                                >

                                    <a
                                        :href="`/category/${category.id}/edit`"
                                        class="action-edit"
                                    >
                                        Edit
                                    </a>


                                    <button
                                        @click="
                                            deleteCategory(
                                                category.id
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
                        v-for="(link, index) in categories.links"
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

</template>


<style scoped>

.search-input {
    @apply flex-1 border border-gray-300 rounded-lg px-4 py-3
           focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.reset-btn {
    @apply px-5 py-3 bg-gray-200 text-gray-700 rounded-lg
           hover:bg-gray-300 transition;
}

.btn-primary {
    @apply bg-blue-600 text-white px-5 py-3 rounded-lg
           hover:bg-blue-700 transition;
}

.product-btn {
    @apply bg-purple-600 text-white px-5 py-3 rounded-lg
           hover:bg-purple-700 transition;
}

.th {
    @apply px-5 py-4 border-b text-left font-semibold text-gray-700;
}

.td {
    @apply px-5 py-4 border-b text-gray-700;
}

.product-count {
    @apply inline-flex items-center justify-center
           min-w-[35px] px-3 py-1 rounded-full
           bg-blue-100 text-blue-700 font-semibold;
}

.action-edit {
    @apply text-blue-600 hover:underline mr-4;
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