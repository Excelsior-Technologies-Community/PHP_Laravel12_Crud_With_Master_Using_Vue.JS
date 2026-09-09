<script setup>
import { useForm } from '@inertiajs/vue3'
import AppHeader from '@/Components/AppHeader.vue'

const form = useForm({
    name: ''
})

const store = () => {
    form.post('/size')
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 pb-16">
        <AppHeader activeTab="sizes" />

        <div class="max-w-xl mx-auto px-4 pt-6">
            <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 border border-slate-200/80">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="text-3xl">➕</span>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Create New Size</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Add a new standard size to your master catalog.</p>
                    </div>
                </div>

                <form @submit.prevent="store" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Size Name / Code <span class="text-rose-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                            :class="form.errors.name ? 'border-rose-400 ring-1 ring-rose-300' : 'border-slate-200'"
                            placeholder="e.g. XL - Extra Large, 34 (Waist), Free Size"
                            autofocus
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600 font-semibold">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="/size"
                            class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-xl shadow-md transition disabled:opacity-50"
                        >
                            <span v-if="form.processing" class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                            <span>Save Size</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
