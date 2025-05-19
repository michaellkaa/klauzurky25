<template>
  <form @submit.prevent="register" class="bg-white shadow-md rounded px-8 pt-6 pb-8 w-full max-w-sm space-y-4">
    <h2 class="text-2xl font-bold text-center">Register</h2>
    <input v-model="form.username" type="text" placeholder="Username" class="input" required />
    <input v-model="form.name" type="text" placeholder="Full Name" class="input" required />
    <input v-model="form.email" type="email" placeholder="Email" class="input" required />
    <input v-model="form.region" type="text" placeholder="Region" class="input" required />
    <input v-model="form.password" type="password" placeholder="Password" class="input" required />
    <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="input" required />
    <button class="btn w-full" type="submit">Register</button>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  username: '',
  name: '',
  email: '',
  region: '',
  password: '',
  password_confirmation: '',
})

async function register() {
  try {
    const res = await fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    if (!res.ok) {
      const data = await res.json()
      console.error('Validation errors:', data.errors || data)
      alert('Něco není v pořádku: ' + JSON.stringify(data.errors))
      return
    }
    alert('Registrace proběhla úspěšně!')
    // tady můžeš přesměrovat uživatele nebo vymazat formulář atd.
  } catch (error) {
    console.error('Chyba při registraci:', error)
  }
}
</script>
