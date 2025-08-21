<template>
    <Logo/>

  <div class="p-6">
    <h2 v-if="faculties.length > 0" class="text-2xl font-bold uppercase tracking-wide mb-4 ml-2"> {{ decodedField }}</h2>

    <div v-if="loading" class="fixed inset-0 bg-white/90 z-[150] flex items-center justify-center">
      <Loader />
    </div>
    <div v-else-if="faculties.length === 0" class="flex flex-col items-center justify-center min-h-[50vh] text-center space-y-4">
      <h1 class="text-3xl font-extrabold uppercase ">ŽÁDNÁ FAKULTA NENALEZENA</h1>
      <router-link to="/" class="font-bold uppercase underline hover:no-underline text-gray-800">
        Zpět na hlavní stránku
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5">
      <FacultyCard v-for="faculty in faculties" :key="faculty.id" :faculty="faculty" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import FacultyCard from '../Components/FacultyCard.vue'
import Loader from '../Components/Loader.vue'
import Logo from '../Components/Logo.vue'

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
