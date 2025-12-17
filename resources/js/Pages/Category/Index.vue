<script setup>
// Import Inertia router
import { router } from '@inertiajs/vue3'

// Receive categories list from controller
defineProps({
  categories: Array
})

// Function to delete category
const deleteCategory = (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
    router.delete(`/category/${id}`)
  }
}
</script>

<template>
  <!-- Page wrapper -->
  <div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-6">

      <!-- Page header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
          📂 Categories
        </h1>

        <!-- Create category button -->
        <a href="/category/create" class="btn-primary">
          + Add Category
        </a>
      </div>

      <!-- Show message if no data -->
      <div
        v-if="categories.length === 0"
        class="text-center text-gray-500 py-10"
      >
        No categories found.
      </div>

      <!-- Category table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-100">
            <tr>
              <th class="th">ID</th>
              <th class="th">Name</th>
              <th class="th text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="cat in categories"
              :key="cat.id"
              class="hover:bg-gray-50"
            >
              <td class="td">{{ cat.id }}</td>
              <td class="td">{{ cat.name }}</td>
              <td class="td text-center">
                <a
                  :href="`/category/${cat.id}/edit`"
                  class="action-edit"
                >
                  Edit
                </a>

                <button
                  class="action-delete"
                  @click="deleteCategory(cat.id)"
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
</template>

<style scoped>
/* Table header */
.th {
  @apply px-4 py-3 border-b font-semibold text-left;
}

/* Table cell */
.td {
  @apply px-4 py-3 border-b;
}

/* Primary button */
.btn-primary {
  @apply bg-blue-600 text-white px-5 py-2 rounded-lg
         hover:bg-blue-700 transition;
}

/* Edit link */
.action-edit {
  @apply text-blue-600 hover:underline mr-4;
}

/* Delete button */
.action-delete {
  @apply text-red-600 hover:underline;
}
</style>
