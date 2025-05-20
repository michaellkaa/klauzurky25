<script setup>
defineProps({ faculty: Object })
</script>

<template>
  <router-link :to="`/faculty/${faculty.id}`">
    <div class="bg-white rounded-2xl shadow-md w-72 overflow-hidden hover:shadow-lg transition-shadow duration-200">
      <!-- Banner -->
      <div v-if="faculty.banner_url" class="h-24 w-full bg-gray-100">
        <img :src="faculty.banner_url" alt="Faculty Banner" class="w-full h-full object-cover" />
      </div>

      <!-- Logo and Info -->
      <div class="p-4">
        <div class="flex items-center gap-3 mb-2">
          <img
            v-if="faculty.logo_url"
            :src="faculty.logo_url"
            alt="Faculty Logo"
            class="h-10 w-10 object-contain rounded-md"
          />
          <div>
            <h3 class="text-lg font-semibold line-clamp-1">{{ faculty.name }}</h3>
            <p v-if="faculty.university" class="text-sm text-gray-600 line-clamp-1">{{ faculty.university }}</p>
          </div>
        </div>

        <!-- Address or other details -->
        <div class="text-xs text-gray-500 space-y-1">
          <p v-if="faculty.address"><strong>Adresa:</strong> {{ faculty.address }}</p>
          <!-- Přidej další detaily, které chceš zobrazit, třeba web, email apod. -->
        </div>
      </div>
    </div>
  </router-link>
</template>
