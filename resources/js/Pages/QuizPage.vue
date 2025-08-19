<template>
    <div class="space-y-8 p-3 md:p-6">


        <div class="flex justify-between">
        <router-link to="/">
            <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
        </router-link>
        <div class="flex flex-row">
            <ProfileLink />
            <CalendarLink />
        </div>
        </div>
    <!-- Obsah testu -->
    <div class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-6">
        
        <!-- Progress bar -->
         <!-- Progress bar -->
        <div v-if="currentQuestion < questions.length" class="mb-6">
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div 
            class="bg-blue-500 h-3 rounded-full transition-all duration-300" 
            :style="{ width: progress + '%' }">
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-1">
            Otázka {{ currentQuestion + 1 }} z {{ questions.length }}
        </p>
        </div>


        <!-- Otázky -->
        <div v-if="currentQuestion < questions.length">
          <h2 class="text-xl font-bold mb-4 text-gray-800">
            {{ questions[currentQuestion].text }}
          </h2>

          <div v-for="answer in questions[currentQuestion].answers" 
               :key="answer.id" 
               class="mb-3">
            <label 
              class="flex items-center space-x-3 bg-gray-50 hover:bg-gray-100 cursor-pointer p-3 rounded-lg border">
              <input 
                type="radio" 
                :value="answer.id" 
                v-model="selectedAnswer" 
                class="h-4 w-4 text-blue-600 focus:ring-blue-500" />
              <span class="text-gray-700">{{ answer.text }}</span>
            </label>
          </div>

          <button 
            @click="nextQuestion" 
            class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
            Další
          </button>
        </div>

        <!-- Výsledek -->
        <div v-else>
          <h2 class="text-xl font-bold mb-4 text-gray-800">Výsledek</h2>
          <div class="p-4 bg-gray-50 rounded-lg border">
            <pre>{{ result }}</pre>
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
      result: {}
    }
  },
  computed: {
    progress() {
      if (!this.questions.length) return 0
      return ((this.currentQuestion) / this.questions.length) * 100
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
    submitResult() {
      axios.post('/api/quiz/result', { answers: this.answersSelected })
        .then(res => {
          this.result = res.data
        })
        .catch(err => {
          console.error("Chyba při odeslání:", err.response?.data || err)
          alert("Něco se pokazilo, zkontroluj konzoli.")
        })
    }
  }
}
</script>
