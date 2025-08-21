<template>
  <div v-if="visible" class="bg-yellow-300 text-black p-4 rounded-md flex justify-between items-center mb-4 transition-all duration-300">
    <router-link :to="link" class="font-bold underline">
      {{ text }}
    </router-link>
    <button @click="dismiss" class="ml-4 font-bold">✕</button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  text: { type: String, required: true },
  link: { type: String, required: true }
})

const visible = ref(true)

function dismiss() {
  visible.value = false
}
</script>

