<template>
  <button
    @click="goToCalendar"
    aria-label="Calendar"
    class="py-2 pl-6 rounded-full  transition-colors"
    title="Kalendář"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      class="h-8 w-8 text-black hover:text-gray-600"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
      stroke-width="2"
    >
      <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
      <line x1="16" y1="2" x2="16" y2="6" />
      <line x1="8" y1="2" x2="8" y2="6" />
      <line x1="3" y1="10" x2="21" y2="10" />
    </svg>
  </button>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()

function goToCalendar() {
  router.push('/calendar') 
}
</script>
