<template>
    
    <div class="space-y-8 p-6">
      <div class="flex justify-between mb-20">
        <img src="../../../public/logo-sfyns.png" alt="" class="w-40 h-auto">
        <ProfileLink :user="user" />
      </div>
          <SearchBar />

  
      <UniversitySlider title="All Universities" />
      <FacultySlider title="All faculties" />
    </div>
  </template>
  
  <script setup>
  import UniversitySlider from '../components/UniversitySlider.vue'
  import FacultySlider from '../components/FacultySlider.vue';
  import ProfileLink from '../Components/ProfileLink.vue';
  import SearchBar from '../Components/SearchBar.vue';

  </script>