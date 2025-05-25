<template>
  <div class="p-6 space-y-8">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold">Admin Dashboard</h1>
      <router-link
        to="/"
        class="text-sm text-purple-600 hover:underline"
      >
        Zpět na hlavní stránku
      </router-link>
    </div>

    <AdminStats />

    <div>
      <h2 class="text-xl font-semibold mb-4">Poslední přihlášení uživatelé</h2>
      <div class="overflow-auto rounded-xl shadow-sm border border-gray-200">
        <table class="min-w-full bg-white text-sm">
          <thead class="bg-gray-50 text-left text-gray-700 font-semibold">
            <tr>
              <th class="px-6 py-4">Jméno</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Poslední přihlášení</th>
            </tr>
          </thead>
          <tbody v-if="recentUsers.length > 0" class="text-gray-800 divide-y divide-gray-100">
            <tr v-for="user in recentUsers" :key="user.id">
              <td class="px-6 py-3">{{ user.name }}</td>
              <td class="px-6 py-3">{{ user.email }}</td>
              <td class="px-6 py-3">{{ formatDate(user.last_login) }}</td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="3" class="text-center py-6 text-gray-500">Žádní uživatelé k zobrazení.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminStats from '../Components/AdminStats.vue'

const recentUsers = ref([])

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleString('cs-CZ', { dateStyle: 'medium', timeStyle: 'short' })
}

onMounted(async () => {
  try {
    const response = await fetch('/api/admin/recent-users', { credentials: 'include' })
    if (response.ok) {
      recentUsers.value = await response.json()
    } else {
      console.error('Chyba při načítání uživatelů')
    }
  } catch (error) {
    console.error('Chyba při komunikaci s API', error)
  }
})
</script>
