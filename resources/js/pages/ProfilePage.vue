<template>
  <div class="max-w-4xl mx-auto py-16 px-6 text-gray-800 space-y-10">
    <!-- Profilová hlavička -->
    <div class="flex items-center gap-8">
      <div class="relative group">
        <div class="w-28 h-28 rounded-full bg-gray-200 overflow-hidden">
          <img
            :src="user.avatar_path ? `/${user.avatar_path}` : `/${user.avatar_path}`"
            alt="Profilový obrázek"
            class="w-full h-full object-cover"
          />


        </div>
        <button
          @click="triggerFileInput"
          class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition text-white text-xs flex items-center justify-center"
        >
          Změnit
        </button>
        <input
          ref="fileInput"
          type="file"
          accept="image/*"
          class="hidden"
          @change="handlePhotoChange"
        />
      </div>
      <div class="flex-1 space-y-2">
        <h1 class="text-3xl font-bold">{{ user.name }}</h1>
        <div class="text-gray-600 flex items-center gap-2">
          <span class="font-medium">E-mail:</span>
          <span>{{ user.email }}</span>
          
        </div>
        <div>
          <RouterLink
            to=""
            class="text-sm text-blue-600 hover:underline"
          >Změnit heslo</RouterLink>
        </div>
      </div>
    </div>

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

    <div class="flex justify-end pt-6 border-t border-gray-200">
    <Button type="logout" :onClick="logout">Odhlásit se</Button>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Button from '../Components/Button.vue'


const user = ref({}) 
//const user = ref({ name: '', email: '', avatar_url: '' })
const router = useRouter()
const fileInput = ref(null)
const previewPhoto = ref(null)

onMounted(async () => {
  const response = await fetch('/api/user', { credentials: 'include' })
  if (response.ok) {
    user.value = await response.json()
  }
})

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handlePhotoChange = async (e) => {
  const file = e.target.files[0]
  if (!file) return

  previewPhoto.value = URL.createObjectURL(file)

  const formData = new FormData()
  formData.append('photo', file)

  const res = await fetch('/api/user/photo', {
    method: 'POST',
    body: formData,
    credentials: 'include',
  })

  if (res.ok) {
  const updatedUser = await res.json()
    user.value.avatar_path = updatedUser.avatar_path || updatedUser.avatar_url
  } else {
    console.error('Nepodařilo se nahrát profilový obrázek.')
  }
}

/*const logout = async () => {
  try {
    // 1. Získání CSRF cookie – Laravel ji pošle do cookies
    await fetch('/sanctum/csrf-cookie', {
      credentials: 'include'
    })

    // 2. Odhlášení – cookie se přiloží automaticky
    const res = await fetch('/api/logout', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
      },
    })

    if (!res.ok) {
      throw new Error('Chyba při odhlašování')
    }

    router.push('/login')
  } catch (error) {
    console.error('Nepodařilo se odhlásit.', error)
  }
}*/


async function logout() {
  try {
    await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
      credentials: 'include',
    });

    const response = await fetch('http://127.0.0.1:8000/logout', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    });

    console.log('Status:', response.status);
    const data = await response.json();
    console.log('Response:', data);

    if (!response.ok) {
      throw new Error('Logout failed');
    }

    console.log('Logout úspěšný');
  } catch (error) {
    console.error('Logout selhal', error);
  }
}

</script>
