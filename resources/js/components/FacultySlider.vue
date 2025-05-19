<script setup>
import { ref, onMounted } from 'vue'
import FacultyCard from './FacultyCard.vue'

const faculties = ref([])

defineProps({
  title: String
})

onMounted(async () => {
  try {
    const res = await fetch('/api/faculties')
    faculties.value = await res.json()
    console.log('Fakulty:', faculties.value)
  } catch (error) {
    console.error('Chyba při načítání fakult:', error)
  }
})

</script>

<template>
  <section>
    <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
    <div class="flex overflow-x-auto gap-4 pb-4">
      <router-link
        v-for="faculty in faculties"
        :key="faculty.id"
        :to="`/faculties/${faculty.id}`"
        class="min-w-[250px] p-2 flex-shrink-0"
      >
        <FacultyCard :faculty="faculty" />
      </router-link>
    </div>
  </section>
</template>
