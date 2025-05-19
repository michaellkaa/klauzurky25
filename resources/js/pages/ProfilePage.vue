<template>
  <div class="max-w-4xl mx-auto py-16 px-6 text-gray-800 space-y-10">
    <!-- Profilová hlavička -->
    <div class="flex items-center gap-8">
      <div class="w-28 h-28 rounded-full bg-gray-200 overflow-hidden">
        <img
          src="https://via.placeholder.com/150"
          alt="Profilový obrázek"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="flex-1 space-y-2">
        <h1 class="text-3xl font-bold">{{ user.name }}</h1>
        <div class="text-gray-600 flex items-center gap-2">
          <span class="font-medium">E-mail:</span>
          <span>{{ user.email }}</span>
          <RouterLink
            to="/change-email"
            class="text-sm text-blue-600 hover:underline"
          >Změnit</RouterLink>
        </div>
        <div>
          <RouterLink
            to="/forgot-password"
            class="text-sm text-blue-600 hover:underline"
          >Zapomněli jste heslo?</RouterLink>
        </div>
      </div>
    </div>

    <!-- Sekce oblíbené/univerzity -->
    <div>
      <h2 class="text-xl font-semibold mb-2">Vaše univerzity</h2>
      <div class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-gray-500 text-sm text-center">
        Zatím nemáte žádné přidané univerzity.
      </div>
    </div>

    <div>
      <h2 class="text-xl font-semibold mb-2">Vaše fakulty</h2>
      <div class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-gray-500 text-sm text-center">
        Zatím nemáte žádné přidané fakulty.
      </div>
    </div>

    <!-- Odhlášení -->
    <div class="flex justify-end pt-6 border-t border-gray-200">
      <Button type="logout" :onClick="logout">Odhlásit se</Button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Button from '../Components/Button.vue'

const user = ref({ name: '', email: '' })
const router = useRouter()

onMounted(async () => {
  const response = await fetch('/api/user', {
    credentials: 'include',
  })
  if (response.ok) {
    user.value = await response.json()
  }
})

const logout = async () => {
  try {
    const res = await fetch('/api/logout', {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    })
    if (res.ok) {
      router.push('/login')
    }
  } catch (error) {
    console.error('Chyba při odhlášení:', error)
  }
}
</script>

<style scoped>
/* Můžeš upravit globálně Tailwind barvy, pokud potřebuješ jiné pozadí */
</style>
