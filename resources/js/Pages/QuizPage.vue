<template>
  <div class="space-y-8 p-4 md:p-8 bg-gray-100 min-h-screen">

    <div class="flex justify-between items-center mb-8">
      <router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-32 md:w-40 h-auto" />
      </router-link>
      <div class="flex space-x-4">
        <ProfileLink />
        <CalendarLink />
      </div>
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

        <!-- Result 
        <div v-else>
        <h2 class="text-2xl font-black uppercase mb-4 text-black text-center">Výsledek</h2>-->

        <!-- Skóre raw
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

<script>
import ProfileLink from '../Components/ProfileLink.vue';
import CalendarLink from '../Components/CalendarLink.vue';
import axios from 'axios'

export default {
  data() {
    return {
      questions: [],
      currentQuestion: 0,
      selectedAnswer: null,
      answersSelected: {},
      result: {},
      recommendedFieldsArray: []
    }
  },
  computed: {
    progress() {
      if (!this.questions.length) return 0
      return ((this.currentQuestion) / this.questions.length) * 100
    },
    topObory() {
  if (!this.result.scores) return []
  const entries = Object.entries(this.result.scores)
    .filter(([name, _]) => !name.startsWith('ostatní'))
  const maxScore = Math.max(...entries.map(([_, score]) => score))
  return entries
    .filter(([_, score]) => score === maxScore)
    .map(([name, score]) => ({ name, score }))
}


  },

  mounted() {
    axios.get('/api/quiz').then(res => {
      this.questions = res.data
    })
  },
  methods: {
    nextQuestion() {
      if (!this.selectedAnswer) return alert('Vyber odpověď')
      this.answersSelected[this.questions[this.currentQuestion].id] = this.selectedAnswer
      this.selectedAnswer = null
      this.currentQuestion++
      if (this.currentQuestion >= this.questions.length) {
        this.submitResult()
      }
    },
    //funkce na ulozeni a odeslani vysledku
    submitResult() { //uz to funguje, na tohle nesahat
  console.log('Odesílám odpovědi:', this.answersSelected)

  axios.post('/api/quiz/result', { answers: this.answersSelected })
    .then(res => {
      console.log('Raw odpověď z API:', res.data)
      this.result = res.data

      this.recommendedFieldsArray = Array.isArray(res.data.recommended_fields)
        ? res.data.recommended_fields
        : []

      console.log('Pole doporučených oborů:', this.recommendedFieldsArray)

      if (this.recommendedFieldsArray.length) {
        axios.post('/api/quiz/store', { recommended_fields: this.recommendedFieldsArray })
          .then(res => console.log('Uloženo do DB:', res.data))
          .catch(err => console.error('Chyba při ukládání do DB:', err.response?.data || err))
      }
    })
    .catch(err => {
      console.error("Chyba při odeslání:", err.response?.data || err)
      alert("Něco se pokazilo, zkontroluj konzoli.")
    })
}



  }
}

</script>
