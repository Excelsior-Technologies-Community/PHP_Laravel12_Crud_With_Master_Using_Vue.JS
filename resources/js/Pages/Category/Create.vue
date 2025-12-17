<script setup>
// Import ref for reactive variables
import { ref } from 'vue'
// Import Inertia router for form submission
import { router } from '@inertiajs/vue3'

// Reactive variable to store category name
const name = ref('')

// Function to store category
const store = () => {
  // Send POST request to backend
  router.post('/category', { name: name.value })
}
</script>

<template>
  <!-- Full page wrapper -->
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <!-- Card container -->
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6">

      <!-- Page title -->
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        ➕ Create Category
      </h2>

      <!-- Category name input -->
      <div class="mb-6">
        <label class="label">Category Name</label>
        <input
          v-model="name"
          class="input"
          placeholder="Enter category name"
        />
      </div>

      <!-- Action buttons -->
      <div class="flex justify-end gap-3">
        <!-- Back to category list -->
        <a href="/category" class="btn-secondary">
          Cancel
        </a>

        <!-- Submit form -->
        <button class="btn-primary" @click="store">
          Save Category
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

/* Input field styling */
.input {
  @apply w-full border border-gray-300 rounded-lg px-3 py-2
         focus:outline-none focus:ring-2 focus:ring-blue-500;
}

/* Primary button styling */
.btn-primary {
  @apply bg-blue-600 text-white px-5 py-2 rounded-lg
         hover:bg-blue-700 transition;
}

/* Secondary button styling */
.btn-secondary {
  @apply bg-gray-200 text-gray-700 px-5 py-2 rounded-lg
         hover:bg-gray-300 transition;
}
</style>
