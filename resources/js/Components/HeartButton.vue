<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  id: Number,
  type: String,
  isFavorite : {
    type: Boolean,
    default: false
  }
})

const isFavorited = ref(props.isFavorite)
/*
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
*/
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
  <button @click.stop.prevent="toggleFavorite" class="focus:outline-none" aria-label="Toggle favorite">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" :class="[
      'w-6 h-6 transition-all duration-200 ease-in-out transform',
      isFavorited ? 'text-red-500 scale-110' : 'text-gray-200',
      'hover:scale-125 hover:text-red-400 cursor-pointer'
    ]">
      <path
        d="M12 20.5s-7.5-5-9-9.5c-1.1-3.2 0.9-6.5 4.1-6.9 2-.3 4 1.2 4.9 2.8 0.9-1.6 2.9-3.1 4.9-2.8 3.2 0.4 5.2 3.7 4.1 6.9-1.5 4.5-9 9.5-9 9.5z" />
    </svg>
  </button>
</template>
