<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Fakulty pro obor: {{ decodedField }}</h1>

    <div v-if="loading" class="text-gray-500">Načítání fakult...</div>
    <div v-else-if="faculties.length === 0" class="text-gray-500">Žádné fakulty nenalezeny.</div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">
      <FacultyCard v-for="faculty in faculties" :key="faculty.id" :faculty="faculty" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import FacultyCard from '@/components/FacultyCard.vue'

const route = useRoute()
const field = ref(route.query.field || '')
const decodedField = decodeURIComponent(field.value)

const faculties = ref([])
const loading = ref(true)

async function loadFaculties() {
  if (!decodedField) return
  loading.value = true
  try {
    const res = await fetch(`/api/field-faculties?field=${encodeURIComponent(decodedField)}`)
    faculties.value = await res.json()
  } catch (e) {
    console.error('Chyba při načítání fakult:', e)
  } finally {
    loading.value = false
  }
}

onMounted(loadFaculties)

watch(() => route.query.field, (newField) => {
  field.value = newField
  loadFaculties()
})
</script>
