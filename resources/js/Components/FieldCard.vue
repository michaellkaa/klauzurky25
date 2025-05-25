<template>
  <div class="flex items-center gap-3 bg-white p-4 m-1 rounded-2xl shadow-md hover:shadow-lg transition cursor-pointer"
    @click="$router.push({ path: '/FacultyList', query: { field } })">
    <div class="text-2xl text-purple-600">-</div>
    <h3 class="text-md font-semibold text-gray-800 line-clamp-1">{{ field }}</h3>
  </div>
</template>

<script setup>
defineProps({
  field: String
})
</script>
