<template>
    
    <div class="space-y-8 p-6">
      <div class="flex justify-between mb-20">
        <img src="../../../public/logo-sfyns.png" alt="" class="w-40 h-auto">
        <ProfileLink  />
      </div>
          <SearchBar />

  
      <UniversitySlider title="All Universities" />
      <FacultySlider title="All faculties" />
    </div>
  </template>
  
  <script setup>
  import UniversitySlider from '../components/UniversitySlider.vue'
  import ProfileLink from '../Components/ProfileLink.vue';
  import SearchBar from '../Components/SearchBar.vue';
  import FacultySlider from '../Components/FacultySlider.vue';

  </script>