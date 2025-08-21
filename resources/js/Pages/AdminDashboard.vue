<template>
  <div class="p-6 space-y-8">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    </div>

    <AdminStats />

    <div>
      <h2 class="text-xl font-semibold mb-4">Všichni uživatelé</h2>
      <div class="overflow-auto rounded-xl shadow-sm border border-gray-800">
        <table class="min-w-full bg-white text-sm">
          <thead class="bg-gray-50 text-left text-gray-700 font-semibold">
            <tr>
              <th class="px-6 py-4">ID</th>
              <th class="px-6 py-4">Jméno</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Akce</th>
            </tr>
          </thead>
          <tbody v-if="users.length > 0" class="text-gray-800 divide-y divide-gray-100">
            <tr v-for="user in users" :key="user.id">
              <td class="px-6 py-3">{{ user.id }}</td>
              <td class="px-6 py-3">{{ user.name }}</td>
              <td class="px-6 py-3">{{ user.email }}</td>
              <td class="px-6 py-3 flex gap-6 flex-nowrap items-center">
                <button @click="deleteUser(user.id)" class="text-red-600 hover:underline whitespace-nowrap">
                  Smazat
                </button>
                <button 
                  v-if="user.role !== 'admin'" 
                  @click="promoteToAdmin(user.id)" 
                  class="text-indigo-600 hover:underline whitespace-nowrap">
                  Změnit na admina
                </button>

              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="5" class="text-center py-6 text-gray-500">Žádní uživatelé k zobrazení.</td>
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

const users = ref([])

const fetchUsers = async () => {
  try {
    const res = await fetch('/api/admin/users', { credentials: 'include', })
    if (res.ok) {
      users.value = await res.json()
    } else {
      console.error('Chyba při načítání uživatelů')
    }
  } catch (err) {
    console.error('Chyba v komunikaci s API', err)
  }
}

const deleteUser = async (id) => {
  if (!confirm('Opravdu chcete smazat tohoto uživatele?')) return

  try {
    const res = await fetch(`/api/admin/users/${id}`, {
      method: 'DELETE',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      }
    })

    if (res.ok) {
      console.log('Uživatel smazán')
      await fetchUsers()
    } else {
      const data = await res.json()
      console.error('Chyba při mazání uživatele', data)
      console.log('Chyba při mazání uživatele')
    }
  } catch (err) {
    console.error('Chyba při mazání uživatele', err)
  }
}

const promoteToAdmin = async (id) => {
  try {
    const res = await fetch(`/api/admin/users/${id}/promote`, {
      method: 'PATCH',
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    });
    if (res.ok) {
      alert('User promoted to admin');
      await fetchUsers();
    } else {
      const data = await res.json();
      console.error('Chyba při povyšování uživatele', data);
    }
  } catch (err) {
    console.error('Chyba při povyšování uživatele', err);
  }
}



onMounted(() => {
  fetchUsers()
})
</script>
