<!-- components/FieldCard.vue -->
<template>
  <div
    class="flex items-center gap-3 bg-white p-4 rounded-2xl shadow-md hover:shadow-lg transition cursor-pointer "
    @click="$emit('click', field)"
  >
    <!-- Ikonka – můžeš změnit podle stylu (nebo použít emoji) -->
    <div class="text-2xl text-blue-600">📚</div>
    <h3 class="text-md font-semibold text-gray-800 line-clamp-1">{{ field }}</h3>
  </div>
</template>

<script setup>
defineProps({
  field: String
})
</script>
