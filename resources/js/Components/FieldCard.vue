<template>
  <div
    class="flex items-center gap-4 bg-white border-2 border-black px-6 py-4 m-2 rounded-xl hover:bg-black hover:text-white transition-colors duration-200 cursor-pointer"
    @click="$router.push({ path: '/FacultyList', query: { field } })"
  >
    <!-- místo ikony může být čárka/tečka/symbol 
    <div class="text-2xl font-bold">–</div>-->
    <h3 class="text-lg font-bold tracking-tight line-clamp-1">
      {{ field }}
    </h3>
  </div>
</template>

<script setup>
defineProps({
  field: String
})
</script>
