<script setup>
/*import { ref, onMounted } from 'vue'

const props = defineProps({
  item: Object,
  type: String
})

const liked = ref(false)

const toggleLike = async () => {
  try {
    // 1. Zajisti CSRF cookie
    await fetch('/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    // 2. Teprve potom pošli POST
    const res = await fetch('/api/favorites/toggle', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({
        id: props.item.id,
        type: props.type
      })
    })

    const data = await res.json()
    liked.value = data.liked
  } catch (err) {
    console.error('Chyba při ukládání oblíbených položek', err)
  }
}

// Optional: Load liked status on mount
onMounted(async () => {
  try {
    const res = await fetch(`/api/favorites/check?id=${props.item.id}&type=${props.type}`, {
      method: 'GET',
      credentials: 'include',
      headers: { 'Accept': 'application/json' },
    })
    const data = await res.json()
    liked.value = data.liked
  } catch (err) {
    console.error('Nepodařilo se načíst stav oblíbených položek', err)
  }
})
</script>

<template>
  <button @click.stop.prevent="toggleLike" class="bg-white bg-opacity-70 rounded-full p-1 hover:bg-opacity-100 transition">
    <svg
      :class="liked ? 'text-red-500' : 'text-gray-400'"
      class="w-6 h-6"
      fill="currentColor"
      viewBox="0 0 24 24"
    >
      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
               2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
               C13.09 3.81 14.76 3 16.5 3
               19.58 3 22 5.42 22 8.5
               c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
  </button>
</template>
*/