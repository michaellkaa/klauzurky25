<script setup>
import { ref, onMounted } from 'vue'
import FacultyCard from './FacultyCard.vue'

const faculties = ref([])
const scrollContainer = ref(null)

defineProps({ 
    title: String 
})

onMounted(async () => {
  try {
    const res = await fetch('/api/recommended-faculties')
    faculties.value = await res.json();
  } catch (error) {
    console.error('Chyba při načítání doporučených fakult:', error);
  }
});

function onWheel(event) {
  if (scrollContainer.value) {
    scrollContainer.value.scrollLeft += event.deltaY
    event.preventDefault()
  }
}
</script>

<template>
  <section>
    <h2 class="text-2xl font-bold uppercase tracking-wide mb-4 ml-3">{{ title }}</h2>
    <div ref="scrollContainer" class="flex overflow-x-auto gap-4 pb-4 no-scrollbar" @wheel="onWheel">
      <router-link v-for="faculty in faculties" :key="faculty.id" :to="`/faculty/${faculty.id}`"
        class="min-w-[250px] p-2 flex-shrink-0">
        <FacultyCard :faculty="faculty" />
      </router-link>
    </div>
  </section>
</template>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { scrollbar-width: none; }
</style>
