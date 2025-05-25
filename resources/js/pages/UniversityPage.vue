<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import HeartButton from '../Components/HeartButton.vue'

const loading = ref(true)

const route = useRoute()
const university = ref(null)

onMounted(async () => {
    loading.value = true
try {
  const res = await fetch(`/api/universities/${route.params.id}`)
  university.value = await res.json()
 } finally {
    loading.value = false
  }
})
</script>

<template>
<div class="space-y-8 p-3 md:p-6">
  <div class="flex justify-between">
    <router-link to="/">
      <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
    </router-link>
  </div>
</div>
    <main class="max-w-3xl mx-auto p-8 font-sans text-gray-900">
    <div v-if="loading" class="text-center py-20 text-gray-500 text-sm">Načítám data...</div>
  <div class="p-6 max-w-4xl mx-auto" v-if="university">
    <!-- Banner + sociální sítě vlevo -->
    <div class="relative rounded-xl overflow-hidden mb-6 shadow flex items-center">
      <img :src="university.banner_url" alt="Banner" class="w-full h-48 object-cover rounded-xl" />
      <div class="absolute top-4 right-4">
          <HeartButton :type="'university'" :id="university.id" />
        </div>
      <div class="absolute top-3 left-4 flex gap-4 text-purple-600">
        <a v-if="university.facebook" :href="university.facebook" target="_blank" aria-label="Facebook" class="hover:text-purple-400 italic">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.522-4.477-10-10-10S2 6.478 2 12c0 5.012 3.676 9.164 8.438 9.877v-6.987h-2.54v-2.89h2.54V9.845c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.324 21.164 22 17.012 22 12z"/></svg>
        </a>
        <a v-if="university.instagram" :href="university.instagram" target="_blank" aria-label="Instagram" class="hover:text-purple-400 italic">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3zm5 2a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
        </a>
        <a v-if="university.twitter" :href="university.twitter" target="_blank" aria-label="Twitter" class="hover:text-purple-400 italic">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.27 4.27 0 001.88-2.36 8.3 8.3 0 01-2.7 1.03 4.14 4.14 0 00-7.05 3.77 11.77 11.77 0 01-8.55-4.32 4.13 4.13 0 001.28 5.52 4.1 4.1 0 01-1.88-.52v.05a4.13 4.13 0 003.33 4.05 4.19 4.19 0 01-1.87.07 4.13 4.13 0 003.85 2.88A8.29 8.29 0 012 19.54 11.7 11.7 0 008.29 21c7.55 0 11.68-6.26 11.68-11.68 0-.18-.01-.35-.02-.52A8.18 8.18 0 0022.46 6z"/></svg>
        </a>
        <a v-if="university.youtube" :href="university.youtube" target="_blank" aria-label="YouTube" class="hover:text-purple-400 italic">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19.6 7.23a2.62 2.62 0 00-1.84-1.84C16.27 5 12 5 12 5s-4.27 0-5.76.39a2.62 2.62 0 00-1.84 1.84A27.14 27.14 0 004 12a27.14 27.14 0 00.36 4.77 2.62 2.62 0 001.84 1.84C7.73 19 12 19 12 19s4.27 0 5.76-.39a2.62 2.62 0 001.84-1.84 27.14 27.14 0 00.36-4.77 27.14 27.14 0 00-.36-4.77zM10 15.5v-7l6 3.5-6 3.5z"/></svg>
        </a>
        <a v-if="university.linkedin" :href="university.linkedin" target="_blank" aria-label="LinkedIn" class="hover:text-purple-400 italic">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 002.5 6v12a2.5 2.5 0 002.48 2.5h14.04A2.5 2.5 0 0021.5 18V6a2.5 2.5 0 00-2.5-2.5H4.98zM8.62 17.75H6.5v-6.5h2.12v6.5zm-1.06-7.5a1.22 1.22 0 110-2.44 1.22 1.22 0 010 2.44zm7.02 7.5h-2.12v-3.5c0-.83-.03-1.9-1.16-1.9-1.17 0-1.35.91-1.35 1.85v3.55H9.6v-6.5h2.04v.88h.03a2.23 2.23 0 012-1.1c2.14 0 2.54 1.4 2.54 3.22v3.5z"/></svg>
        </a>
      </div>
    </div>

    <h1 class="text-3xl font-bold mb-4 mt-4">{{ university.name }}</h1>
    <p class="text-gray-700 mb-2">{{ university.address }}</p>

    <section class="mb-4">
      <h2 class="text-xl font-semibold mb-1">O univerzitě</h2>
      <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ university.about }}</p>
    </section>

    <hr class="my-6 border-gray-300" />

    

    <section>
      <h2 class="text-xl font-semibold mb-4">Fakulty</h2>
        
          {{ university.field }}
          
    </section>
        <hr class="my-6 border-gray-300" />

    <p v-if="university.type" class="text-gray-700 italic mb-6"><strong>Typ školy:</strong> {{ university.type }}</p>

    <section class="mb-6 grid grid-cols-2 md:grid-cols-2 gap-4 text-gray-700">
      <div v-if="university.language"><strong>Jazyk:</strong> <span class="italic">{{ university.language }}</span></div>
      <div>
        <p class="text-gray-700 italic mb-2"><strong>Kontakt:</strong></p>
        <div class="mt-1 flex flex-col gap-1 italic text-sm">
          <span v-if="university.phone">{{ university.phone }}</span>
          <a v-if="university.email" :href="`mailto:${university.email}`" class="text-purple-600 hover:text-purple-400">{{ university.email }}</a>
          <a v-if="university.website" :href="university.website" target="_blank" class="text-purple-600 hover:text-purple-400">{{ university.website }}</a>
        </div>
      </div>
    </section>
  </div>
  </main>

</template>

<style scoped>
a {
  font-style: italic;
  color: #7c3aed;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
  color: #a78bfa;
}
</style>
