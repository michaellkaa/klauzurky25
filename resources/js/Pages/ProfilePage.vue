<template>
  <Logo/>

  <div class="max-w-6xl mx-auto py-16 px-6 text-gray-800 space-y-10">
    <div class="flex items-center gap-8">
      <div class="relative group">
        <div class="w-20 h-20 md:w-28 md:h-28 rounded-full bg-gray-200 overflow-hidden">
          <img :src="user.avatar_path ? `/${user.avatar_path}` : '/default-avatar.png'" alt=""
            class="w-full h-full object-cover" />
        </div>
        <button @click="triggerFileInput"
          class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition text-white text-xs flex items-center justify-center">
          Změnit
        </button>
        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handlePhotoChange" />
      </div>

      <div class="flex-1 space-y-2">
        <h1 class="text-3xl font-bold flex items-center gap-3">
          {{ user.name }}
          <span v-if="user.role === 'admin'"
            class="px-2 py-1 text-xs font-semibold text-white bg-purple-600 rounded-full">
            Admin
          </span>
        </h1>

        <div class="text-gray-600 flex items-center gap-2" v-if="user.email">
          <span class="font-medium">E-mail:</span>
          <span>{{ user.email }}</span>
        </div>
        <div>
          <RouterLink v-if="$router.hasRoute('change-password')" :to="{ name: 'change-password' }"
            class="text-sm text-purple-600 hover:underline">
            Změnit heslo
          </RouterLink>

        </div>
      </div>
    </div>

    <div v-if="user.role === 'admin'" class="bg-gray-50 border border-gray-300 p-6 rounded-xl text-gray-700">
      <AdminDashboard />
    </div>


    <div v-else>
      <div>
        <h2 class="text-2xl font-bold uppercase tracking-wide mb-4 ml-2">Vaše univerzity</h2>
        <div v-if="universities.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-10">
          <RouterLink v-for="university in universities" :key="university.id" :to="`/university/${university.id}`"
            class="bg-white rounded-2xl shadow-sm border-b-5 border-r-5 border-black hover:border-gray-600 hover:text-gray-600 p-5 transition hover:shadow-md duration-500 ease-in-out group flex flex-col relative cursor-pointer">
            <div class="absolute top-3 right-3 z-10">
              <HeartButton :id="university.id" type="university" />
            </div>
            <div class="flex items-start gap-4 mb-4">
              <img :src="university.logo_url" alt="Logo univezity" class="w-12 h-12 rounded-md object-contain" />
              <div class="max-w-[75 %] pr-3">
                <h3 class="text-lg font-bold text-gray-800 group-hover:text-gray-600 transition uppercase ">
                  {{ university.name }}
                </h3>
              </div>
            </div>
          </RouterLink>
        </div>
        <div v-else
          class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-gray-500 text-sm text-center">
          Zatím nemáte žádné přidané fakulty.
        </div>
      </div>

      <div>
        <h2 class="text-2xl font-bold uppercase tracking-wide mb-4 ml-2">Vaše fakulty</h2>
        <div v-if="faculties.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <RouterLink v-for="faculty in faculties" :key="faculty.id" :to="`/faculty/${faculty.id}`"
            class="bg-white rounded-2xl shadow-sm border-b-5 border-r-5 border-black  hover:border-gray-600  p-5 transition hover:shadow-md  group flex flex-col relative cursor-pointer">
            <div class="absolute top-3 right-3 z-10">
              <HeartButton :id="faculty.id" type="faculty" />
            </div>
            <div class="flex items-start gap-4 mb-4">
              <img :src="faculty.logo_url" alt="Logo fakulty" class="w-12 h-12 rounded-md object-contain" />
              <div class="max-w-[80%] pr-3">
                <h3 class="text-lg font-bold text-gray-800 group-hover:text-gray-600 transition uppercase">
                  {{ faculty.name }}
                </h3>
                <p class="text-sm text-gray-500">{{ faculty.university }}</p>
              </div>
            </div>
          </RouterLink>
        </div>
        <div v-else
          class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-gray-500 text-sm text-center">
          Zatím nemáte žádné přidané fakulty.
        </div>
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
import HeartButton from '../Components/HeartButton.vue'
import AdminDashboard from '../Pages/AdminDashboard.vue';
import Logo from '../Components/Logo.vue'


const user = ref({})
const fileInput = ref(null)

const faculties = ref([])
const universities = ref([])

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handlePhotoChange = async (e) => {
  const file = e.target.files[0]
  if (!file) return

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

async function logout() {
  try {
    await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
      credentials: 'include',
    })

    const response = await fetch('http://127.0.0.1:8000/logout', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      credentials: 'include',
    })

    if (!response.ok) throw new Error('Logout failed')
    window.location.href = '/login'
  } catch (error) {
    console.error('Logout selhal', error)
  }
}

onMounted(async () => {
  const resUser = await fetch('/api/user', { credentials: 'include' })
  if (resUser.ok) {
    user.value = await resUser.json()
  }

  if (!user.value.is_admin) {
    const resFaculties = await fetch('/api/user/favorites/faculties', { credentials: 'include' })
    if (resFaculties.ok) faculties.value = await resFaculties.json()

    const resUniversities = await fetch('/api/user/favorites/universities', { credentials: 'include' })
    if (resUniversities.ok) universities.value = await resUniversities.json()
  }
})
</script>
