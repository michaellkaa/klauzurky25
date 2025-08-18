<template>
  <div class="max-w-xl mx-auto p-4 bg-white rounded shadow">
    <div v-if="currentQuestion < questions.length">
      <h2 class="text-lg font-bold mb-2">{{ questions[currentQuestion].text }}</h2>
      <div v-for="answer in questions[currentQuestion].answers" :key="answer.id" class="mb-2">
        <label class="flex items-center space-x-2">
          <input type="radio" :value="answer.id" v-model="selectedAnswer" />
          <span>{{ answer.text }}</span>
        </label>
      </div>
      <button @click="nextQuestion" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Další</button>
    </div>

    <div v-else>
      <h2 class="text-lg font-bold mb-2">Výsledek</h2>
      <pre>{{ result }}</pre>
    </div>
  </div>
</template>

<script>
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
  mounted() {
    axios.get('/api/quiz').then(res => {
      this.questions = res.data
    })
  },
  methods: {
    nextQuestion() {
      if (!this.selectedAnswer) return alert('Vyber odpověď')
      this.answersSelected[this.questions[this.currentQuestion].id] = this.selectedAnswer
      console.log("Aktuální odpovědi:", this.answersSelected) // debug
      this.selectedAnswer = null
      this.currentQuestion++
      if (this.currentQuestion >= this.questions.length) {
        this.submitResult()
      }
    },
    submitResult() {
    console.log("Odesílám odpovědi:", this.answersSelected)
    axios.post('/api/quiz/result', { answers: this.answersSelected })
        .then(res => {
        console.log("Výsledek ze serveru:", res.data)
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
