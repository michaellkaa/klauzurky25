<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  id: Number,
  type: String, // 'faculty' nebo 'university'
})

const isFavorited = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get('/api/is-favorited', {
      params: {
        id: props.id,
        type: props.type,
      },
    })
    isFavorited.value = response.data.favorited
  } catch (error) {
    console.error('Chyba při získávání informace o oblíbenosti:', error)
  }
})

async function toggleFavorite() {
  try {
    if (isFavorited.value) {
      await axios.post('/api/unfavorite', {
        id: props.id,
        type: props.type,
      })
    } else {
      await axios.post('/api/favorite', {
        id: props.id,
        type: props.type,
      })
    }
    isFavorited.value = !isFavorited.value
  } catch (error) {
    console.error('Chyba při změně oblíbenosti:', error)
  }
}
</script>

<template>
  <button @click.stop.prevent="toggleFavorite">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      :class="['w-6 h-6 transition-colors duration-200', isFavorited ? 'text-red-500' : 'text-gray-400']"
      fill="currentColor"
      viewBox="0 0 24 24"
    >
      <path
        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 
           6 3.5 4 6 4c1.54 0 3.04.99 
           3.57 2.36h.87C14.96 4.99 16.46 4 
           18 4c2.5 0 4 2 4 4.5 0 3.78-3.4 
           6.86-8.55 11.54L12 21.35z"
      />
    </svg>
  </button>
</template>
