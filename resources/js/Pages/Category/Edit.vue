<script setup>
// Import ref for reactive data
import { ref } from 'vue'
// Import Inertia router
import { router } from '@inertiajs/vue3'

// Receive category data from controller
const props = defineProps({
  category: Object
})

// Set initial category name
const name = ref(props.category.name)

// Function to update category
const update = () => {
  router.put(`/category/${props.category.id}`, {
    name: name.value
  })
}
</script>

<template>
  <!-- Full page wrapper -->
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <!-- Card container -->
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6">

      <!-- Page title -->
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        ✏️ Edit Category
      </h2>

      <!-- Category name input -->
      <div class="mb-6">
        <label class="label">Category Name</label>
        <input v-model="name" class="input" />
      </div>

      <!-- Action buttons -->
      <div class="flex justify-end gap-3">
        <a href="/category" class="btn-secondary">
          Cancel
        </a>

        <button class="btn-primary" @click="update">
          Update Category
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
