<template>
  <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
    <div class="stat-card">
      <p class="text-sm text-gray-500">Celkem uživatelů</p>
      <p class="text-2xl font-bold text-gray-800">{{ stats.total_users }}</p>
    </div>
    <div class="stat-card">
      <p class="text-sm text-gray-500">Univerzity</p>
      <p class="text-2xl font-bold text-gray-800">{{ stats.total_universities }}</p>
    </div>
    <div class="stat-card">
      <p class="text-sm text-gray-500">Fakulty</p>
      <p class="text-2xl font-bold text-gray-800">{{ stats.total_faculties }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const stats = ref({
  total_users: 0,
  total_universities: 0,
  total_faculties: 0,
})

onMounted(async () => {
  try {
    const res = await fetch('/api/admin/stats', { credentials: 'include' })
    if (res.ok) {
      stats.value = await res.json()
    } else {
      console.error('Chyba při načítání statistik')
    }
  } catch (err) {
    console.error('Chyba v komunikaci s API', err)
  }
})
</script>