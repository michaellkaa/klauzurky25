<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import HeartButton from '@/components/HeartButton.vue'

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
  <div class="space-y-8 p-6">
    <div class="flex justify-between">
      <router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-40 h-auto" />
      </router-link>
    </div>
  </div>
  <main class="max-w-3xl mx-auto p-8 font-sans text-gray-900">
    <div v-if="loading" class="text-center py-20 text-gray-500 text-sm">Načítám data...</div>
    <article v-else-if="faculty" class="space-y-8">

      <!-- Banner s heart button a sociálními sítěmi -->
      <section class="relative rounded-md overflow-hidden border border-gray-200">
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
        <nav class="absolute top-4 left-4 flex gap-4 text-gray-600 text-xl">
          <a
            v-if="faculty.facebook_url"
            :href="faculty.facebook_url"
            target="_blank"
            rel="noopener"
            aria-label="Facebook"
            class="hover:text-blue-600 transition"
          >
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.87v-6.99H8.09v-2.88h2.41v-2.2c0-2.39 1.43-3.7 3.63-3.7 1.05 0 2.15.18 2.15.18v2.37h-1.21c-1.2 0-1.57.74-1.57 1.5v1.85h2.67l-.43 2.88h-2.24v6.99A10 10 0 0 0 22 12z"/></svg>
          </a>
          <a
            v-if="faculty.instagram_url"
            :href="faculty.instagram_url"
            target="_blank"
            rel="noopener"
            aria-label="Instagram"
            class="hover:text-pink-500 transition"
          >
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M7.75 2A5.75 5.75 0 0 0 2 7.75v8.5A5.75 5.75 0 0 0 7.75 22h8.5A5.75 5.75 0 0 0 22 16.25v-8.5A5.75 5.75 0 0 0 16.25 2h-8.5zm0 1.5h8.5A4.25 4.25 0 0 1 20.5 7.75v8.5a4.25 4.25 0 0 1-4.25 4.25h-8.5A4.25 4.25 0 0 1 3.5 16.25v-8.5A4.25 4.25 0 0 1 7.75 3.5zm8.125 2a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25zM12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 1.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7z"/></svg>
          </a>
          <a
            v-if="faculty.twitter_url"
            :href="faculty.twitter_url"
            target="_blank"
            rel="noopener"
            aria-label="Twitter"
            class="hover:text-sky-500 transition"
          >
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 19c7.5 0 11.6-6.3 11.6-11.7 0-.18 0-.35-.01-.52A8.36 8.36 0 0 0 22 5.6a8.22 8.22 0 0 1-2.36.64 4.13 4.13 0 0 0 1.8-2.27 8.26 8.26 0 0 1-2.6.99 4.11 4.11 0 0 0-7 3.75A11.67 11.67 0 0 1 3 4.9a4.1 4.1 0 0 0 1.27 5.48 4.08 4.08 0 0 1-1.86-.5v.05a4.11 4.11 0 0 0 3.3 4 4.07 4.07 0 0 1-1.85.07 4.11 4.11 0 0 0 3.83 2.84A8.24 8.24 0 0 1 4 17.8a11.6 11.6 0 0 0 7.9 2.3"/></svg>
          </a>
        </nav>
      </section>

      <!-- Název a univerzita -->
      <header class="space-y-1">
        <h1 class="text-3xl font-semibold tracking-tight">{{ faculty.name }}</h1>
        <p class="text-sm text-gray-500">{{ faculty.university }}</p>
      </header>

      <!-- Popis -->
      <section>
        <p class="leading-relaxed whitespace-pre-line text-gray-800">{{ faculty.description }}</p>
      </section>

      <!-- Kontakt -->
      <section class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm text-gray-600 border-t border-gray-200 pt-6">
        <p><strong>Adresa:</strong> {{ faculty.address }}</p>
        <p v-if="faculty.website"><strong>Web:</strong> <a :href="faculty.website" target="_blank" rel="noopener" class="text-blue-600 hover:underline break-words">{{ faculty.website }}</a></p>
        <p v-if="faculty.email"><strong>Email:</strong> <a :href="`mailto:${faculty.email}`" class="text-blue-600 hover:underline">{{ faculty.email }}</a></p>
        <p v-if="faculty.phone"><strong>Telefon:</strong> {{ faculty.phone }}</p>
      </section>

      <!-- Důležité odkazy -->
      <section class="mt-6 space-y-2 text-sm text-gray-600 border-t border-gray-200 pt-6">
        <p v-if="faculty.application_link"><strong>Přihláška:</strong> <a :href="faculty.application_link" target="_blank" rel="noopener" class="text-blue-600 hover:underline">Podat přihlášku</a></p>
        <p v-if="faculty.admission_notes"><strong>Poznámky k přijímacímu řízení:</strong> {{ faculty.admission_notes }}</p>
        <p v-if="faculty.open_day_dates"><strong>Dny otevřených dveří:</strong> {{ faculty.open_day_dates }}</p>
        <p v-if="faculty.open_day_url"><a :href="faculty.open_day_url" target="_blank" rel="noopener" class="text-blue-600 hover:underline">Více o dnech otevřených dveří</a></p>
        <p v-if="faculty.exam_dates"><strong>Termíny přijímacích zkoušek:</strong> {{ faculty.exam_dates }}</p>
        <p v-if="faculty.application_fee"><strong>Poplatek za přihlášku:</strong> {{ faculty.application_fee }}</p>
        <p v-if="faculty.application_deadlines"><strong>Termíny podání přihlášek:</strong> {{ faculty.application_deadlines }}</p>
      </section>

      <!-- Studijní programy -->
      <section class="mt-8 border-t border-gray-200 pt-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900">Studijní programy</h2>

        <div class="space-y-6 text-gray-800 text-sm leading-relaxed">

          <div>
            <h3 class="font-medium mb-1">Bakalářské</h3>
            <p>
              {{ showFullBc ? faculty.bc_programs : truncatedText(faculty.bc_programs) }}
              <button
                v-if="faculty.bc_programs && faculty.bc_programs.length > 120"
                @click="toggleShowBc"
                class="ml-2 text-blue-600 hover:underline focus:outline-none"
                aria-label="Zobrazit více bakalářských programů"
              >
                {{ showFullBc ? 'Zobrazit méně' : 'Více' }}
              </button>
            </p>
          </div>

          <div>
            <h3 class="font-medium mb-1">Magisterské</h3>
            <p>
              {{ showFullMgr ? faculty.mgr_programs : truncatedText(faculty.mgr_programs) }}
              <button
                v-if="faculty.mgr_programs && faculty.mgr_programs.length > 120"
                @click="toggleShowMgr"
                class="ml-2 text-blue-600 hover:underline focus:outline-none"
                aria-label="Zobrazit více magisterských programů"
              >
                {{ showFullMgr ? 'Zobrazit méně' : 'Více' }}
              </button>
            </p>
          </div>

          <div>
            <h3 class="font-medium mb-1">Doktorské</h3>
            <p>
              {{ showFullDr ? faculty.dr_programs : truncatedText(faculty.dr_programs) }}
              <button
                v-if="faculty.dr_programs && faculty.dr_programs.length > 120"
                @click="toggleShowDr"
                class="ml-2 text-blue-600 hover:underline focus:outline-none"
                aria-label="Zobrazit více doktorských programů"
              >
                {{ showFullDr ? 'Zobrazit méně' : 'Více' }}
              </button>
            </p>
          </div>

        </div>
      </section>

    </article>
  </main>
</template>

<style scoped>
a {
  text-decoration: none;
  font-style: italic;
  color: #7c3aed; /* fialová */
}

a:hover {
  text-decoration: underline;
  color: #a78bfa; /* světlejší fialová na hover */
}

button {
  background: none;
  border: none;
  cursor: pointer;
  font-weight: 600;
  color: #7c3aed;
}

button:hover {
  color: #a78bfa;
}

button:focus {
  outline: 2px solid #7c3aed;
  outline-offset: 2px;
}
</style>
