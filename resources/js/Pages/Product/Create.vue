<script setup>
// Import ref to create reactive state
import { ref } from 'vue'
// Import Inertia router for form submission
import { router } from '@inertiajs/vue3'

// Receive categories list from controller
defineProps({
  categories: Array
})

// Reactive form object
const form = ref({
  name: '',
  details: '',
  price: '',
  category_id: ''
})

// Store product in database
const store = () => {
  // Send POST request to backend
  router.post('/product', form.value)
}
</script>

<template>
  <!-- Page wrapper -->
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <!-- Card container -->
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-6">
      
      <!-- Page title -->
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        ➕ Create Product
      </h2>

      <!-- Product Name -->
      <div class="mb-4">
        <label class="label">Product Name</label>
        <input
          v-model="form.name"
          type="text"
          class="input"
          placeholder="Enter product name"
        />
      </div>

      <!-- Product Details -->
      <div class="mb-4">
        <label class="label">Details</label>
        <textarea
          v-model="form.details"
          class="input"
          placeholder="Enter product details"
        ></textarea>
      </div>

      <!-- Product Price -->
      <div class="mb-4">
        <label class="label">Price</label>
        <input
          v-model="form.price"
          type="number"
          class="input"
          placeholder="Enter price"
        />
      </div>

      <!-- Category Dropdown -->
      <div class="mb-6">
        <label class="label">Category</label>
        <select v-model="form.category_id" class="input">
          <option value="">Select category</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>
      </div>

      <!-- Action buttons -->
      <div class="flex justify-end gap-3">
        <!-- Back button -->
        <a href="/product" class="btn-secondary">
          Cancel
        </a>

        <!-- Submit button -->
        <button class="btn-primary" @click="store">
          Save Product
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

/* Input and textarea styling */
.input {
  @apply w-full border border-gray-300 rounded-lg px-3 py-2
         focus:outline-none focus:ring-2 focus:ring-blue-500;
}

/* Primary button */
.btn-primary {
  @apply bg-blue-600 text-white px-5 py-2 rounded-lg
         hover:bg-blue-700 transition;
}

/* Secondary button */
.btn-secondary {
  @apply bg-gray-200 text-gray-700 px-5 py-2 rounded-lg
         hover:bg-gray-300 transition;
}
</style>
