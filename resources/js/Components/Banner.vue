<template>
  <div v-if="visible" class="bg-gray-200 text-black py-4 px-10 rounded-md flex justify-between items-center mb-4 transition-all duration-300">
    <router-link :to="link" class="font-semibold underline">
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

