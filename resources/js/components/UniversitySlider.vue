<script setup>
import { ref, onMounted } from 'vue';
import UniversityCard from './UniversityCard.vue';
const universities = ref([])

defineProps({
  title: String
})

onMounted(async () => {
  try {
    const res = await fetch('/api/universities')
    universities.value = await res.json()
  } catch (error) {
    console.error('Error loading universities:', error)
  }
})
</script>

<template>
    <section>
      <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
      <div class="flex overflow-x-auto gap-4 pb-4">
        <router-link
          v-for="uni in universities"
          :key="uni.id"
          :to="`/university/${uni.id}`"
          class="min-w-[250px] p-2 flex-shrink-0 "
        >
          <UniversityCard :university="uni" />
        </router-link>
      </div>
    </section>
  </template>