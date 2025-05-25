<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const query = ref('')
const results = ref([])
const loading = ref(false)
const error = ref(null)

const router = useRouter()

const search = async () => {
  const trimmed = query.value.trim()
  if (!trimmed) {
    results.value = []
    return
  }

  loading.value = true
  error.value = null

  try {
    const response = await fetch(`/api/search?query=${encodeURIComponent(trimmed)}`)
    if (!response.ok) throw new Error('Chyba při načítání výsledků')

    const data = await response.json()

    const allResults = [
      ...data.universities.map(u => ({ type: 'university', id: u.id, name: u.name, location: u.location })),
      ...data.faculties.map(f => ({ type: 'faculty', id: f.id, name: f.name, location: f.address })),
    ]

    results.value = allResults.slice(0, 5)
  } catch (err) {
    error.value = err.message
    results.value = []
  } finally {
    loading.value = false
  }
}

const goToResult = (result) => {
  if (result.type === 'university') {
    router.push(`/university/${result.id}`)
  } else if (result.type === 'faculty') {
    router.push(`/faculty/${result.id}`)
  }

  // Clear input and results
  query.value = ''
  results.value = []
}
</script>

<template>
  <div class="relative max-w-xl mx-auto mt-6">
    <input v-model="query" @input="search" type="text" placeholder="Hledat univerzitu nebo fakultu…"
      class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-400" />

    <ul v-if="query && results.length"
      class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 shadow-md divide-y">
      <li v-for="(result, index) in results" :key="index"
        class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center"
        @click="goToResult(result)">
        <span class="font-medium">{{ result.name }}</span>
        <span class="text-sm text-gray-500">{{ result.location }}</span>
      </li>
    </ul>

    <div v-if="query && !results.length && !loading"
      class="absolute w-full mt-1 text-sm text-gray-500 bg-white border border-gray-200 rounded-md p-2">
      Žádné výsledky
    </div>
  </div>
</template>
