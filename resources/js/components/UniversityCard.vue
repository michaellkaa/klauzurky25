<script setup>
defineProps({ university: Object })
</script>

<template>
  <router-link :to="`/university/${university.id}`">
    <div class="bg-white rounded-lg shadow-md w-64 overflow-hidden">
      <div class="p-4">
        <h3 class="text-lg font-semibold">{{ university.name }}</h3>
        <p class="text-sm text-gray-600">{{ university.location }}</p>
      </div>
    </div>
  </router-link>
</template>