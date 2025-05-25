<template>
  <form @submit.prevent="register" class="bg-white shadow-md rounded px-8 pt-6 pb-8 w-full max-w-sm space-y-6">
    <h2 class="text-2xl font-bold text-center mb-4">Registrace</h2>

    <div class="flex gap-4">
      <div class="flex-1">
        <input v-model="form.username" type="text" placeholder="Username" class="input w-full" required />
        <p v-if="errors.username" class="text-red-600 text-sm mt-1">{{ errors.username }}</p>
      </div>
      <div class="flex-1">
        <input v-model="form.name" type="text" placeholder="Full Name" class="input w-full" required />
        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
      </div>
    </div>

    <div>
      <input v-model="form.email" type="email" placeholder="Email" class="input w-full" required />
      <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
    </div>

    <div>
      <input v-model="form.region" type="text" placeholder="Region (Zlínský, Středočeský,..)" class="input w-full" required />
      <p v-if="errors.region" class="text-red-600 text-sm mt-1">{{ errors.region }}</p>
    </div>

    <div class="flex gap-4">
      <div class="flex-1">
        <input
          v-model="form.password"
          type="password"
          placeholder="Password"
          class="input w-full"
          required
        />
        <p v-if="errors.password" class="text-red-600 text-sm mt-1">{{ errors.password }}</p>
      </div>
      <div class="flex-1">
        <input
          v-model="form.password_confirmation"
          type="password"
          placeholder="Confirm Password"
          class="input w-full"
          required
        />
        <p v-if="errors.password_confirmation" class="text-red-600 text-sm mt-1">{{ errors.password_confirmation }}</p>
      </div>
    </div>

    <button class="w-full bg-purple-800 hover:bg-purple-600 text-white rounded-md px-4 py-2" type="submit">
      Registrovat se
    </button>
  </form>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { ref } from 'vue'

const router = useRouter()

const form = ref({
  username: '',
  name: '',
  email: '',
  region: '',
  password: '',
  password_confirmation: '',
})

const errors = ref({})

async function register() {
  errors.value = {}

  if (form.value.password.length < 8) {
    errors.value.password = 'Heslo musí mít alespoň 8 znaků.'
    return
  }

  try {
    // DŮLEŽITÉ: nejdřív CSRF cookie
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' })

    const res = await fetch('/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json' // ← důležité!
  },
  credentials: 'include',
  body: JSON.stringify(form.value),
})


    const data = await res.json().catch(() => {
      throw new Error('Backend nevrátil platný JSON')
    })
    console.log(data)

    if (!res.ok) {
      if (data.errors) {
        for (const key in data.errors) {
          errors.value[key] = data.errors[key][0]
        }
      } else {
        alert('Chyba: ' + JSON.stringify(data))
      }
      return
    }

    alert('Registrace proběhla úspěšně!')
    router.push('/')
  } catch (error) {
    console.error('Chyba při registraci:', error)
  }
}

</script>
