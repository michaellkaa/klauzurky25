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
  <!-- Logo nahoře -->
  <div class="space-y-8 p-3 md:p-6">
    <div class="flex justify-between">
      <router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
      </router-link>
    </div>
  </div>

  <main class="max-w-3xl mx-auto p-8 font-sans text-black">
    <!-- Loader -->
    <div v-if="loading" class="text-center py-20 text-gray-500 text-sm uppercase font-bold">
      NAČÍTÁM DATA...
    </div>

    <!-- Obsah -->
    <div v-else-if="university" class="space-y-10">
      <!-- Banner + srdíčko (sjednocený styl s fakultami) -->
      <section v-if="university.banner_url" class="relative rounded-xl overflow-hidden border border-gray-300 shadow-sm mb-6">
        <img 
          v-if="university.banner_url" 
          :src="university.banner_url" 
          alt="Banner univerzity" 
          class="w-full h-48 object-cover"
          loading="lazy" 
        />
        <div class="absolute top-4 right-4">
          <HeartButton :type="'university'" :id="university.id" />
        </div>
        <!-- Socials -->
        <nav class="absolute top-4 left-4 flex gap-4 text-gray-700 text-lg font-bold">
          <a v-if="university.facebook" :href="university.facebook" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-black transition">
            FB
          </a>
          <a v-if="university.instagram" :href="university.instagram" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-black transition">
            IG
          </a>
          <a v-if="university.twitter" :href="university.twitter" target="_blank" rel="noopener" aria-label="Twitter" class="hover:text-black transition">
            TW
          </a>
        </nav>
      </section>

      <!-- Název + adresa -->
    
      <header class="space-y-1 border-b border-gray-300 pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight uppercase">{{ university.name }}</h1>
        <p v-if="university.address" class="text-base font-semibold text-gray-700">{{ university.address }}</p>
      </header>

      <!-- O univerzitě -->
      <section v-if="university.about">
        <p class="text-gray-800 font-medium">{{ university.about }}</p>
      </section>

      <!-- Fakulty -->
      <section v-if="university.field" class="mb-8 border-gray-300 border-y py-6">
        <h2 class="text-xl font-extrabold mb-6 uppercase text-black">Fakulty</h2>
        <p class="text-gray-900 text-sm leading-relaxed font-medium">{{ university.field }}</p>
      </section>

      <!-- Typ školy -->
      <p v-if="university.type" class="text-sm text-gray-800 font-medium">
        <strong class="font-bold uppercase">Typ školy:</strong> {{ university.type }}
      </p>

      <!-- Kontakt -->
       <!-- Contact info -->
      <section class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm text-gray-800 border-t border-gray-300 pt-6 font-medium">
        <p v-if="university.website"><span class="font-bold uppercase">Web: </span> 
          <a :href="university.website" target="_blank" rel="noopener" class="text-black font-semibold hover:underline break-words">
            {{ university.website }}
          </a>
        </p>
        <p v-if="university.email"><span class="font-bold uppercase">Email: </span> 
          <a :href="`mailto:${university.email}`" class="text-black font-semibold hover:underline">
            {{ university.email }}
          </a>
        </p>
        <p v-if="university.phone"><span class="font-bold uppercase">Telefon: </span> {{ university.phone }}</p>
      </section>

      
    </div>
    <div v-else-if="!university" class="space-y-10">cau</div>
  </main>
</template>

<style scoped>
a {
  font-style: normal;
  font-weight: bold;
  color: black;
  text-decoration: none;
  text-transform: uppercase;
}
a:hover {
  text-decoration: underline;
}
</style>
