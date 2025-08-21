<template>
  <div>
    <h2 class="text-2xl font-bold uppercase tracking-wide my-4 ml-3">{{ title }}</h2>

    <div
      ref="scrollContainer"
      class="flex gap-2 overflow-x-auto no-scrollbar pb-2"
      @wheel="onWheel"
    >
      <div
        v-for="field in fields"
        :key="field.id"
        class="h-10 bg-black rounded cursor-pointer flex items-center justify-center text-white px-4 whitespace-nowrap"
        @click="handleClick(field)"
      >
        {{ field.name }}
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const fields = ref([])
const scrollContainer = ref(null)

defineProps({
  title: String
})

onMounted(async () => {
  try {
    const res = await fetch('/api/fields')
    fields.value = await res.json()
  } catch (err) {
    console.error('Chyba při načítání oborů:', err)
  }
})

function handleClick(field) {
  router.push({ path: '/field-faculties', query: { field: field.name } })
}

function onWheel(event) {
  if (scrollContainer.value) {
    scrollContainer.value.scrollLeft += event.deltaY
    event.preventDefault()
  }
}
</script>

<style>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  scrollbar-width: none;
}
</style>
