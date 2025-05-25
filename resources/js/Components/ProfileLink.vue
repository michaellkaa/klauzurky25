<template>
  <router-link v-if="user" to="/profile" class="flex items-center space-x-2 cursor-pointer">
    <span class="hidden sm:inline text-sm text-black font-medium pr-2">{{ user.email }}</span>
    <img
      :src="user.avatar_path || defaultAvatar"
      alt="Profilová fotka"
      class="w-10 h-10 rounded-full object-cover  border-gray-300 border"
    />
  </router-link>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const defaultAvatar = '/images/default-avatar.png' // nastav cestu k výchozímu avataru

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/user', { withCredentials: true })
    user.value = data
  } catch (error) {
    console.error('Nepodařilo se načíst uživatele:', error)
  }
})
</script>
