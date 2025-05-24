<!-- components/FieldSlider.vue -->
<template>
  <section>
    <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
    <div class="flex overflow-x-auto gap-4 pb-4">
      <div
        v-for="field in fields"
        :key="field"
        class="min-w-[200px] flex-shrink-0"
      >
        <FieldCard :field="field" @click="onFieldClick" />
      </div>
    </div>
  </section>
</template>

<script setup>
import FieldCard from './FieldCard.vue'

defineProps({
  title: String,
  fields: Array
})

const emit = defineEmits(['select'])

function onFieldClick(field) {
  emit('select', field)
}
</script>
