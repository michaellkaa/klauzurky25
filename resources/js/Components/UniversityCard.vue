<script setup>
import HeartButton from './HeartButton.vue';

const props = defineProps({
  university: Object
})
</script>

<template>
  <div class="relative w-72">
    <div class="absolute top-2 right-2 z-50">
      <HeartButton :type="'university'" :id="university.id" :is-favorite="university.is_favorite" />
    </div>

    <router-link :to="`/university/${university.id}`" class="block">
      <div class="bg-white rounded-xl border-b-5 border-r-5 border-black shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 cursor-pointer">
        
        <div class="h-24 w-full bg-gray-100 border-b-2 border-black">
          <img v-if="university.banner_url" :src="university.banner_url" alt="University Banner"
               class="w-full h-full object-cover" />
        </div>

        <div class="p-4">
          <div class="flex items-center gap-3 mb-2">
            <img v-if="university.logo_url" :src="university.logo_url" alt="University Logo"
                 class="h-10 w-10 object-contain rounded-md" />
            <div>
              <h3 class="text-lg font-bold line-clamp-1">{{ university.name }}</h3>
              <p v-if="university.location" class="text-sm text-gray-600 line-clamp-1">{{ university.location }}</p>
            </div>
          </div>

          <div v-if="university.type || university.language" class="text-xs text-gray-500 space-y-1">
            <p v-if="university.type"><strong>Typ:</strong> {{ university.type }}</p>
            <p v-if="university.language"><strong>Jazyk výuky:</strong> {{ university.language }}</p>
          </div>
        </div>

      </div>
    </router-link>
  </div>
</template>
