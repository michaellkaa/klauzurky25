<template>
  <button @click="toggleFavorite" class="text-2xl">
    <span v-if="favorited">❤️</span>
    <span v-else>🤍</span>
  </button>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  id: Number,
  type: String,
  initiallyFavorited: Boolean,
})

const favorited = ref(props.initiallyFavorited)

async function toggleFavorite() {
  const url = '/api/favorites'
  const method = favorited.value ? 'DELETE' : 'POST'

  try {
    const response = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      credentials: 'include', // důležité pro Sanctum session
      body: JSON.stringify({
        id: props.id,
        type: props.type,
      }),
    })

    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`)
    }

    const data = await response.json()
    console.log(data.message)

    // Přepne stav
    favorited.value = !favorited.value
  } catch (error) {
    console.error('Chyba při přepínání oblíbenosti:', error)
  }
}
</script>

<style scoped>
button {
  cursor: pointer;
  transition: all 0.3s ease;
}
</style>
