<script setup>
// Import ref for reactive form
import { ref } from 'vue'
// Import Inertia router
import { router } from '@inertiajs/vue3'

// Receive product and categories from controller
const props = defineProps({
  product: Object,
  categories: Array
})

// Initialize form with existing product data
const form = ref({
  name: props.product.name,
  details: props.product.details,
  price: props.product.price,
  category_id: props.product.category_id
})

// Update product in database
const update = () => {
  router.put(`/product/${props.product.id}`, form.value)
}
</script>

<template>
  <!-- Page wrapper -->
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <!-- Card container -->
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-6">

      <!-- Page title -->
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        ✏️ Edit Product
      </h2>

      <!-- Product Name -->
      <div class="mb-4">
        <label class="label">Product Name</label>
        <input v-model="form.name" class="input" />
      </div>

      <!-- Product Details -->
      <div class="mb-4">
        <label class="label">Details</label>
        <textarea v-model="form.details" class="input"></textarea>
      </div>

      <!-- Product Price -->
      <div class="mb-4">
        <label class="label">Price</label>
        <input v-model="form.price" type="number" class="input" />
      </div>

      <!-- Category Dropdown -->
      <div class="mb-6">
        <label class="label">Category</label>
        <select v-model="form.category_id" class="input">
          <option v-for="c in categories" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>
      </div>

      <!-- Action buttons -->
      <div class="flex justify-end gap-3">
        <a href="/product" class="btn-secondary">
          Cancel
        </a>

        <button class="btn-primary" @click="update">
          Update Product
        </button>
      </div>

    </div>
  </div>
</template>

<style scoped>
/* Label styling */
.label {
  @apply block mb-1 font-medium text-gray-700;
}

/* Input styling */
.input {
  @apply w-full border border-gray-300 rounded-lg px-3 py-2
         focus:outline-none focus:ring-2 focus:ring-green-500;
}

/* Primary button */
.btn-primary {
  @apply bg-green-600 text-white px-5 py-2 rounded-lg
         hover:bg-green-700 transition;
}

/* Secondary button */
.btn-secondary {
  @apply bg-gray-200 text-gray-700 px-5 py-2 rounded-lg
         hover:bg-gray-300 transition;
}
</style>
