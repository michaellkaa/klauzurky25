<template>
    
    <div class="space-y-8 p-6">
      <div class="flex justify-between mb-20">
        <img src="../../../public/logo-sfyns.png" alt="" class="w-40 h-auto">
        <ProfileLink  />
      </div>
          <SearchBar />

  
      <UniversitySlider title="Univerzity" />
      <FacultySlider title="Fakulty" />
      <FieldSlider title="Zaměření"/>
    </div>
  </template>
  
  <script setup>
  import UniversitySlider from '../components/UniversitySlider.vue'
  import ProfileLink from '../Components/ProfileLink.vue';
  import SearchBar from '../Components/SearchBar.vue';
  import FacultySlider from '../Components/FacultySlider.vue';
  import FieldSlider from '../Components/FieldSLider.vue';
import { ref, onMounted } from 'vue'

const fields = ref([])

onMounted(async () => {
  const res = await fetch('/api/zamereni')
  fields.value = await res.json()
})

function filterByField(field) {
  // Např. redirect na stránku se seznamem fakult podle zaměření
  console.log('Vybrané zaměření:', field)
}
  </script>