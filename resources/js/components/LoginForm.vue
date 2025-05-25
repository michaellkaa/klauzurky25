<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref(null)

const handleLogin = async () => {
  error.value = null
  try {


    const response = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.message || 'Chyba při přihlášení')
    }

    router.push('/')  // přesměrování po úspěchu
  } catch (err) {
    error.value = err.message
  }
}
</script>

<template>
  <form @submit.prevent="handleLogin" class="bg-white shadow-md rounded px-8 pt-6 pb-8 w-full max-w-sm space-y-4">
    <h2 class="text-2xl font-bold text-center">Login</h2>

    <input
      v-model="email"
      type="email"
      placeholder="Email"
      class="input w-full"
      required
    />
    <input
      v-model="password"
      type="password"
      placeholder="Heslo"
      class="input w-full"
      required
    />

    <button class=" w-full bg-purple-800 hover:bg-purple-600 text-white rounded-md px-4 py-2">Přihlásit se</button>

    <p v-if="error" class="text-red-500 text-sm text-center">{{ error }}</p>

    <p class="text-sm text-center text-gray-600">
      Nemáš účet?
      <router-link to="/register" class="text-purple-900 hover:underline">Registruj se</router-link>
    </p>
  </form>
</template>
