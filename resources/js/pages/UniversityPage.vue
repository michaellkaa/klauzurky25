<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const university = ref(null)

onMounted(async () => {
  const res = await fetch(`/api/universities/${route.params.id}`)
  university.value = await res.json()
})
</script>

<template>
  <div class="p-6 max-w-4xl mx-auto" v-if="university">
    <h1 class="text-3xl font-bold mb-4">{{ university.name }}</h1>
    <p class="text-gray-700 mb-2">{{ university.address }}</p>
    <p class="text-gray-600 mb-6">{{ university.about }}</p>
    <a :href="university.website" class="text-green-600 underline" target="_blank">Visit website</a>
  </div>
</template>
