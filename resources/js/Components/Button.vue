<template>
  <button
    @click="onClick"
    :class="buttonClasses"
    class="font-semibold py-2 px-6 rounded-lg transition"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'primary', // 'primary' | 'logout' | etc.
  },
  onClick: {
    type: Function,
    required: true,
  },
})

const buttonClasses = computed(() => {
  switch (props.type) {
    case 'logout':
      return 'bg-red-600 hover:bg-red-700 text-white'
    case 'primary':
    default:
      return 'bg-gray-400 hover:bg-gray-700 text-white'
  }
})
</script>
