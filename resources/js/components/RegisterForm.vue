<template>
  <div class="min-h-screen flex items-center justify-center p-6">
    <form 
      @submit.prevent="register" 
      class="w-full max-w-2xl flex flex-col space-y-8"
    >
      <h1 class="text-5xl font-extrabold tracking-tight text-black text-center uppercase">
        Registrace
      </h1>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <input 
            v-model="form.username" 
            type="text" 
            placeholder="Username" 
            class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
            required 
          />
          <p v-if="errors.username" class="text-red-600 font-semibold mt-1">{{ errors.username }}</p>
        </div>
        <div>
          <input 
            v-model="form.name" 
            type="text" 
            placeholder="Full Name" 
            class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
            required 
          />
          <p v-if="errors.name" class="text-red-600 font-semibold mt-1">{{ errors.name }}</p>
        </div>
      </div>

      <div>
        <input 
          v-model="form.email" 
          type="email" 
          placeholder="Email" 
          class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
          required 
        />
        <p v-if="errors.email" class="text-red-600 font-semibold mt-1">{{ errors.email }}</p>
      </div>

      <div>
        <input 
          v-model="form.region" 
          type="text" 
          placeholder="Region (Zlínský, Středočeský,..)" 
          class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
          required 
        />
        <p v-if="errors.region" class="text-red-600 font-semibold mt-1">{{ errors.region }}</p>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="Password" 
            class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
            required 
          />
          <p v-if="errors.password" class="text-red-600 font-semibold mt-1">{{ errors.password }}</p>
        </div>
        <div>
          <input 
            v-model="form.password_confirmation" 
            type="password" 
            placeholder="Confirm Password" 
            class="w-full px-4 py-4 border-2 border-black text-lg font-semibold focus:outline-none focus:ring-4 focus:ring-black"
            required 
          />
          <p v-if="errors.password_confirmation" class="text-red-600 font-semibold mt-1">{{ errors.password_confirmation }}</p>
        </div>
      </div>

      <button 
        class="w-full bg-black text-white py-4 text-xl font-extrabold uppercase tracking-wider hover:bg-white hover:text-black hover:border-black hover:border-2 transition-all duration-200"
        type="submit"
      >
        Registrovat se
      </button>

      <p class="text-center text-lg font-medium text-gray-700">
        Už máš účet?
        <router-link to="/login" class="underline font-bold hover:text-black">
          Přihlaš se
        </router-link>
      </p>
    </form>
  </div>
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
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' })

    const res = await fetch('/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
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
        console.log('Chyba: ' + JSON.stringify(data))
      }
      return
    }

    router.push('/login')
  } catch (error) {
    console.error('Chyba při registraci:', error)
  }
}
</script>
