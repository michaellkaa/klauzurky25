<template>
<Logo/>
  <div class="space-y-8 p-4 md:p-8 bg-gray-100 min-h-screen">

    

  
    <!--loader stranky-->
    <div v-if="loading" class="fixed inset-0 bg-white/90 z-[150] flex items-center justify-center">
      <Loader />
    </div>

    <div class="flex justify-center">
      <div class="w-full max-w-2xl bg-white rounded-3xl shadow-lg p-8">

        <div v-if="currentQuestion < questions.length" class="mb-6">
          <div class="w-full bg-gray-300 rounded-full h-4">
            <div class="bg-black h-4 rounded-full transition-all duration-300" :style="{ width: progress + '%' }">
            </div>
          </div>
          <p class="text-sm text-gray-700 mt-2 font-black uppercase text-right">
            Otázka {{ currentQuestion + 1 }} z {{ questions.length }}
          </p>
        </div>

        <div v-if="currentQuestion < questions.length">
          <h2 class="text-2xl md:text-2xl font-black uppercase mb-6 text-black text-center">
            {{ questions[currentQuestion].text }}
          </h2>

          <div v-for="answer in questions[currentQuestion].answers" :key="answer.id" class="mb-4">
            <label
              class="flex items-center space-x-3 cursor-pointer p-4 rounded-xl border border-gray-300 hover:border-black hover:bg-gray-50 transition-all">
              <input type="radio" :value="answer.id" v-model="selectedAnswer"
                class="h-5 w-5 text-black focus:ring-black" />
              <span class="font-black uppercase text-black text-sm md:text-md">{{ answer.text }}</span>
            </label>
          </div>

          <button @click="nextQuestion"
            class="mt-6 w-full bg-black hover:bg-gray-900 text-white uppercase font-black px-6 py-3 rounded-xl shadow-lg transition-all">
            Další
          </button>
        </div>

        <!-- vysledek 
        <div v-else>
        <h2 class="text-2xl font-black uppercase mb-4 text-black text-center">Výsledek</h2>-->

        <!-- testovani
        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 mb-6">
          <pre class="font-black uppercase text-black">{{ result }}</pre>
        </div> -->


        <div v-if="result.scores" class="space-y-3">
          <h3 class="text-xl font-black uppercase mb-2 text-black text-center">Doporučené obory</h3>
          <div v-for="obor in topObory" :key="obor.name"
            class="bg-black text-white p-4 rounded-xl uppercase font-black text-center hover:bg-gray-800 transition">
            <router-link :to="`/field-faculties?field=${encodeURIComponent(obor.name)}`">{{ obor.name }}</router-link>
            <!--<span class="ml-2 text-sm font-normal">({{ obor.score }} bodů)</span>-->
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

import Logo from '../Components/Logo.vue'
import Loader from '../Components/Loader.vue'

const loading = ref(true)
const questions = ref([])
const currentQuestion = ref(0)
const selectedAnswer = ref(null)
const answersSelected = ref({})
const result = ref({})
const recommendedFieldsArray = ref([])

onMounted(async () => {
  loading.value = true
  try {
    const res = await axios.get('/api/quiz')
    questions.value = res.data
  } catch (err) {
    console.error('Chyba při načítání otázek:', err)
  } finally {
    loading.value = false
  }
})

const progress = computed(() => {
  if (!questions.value.length) return 0
  return ((currentQuestion.value) / questions.value.length) * 100
})

const topObory = computed(() => {
  if (!result.value.scores) return []
  const entries = Object.entries(result.value.scores).filter(([name, _]) => !name.startsWith('ostatní'))
  const maxScore = Math.max(...entries.map(([_, score]) => score))
  return entries
    .filter(([_, score]) => score === maxScore)
    .map(([name, score]) => ({ name, score }))
})

function nextQuestion() {
  if (!selectedAnswer.value) return alert('Vyber odpověď')
  answersSelected.value[questions.value[currentQuestion.value].id] = selectedAnswer.value
  selectedAnswer.value = null
  currentQuestion.value++
  if (currentQuestion.value >= questions.value.length) {
    submitResult()
  }
}

function submitResult() {
  console.log('Odesílám odpovědi:', answersSelected.value)

  axios.post('/api/quiz/result', { answers: answersSelected.value })
    .then(res => {
      console.log('Raw odpověď z API:', res.data)
      result.value = res.data

      recommendedFieldsArray.value = Array.isArray(res.data.recommended_fields)
        ? res.data.recommended_fields
        : []

      console.log('Pole doporučených oborů:', recommendedFieldsArray.value)

      if (recommendedFieldsArray.value.length) {
        axios.post('/api/quiz/store', { recommended_fields: recommendedFieldsArray.value })
          .then(res => console.log('Uloženo do DB:', res.data))
          .catch(err => console.error('Chyba při ukládání do DB:', err.response?.data || err))
      }
    })
    .catch(err => {
      console.error("Chyba při odeslání:", err.response?.data || err)
      alert("Něco se pokazilo, zkontroluj konzoli.")
    })
}
</script>
