<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import HeartButton from '../Components/HeartButton.vue'

const route = useRoute()
const faculty = ref(null)
const loading = ref(true)
const error = ref(null)

const showFullBc = ref(false)
const showFullMgr = ref(false)
const showFullDr = ref(false)

onMounted(async () => {
  loading.value = true
  error.value = null
  try {
    const res = await fetch(`/api/faculties/${route.params.id}`)
    if (!res.ok) throw new Error('Nepodařilo se načíst data fakulty')
    faculty.value = await res.json()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
})

const toggleShowBc = () => showFullBc.value = !showFullBc.value
const toggleShowMgr = () => showFullMgr.value = !showFullMgr.value
const toggleShowDr = () => showFullDr.value = !showFullDr.value

function truncatedText(text, maxLength = 120) {
  if (!text) return ''
  return text.length > maxLength ? text.slice(0, maxLength) + '…' : text
}
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
    <div v-if="loading" class="text-center py-20 text-gray-500 text-sm tracking-wider uppercase">
      Načítám data...
    </div>

    <div v-else-if="faculty" class="space-y-10">

      <!-- Banner + heart -->
      <section class="relative rounded-xl overflow-hidden border border-gray-300 shadow-sm">
        <img 
          v-if="faculty.banner_url" 
          :src="faculty.banner_url" 
          alt="Banner fakulty" 
          class="w-full h-48 object-cover"
          loading="lazy" 
        />
        <div class="absolute top-4 right-4">
          <HeartButton :type="'faculty'" :id="faculty.id" />
        </div>
        <!-- Socials -->
        <nav class="absolute top-4 left-4 flex gap-4 text-gray-700 text-lg font-bold">
          <a v-if="faculty.facebook_url" :href="faculty.facebook_url" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-black transition">
            FB
          </a>
          <a v-if="faculty.instagram_url" :href="faculty.instagram_url" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-black transition">
            IG
          </a>
          <a v-if="faculty.twitter_url" :href="faculty.twitter_url" target="_blank" rel="noopener" aria-label="Twitter" class="hover:text-black transition">
            TW
          </a>
        </nav>
      </section>

      <!-- Title -->
      <header class="space-y-1 border-b border-gray-300 pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight uppercase">{{ faculty.name }}</h1>
        <p class="text-base font-semibold text-gray-700">{{ faculty.university }}</p>
      </header>

      <!-- Description -->
      <section>
        <p class="text-gray-800 font-medium">{{ faculty.description }}</p>
      </section>

      <!-- Contact info -->
      <section class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm text-gray-800 border-t border-gray-300 pt-6 font-medium">
        <p><span class="font-bold uppercase">Adresa:</span> {{ faculty.address }}</p>
        <p v-if="faculty.website"><span class="font-bold uppercase">Web: </span> 
          <a :href="faculty.website" target="_blank" rel="noopener" class="text-black font-semibold hover:underline break-words">
            {{ faculty.website }}
          </a>
        </p>
        <p v-if="faculty.email"><span class="font-bold uppercase">Email: </span> 
          <a :href="`mailto:${faculty.email}`" class="text-black font-semibold hover:underline">
            {{ faculty.email }}
          </a>
        </p>
        <p v-if="faculty.phone"><span class="font-bold uppercase">Telefon: </span> {{ faculty.phone }}</p>
      </section>

      <!-- Admissions -->
      <section class="space-y-2 text-sm text-gray-800 border-t border-gray-300 pt-6 font-medium">
        <p v-if="faculty.application_link"><span class="font-bold uppercase">Přihláška: </span> 
          <a :href="faculty.application_link" target="_blank" rel="noopener" class="text-black font-semibold hover:underline">
            Podat přihlášku
          </a>
        </p>
        <p v-if="faculty.admission_notes"><span class="font-bold uppercase">Poznámky: </span> {{ faculty.admission_notes }}</p>
        <p v-if="faculty.open_day_dates"><span class="font-bold uppercase">Dny otevřených dveří: </span> {{ faculty.open_day_dates }}</p>
        <p v-if="faculty.open_day_url">
          <a :href="faculty.open_day_url" target="_blank" rel="noopener" class="text-black font-semibold hover:underline">
            Více o dnech otevřených dveří
          </a>
        </p>
        <p v-if="faculty.exam_dates"><span class="font-bold uppercase">Termíny zkoušek: </span> {{ faculty.exam_dates }}</p>
        <p v-if="faculty.application_fee"><span class="font-bold uppercase">Poplatek: </span> {{ faculty.application_fee }}</p>
        <p v-if="faculty.application_deadlines"><span class="font-bold uppercase">Deadline: </span> {{ faculty.application_deadlines }}</p>
      </section>

      <!-- Programs -->
      <section class="border-t border-gray-300 pt-6">
        <h2 class="text-xl font-extrabold mb-6 uppercase text-black">Studijní programy</h2>

        <div class="space-y-6 text-gray-900 text-sm leading-relaxed font-medium">
          <div>
            <h3 class="font-bold uppercase mb-1">Bakalářské</h3>
            <p>
              {{ showFullBc ? faculty.bc_programs : truncatedText(faculty.bc_programs) }}
              <button v-if="faculty.bc_programs && faculty.bc_programs.length > 120" @click="toggleShowBc"
                class="ml-2 font-bold text-black hover:underline focus:outline-none">
                {{ showFullBc ? 'ZOBRAZIT MÉNĚ' : 'VÍCE' }}
              </button>
            </p>
          </div>

          <div>
            <h3 class="font-bold uppercase mb-1">Magisterské</h3>
            <p>
              {{ showFullMgr ? faculty.mgr_programs : truncatedText(faculty.mgr_programs) }}
              <button v-if="faculty.mgr_programs && faculty.mgr_programs.length > 120" @click="toggleShowMgr"
                class="ml-2 font-bold text-black hover:underline focus:outline-none">
                {{ showFullMgr ? 'ZOBRAZIT MÉNĚ' : 'VÍCE' }}
              </button>
            </p>
          </div>

          <div>
            <h3 class="font-bold uppercase mb-1">Doktorské</h3>
            <p>
              {{ showFullDr ? faculty.dr_programs : truncatedText(faculty.dr_programs) }}
              <button v-if="faculty.dr_programs && faculty.dr_programs.length > 120" @click="toggleShowDr"
                class="ml-2 font-bold text-black hover:underline focus:outline-none">
                {{ showFullDr ? 'ZOBRAZIT MÉNĚ' : 'VÍCE' }}
              </button>
            </p>
          </div>
        </div>
      </section>

    </div>
  </main>
</template>
