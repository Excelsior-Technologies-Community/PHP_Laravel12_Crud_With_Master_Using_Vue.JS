<script setup>
// Import Inertia router for delete action
import { router } from '@inertiajs/vue3'

// Receive products list from controller
defineProps({
  products: Array
})

// Delete product from database
const deleteProduct = (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    router.delete(`/product/${id}`)
  }
}
</script>

<template>
  <!-- Page wrapper -->
  <div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6">

      <!-- Page header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
          📦 Products
        </h1>

        <!-- Create product button -->
        <a href="/product/create" class="btn-primary">
          + Add Product
        </a>
      </div>

      <!-- Empty state -->
      <div
        v-if="products.length === 0"
        class="text-center text-gray-500 py-10"
      >
        No products found.
      </div>

      <!-- Product table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-100">
            <tr>
              <th class="th">Name</th>
              <th class="th">Price</th>
              <th class="th">Category</th>
              <th class="th text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="p in products"
              :key="p.id"
              class="hover:bg-gray-50"
            >
              <td class="td font-medium">{{ p.name }}</td>
              <td class="td">₹{{ p.price }}</td>
              <td class="td">{{ p.category.name }}</td>
              <td class="td text-center">
                <!-- Edit link -->
                <a
                  :href="`/product/${p.id}/edit`"
                  class="action-edit"
                >
                  Edit
                </a>

                <!-- Delete button -->
                <button
                  class="action-delete"
                  @click="deleteProduct(p.id)"
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
  @apply px-4 py-3 border-b text-left font-semibold;
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
