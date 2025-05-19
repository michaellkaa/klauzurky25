<template>
    <div class="space-y-8 p-6">
      <section class="text-center">
        <h1 class="text-4xl font-bold">Start Finding Your Next Step</h1>
        <p class="text-gray-600 mt-2">Explore top universities in the Czech Republic</p>
      </section>
  
      <UniversitySlider title="All Universities" />
      <FacultySlider title="All faculties" />
    </div>
  </template>
  
  <script setup>
  import UniversitySlider from '../components/UniversitySlider.vue'
  import FacultySlider from '../components/FacultySlider.vue';
  </script>