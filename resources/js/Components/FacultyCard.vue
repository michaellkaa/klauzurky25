<script setup>
import { useRouter } from 'vue-router'
import HeartButton from './HeartButton.vue'

const props = defineProps({ faculty: Object })

const router = useRouter()

function goToFaculty() {
  console.log('Card clicked: navigating to faculty', props.faculty.id)
  router.push(`/faculty/${props.faculty.id}`)
}

</script>

<template>
  
  <div class="relative w-72">
    <div class="absolute top-2 right-2 z-50">
      <HeartButton :type="'faculty'" :id="faculty.id" :is-favorite="faculty.is_favorite" />

    </div>

    <div
      class="relative bg-white rounded-xl border-b-5 border-r-5 border-black shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 cursor-pointer"
      @click="goToFaculty">
      <div v-if="faculty.banner_url" class="relative h-24 w-full bg-gray-100">
        <img :src="faculty.banner_url" alt="Faculty Banner" class="w-full h-full object-cover border-b-2 border-black" />
      </div>

      <div class="p-4">
        <div class="flex items-center gap-3 mb-2">
          <img v-if="faculty.logo_url" :src="faculty.logo_url" alt="Faculty Logo"
            class="h-10 w-10 object-contain rounded-md " />
          <div>
            <h3 class="text-lg font-bold line-clamp-1">{{ faculty.name }}</h3>
            <p v-if="faculty.university" class="text-sm text-gray-600 line-clamp-1">{{ faculty.university }}</p>
          </div>
        </div>

        <div class="text-xs text-gray-500 space-y-1">
          <p v-if="faculty.address"> {{ faculty.address }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
