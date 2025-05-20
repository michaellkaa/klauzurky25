<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const faculty = ref(null)

onMounted(async () => {
  const res = await fetch(`/api/faculties/${route.params.id}`)
  faculty.value = await res.json()
})
</script>

<template>
  <div class="p-6 max-w-4xl mx-auto" v-if="faculty">
    <img :src="faculty.banner_url" alt="" class="w-full h-auto mb-4 rounded-xl shadow" />
    <h1 class="text-3xl font-bold mb-4">{{ faculty.name }}</h1>
    <p class="text-gray-700 mb-2">{{ faculty.address }}</p>
    <p class="text-gray-600 mb-6">{{ faculty.field }}</p>

  </div>
</template>