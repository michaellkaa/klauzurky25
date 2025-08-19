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

    const data = await response.json().catch(() => {
      throw new Error('Backend nevrátil platný JSON')
    })

    if (!response.ok) {
      if (data.errors && data.errors.email) {
        error.value = data.errors.email[0]
      } else {
        error.value = data.message || 'Chyba při přihlášení'
      }
      return
    }

    router.push('/')
  } catch (err) {
    error.value = err.message
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center  p-6">
    <form
      @submit.prevent="handleLogin"
      class="w-full max-w-md flex flex-col space-y-8"
    >
      <!-- Nadpis -->
      <h1 class="text-5xl font-extrabold tracking-tight text-black text-center uppercase">
        Přihlášení
      </h1>

      <!-- Inputy -->
      <div class="flex flex-col space-y-4">
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
          required
        />
        <input
          v-model="password"
          type="password"
          placeholder="Heslo"
          class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
          required
        />
      </div>

      <!-- Error -->
      <p v-if="error" class="text-red-600 font-semibold text-center">
        {{ error }}
      </p>

      <!-- Button -->
      <button
        class="w-full bg-black text-white py-4 text-xl font-extrabold uppercase tracking-wider hover:bg-white hover:text-black hover:border-black hover:border-2 transition-all duration-200"
      >
        Přihlásit se
      </button>

      <!-- Register link -->
      <p class="text-center text-lg font-medium text-gray-700">
        Nemáš účet?
        <router-link to="/register" class="underline font-bold hover:text-black">
          Registruj se
        </router-link>
      </p>
    </form>
  </div>
</template>
