<template>

  <div class="space-y-8 p-3 md:p-6">
    <div class="flex justify-between">
      <router-link to="/">
        <img src="../../../public/logo-sfyns.png" alt="Logo" class="w-28 md:w-40 h-auto" />
      </router-link>
      <div class="flex flex-row">
        <ProfileLink />
        <CalendarLink />
      </div>
    </div>

    <SearchBar />

    <FieldSlider title="" />
    <UniversitySlider title="Univerzity" />
    <FacultySlider title="Fakulty" />
    <RecommendedFaculties title="Doporučené" />
    
  </div>
</template>

<script setup>
import UniversitySlider from '../Components/UniversitySlider.vue'
import ProfileLink from '../Components/ProfileLink.vue';
import SearchBar from '../Components/SearchBar.vue';
import FacultySlider from '../Components/FacultySlider.vue';
import FieldSlider from '../Components/FieldSLider.vue';
import CalendarLink from '../Components/CalendarLink.vue';
import RecommendedFaculties from '../Components/RecommendedFaculties.vue'
import { ref, onMounted } from 'vue'

const fields = ref([])

onMounted(async () => {
  const res = await fetch('/api/zamereni')
  fields.value = await res.json()
})

function filterByField(field) {
  console.log('Vybrané zaměření:', field)
}
</script>