<script setup>
defineProps({ university: Object })
</script>

<template>
  <router-link :to="`/university/${university.id}`">
    <div class="bg-white rounded-2xl shadow-md w-72 overflow-hidden hover:shadow-lg transition-shadow duration-200">
      <!-- Banner -->
      <div v-if="university.banner_url" class="h-24 w-full bg-gray-100">
        <img :src="university.banner_url" alt="University Banner" class="w-full h-full object-cover" />
      </div>

      <!-- Logo and Info -->
      <div class="p-4">
        <div class="flex items-center gap-3 mb-2">
          <img
            v-if="university.logo_url"
            :src="university.logo_url"
            alt="University Logo"
            class="h-10 w-10 object-contain rounded-md"
          />
          <div>
            <h3 class="text-lg font-semibold line-clamp-1">{{ university.name }}</h3>
            <p class="text-sm text-gray-600 line-clamp-1">{{ university.location }}</p>
          </div>
        </div>

        <!-- Type, Language -->
        <div class="text-xs text-gray-500 space-y-1">
          <p><strong>Typ:</strong> {{ university.type || 'N/A' }}</p>
          <p><strong>Jazyk výuky:</strong> {{ university.language || 'N/A' }}</p>
        </div>
      </div>
    </div>
  </router-link>
</template>