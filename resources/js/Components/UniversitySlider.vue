<script setup>
import UniversityCard from './UniversityCard.vue';
import { ref, onMounted } from 'vue';

const universities = ref([])

defineProps({
  title: String
})

const scrollContainer = ref(null)

onMounted(async () => {
  try {
    const res = await fetch('/api/universities')
    universities.value = await res.json()
  } catch (error) {
    console.error('Error loading universities:', error)
  }
})

function onWheel(event) {
  if (scrollContainer.value) {
    if (event.deltaY !== 0) {
      scrollContainer.value.scrollLeft += event.deltaY
      event.preventDefault()
    }
  }
}
</script>

<template>
  <section>
    <h2 class="text-2xl font-bold uppercase tracking-wide mb-4 ml-3">{{ title }}</h2>
    <div
      ref="scrollContainer"
      class="flex overflow-x-auto gap-4 pb-4  no-scrollbar"
      @wheel="onWheel"
    >
      <router-link
        v-for="uni in universities"
        :key="uni.id"
        :to="`/university/${uni.id}`"
        class="min-w-[250px] p-2 flex-shrink-0"
      >
        <UniversityCard :university="uni" />
      </router-link>
    </div>
  </section>
</template>

<style>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  scrollbar-width: none;
}
</style>